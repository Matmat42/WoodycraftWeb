
# WoodyCraft

Application e‑commerce (Laravel 10) pour la vente de puzzles en bois : navigation par catégories, fiches produit, panier, passage de commande (chèque / stub PayPal), facture PDF et **avis sur commande**.

---

## 🧱 Stack technique

- **PHP 8.2+**, **Laravel 10**
- Blade, Tailwind (via Breeze / layout maison)
- **MySQL** / MariaDB
- **barryvdh/laravel-dompdf** pour la facture PDF
- Auth Laravel (login/register, vérification d’e‑mail optionnelle)
- Politiques/authorizations pour sécuriser l’accès aux commandes & avis

---

## ✨ Fonctionnalités principales

- Parcours catalogue : catégories → puzzles → fiche
- Panier persistant (1 panier “ouvert” par utilisateur)
- Passage de commande :
  - choix adresse de livraison & de facturation
  - **réemploi automatique** de l’adresse de la 1ʳᵉ commande (modifiable)
  - paiement “Chèque” (PDF) ou **stub PayPal** (routes et écran de succès/annulation)
- Facture **PDF** téléchargeable
- **Avis** sur une commande (1 avis par commande & par utilisateur)
- Espace “Mes commandes” + détail d’une commande

---

## 🗂️ Modèle de données (tables)

> Les tables “par défaut” de Laravel (migrations, password_reset_tokens, personal_access_tokens, failed_jobs) existent également mais ne sont pas détaillées ci‑dessous.

### 1) `users`
| Colonne | Type | Remarques |
|---|---|---|
| id | BIGINT | PK |
| name | VARCHAR |  |
| email | VARCHAR | unique |
| password | VARCHAR |  |
| email_verified_at | TIMESTAMP NULL |  |
| remember_token | VARCHAR(100) NULL |  |
| created_at / updated_at | TIMESTAMP |  |

**Relations :**  
`hasMany(Adresse)`, `hasMany(Commande)`, `hasMany(Avis)`, `hasOne(Panier ouvert)`

---

### 2) `categories`
| Colonne | Type | Remarques |
|---|---|---|
| id | BIGINT | PK |
| nom | VARCHAR |  |
| created_at / updated_at | TIMESTAMP |  |

**Relations :** `hasMany(Puzzle)`

---

### 3) `puzzles`
| Colonne | Type | Remarques |
|---|---|---|
| id | BIGINT | PK |
| categorie_id | BIGINT | FK → categories.id (indexé) |
| nom | VARCHAR |  |
| description | TEXT NULL |  |
| prix | DECIMAL(10,2) |  |
| stock | INT |  |
| image | VARCHAR NULL | chemin dans `storage/app/public/...` |
| created_at / updated_at | TIMESTAMP |  |

**Relations :** `belongsTo(Categorie)`

---

### 4) `adresses`
| Colonne | Type | Remarques |
|---|---|---|
| id | BIGINT | PK |
| user_id | BIGINT | FK → users.id (indexé) |
| numero | VARCHAR |  |
| rue | VARCHAR |  |
| ville | VARCHAR |  |
| code_postal | VARCHAR |  |
| pays | VARCHAR |  |
| type | ENUM('livraison','facturation') | usage informatif ; non bloquant |
| created_at / updated_at | TIMESTAMP |  |

**Relations :** `belongsTo(User)`

---

### 5) `paniers`
| Colonne | Type | Remarques |
|---|---|---|
| id | BIGINT | PK |
| user_id | BIGINT | FK → users.id (indexé) |
| statut | TINYINT | **0 = ouvert**, **1 = validé** |
| created_at / updated_at | TIMESTAMP |  |

**Relations :** `belongsTo(User)`, `hasMany(Appartient)`

> Un seul panier **statut = 0** par utilisateur.

---

### 6) `commandes`
| Colonne | Type | Remarques |
|---|---|---|
| id | BIGINT | PK |
| user_id | BIGINT | FK → users.id (indexé) |
| adresse_livraison_id | BIGINT | FK → adresses.id |
| adresse_facturation_id | BIGINT | FK → adresses.id |
| mode_paiement | ENUM('paypal','cheque') |  |
| montant_total | DECIMAL(10,2) | somme des lignes au moment de la création |
| date_commande | TIMESTAMP | `now()` à l’enregistrement |
| created_at / updated_at | TIMESTAMP |  |

**Relations :** `belongsTo(User)`, `belongsTo(Adresse, 'adresse_livraison_id')`, `belongsTo(Adresse, 'adresse_facturation_id')`, `hasMany(Appartient)`, `hasOne(Avis)`

---

### 7) `appartients`  *(table unique des **lignes**)*
> Une ligne peut appartenir **soit** à un panier, **soit** à une commande.

| Colonne | Type | Remarques |
|---|---|---|
| id | BIGINT | PK |
| panier_id | BIGINT NULL | FK → paniers.id (indexé, **nullable**) |
| commande_id | BIGINT NULL | FK → commandes.id (indexé, **nullable**) |
| puzzle_id | BIGINT | FK → puzzles.id (indexé) |
| quantite | INT | >= 1 |
| prix_unitaire | DECIMAL(10,2) | capture du prix au moment de l’ajout |
| total_ligne | DECIMAL(10,2) | prix_unitaire × quantite |
| created_at / updated_at | TIMESTAMP |  |

**Relations :** `belongsTo(Panier)`, `belongsTo(Commande)`, `belongsTo(Puzzle)`

---

### 8) `avis`
| Colonne | Type | Remarques |
|---|---|---|
| id | BIGINT | PK |
| commande_id | BIGINT | FK → commandes.id (unique avec user_id) |
| user_id | BIGINT | FK → users.id (unique avec commande_id) |
| note | TINYINT | 1..5 |
| commentaire | TEXT NULL |  |
| created_at / updated_at | TIMESTAMP |  |

**Contraintes :** **unique (`commande_id`, `user_id`)** pour garantir **un avis par commande et par utilisateur**.  
**Relations :** `belongsTo(Commande)`, `belongsTo(User)`

---

## 🔁 Flux métier (récapitulatif)

1. **Catalogue** → catégories → puzzles → *Ajouter au panier*
2. **Panier** — lignes stockées dans `appartients` (colonne `panier_id`)
3. **Validation** — création `commandes` + **copie** des lignes du panier vers `appartients` (colonne `commande_id`) + fermeture du panier (`statut = 1`)
4. **Paiement**
   - `cheque` : redirection page “Merci”, **facture PDF** disponible
   - `paypal` (stub) : routes `paypal.start/success/cancel`
5. **Avis** — sur la page de la commande : bouton “Écrire un avis”, formulaire, sauvegarde dans `avis`

---

## 🔒 Sécurité & autorisations

- Accès aux routes *dashboard* sous `auth` + `verified`
- Policies :
  - **CommandePolicy::view** → un utilisateur ne peut voir **que ses commandes**
  - **Avis** : contrôle supplémentaire pour empêcher plusieurs avis sur la même commande

---

## 📦 Installation & lancement

```bash
git clone https://github.com/Fares685/woodycraft.git
cd woodycraft

# Dépendances
composer install
cp .env.example .env
php artisan key:generate

# Configurer .env (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
php artisan migrate

# Lier le storage pour les images produits
php artisan storage:link

# Lancer le serveur
php artisan serve
```

> Si vous utilisez Laragon, placez le dossier dans `C:\laragon\www\` puis accédez via `http://woodycraft.test` (ou `http://localhost/...`).

---

## 🧪 Données de test (optionnel)

- Créez quelques catégories & puzzles via seeders ou tinker
- Connectez‑vous, ajoutez des produits au panier puis passez une commande

---

## 🧾 Facturation (PDF)

- Paquet : `barryvdh/laravel-dompdf`
- Vue : `resources/views/commandes/facture.blade.php`
- Route : `dashboard/commandes/{commande}/facture`  
  Accès restreint au propriétaire de la commande

---

## 🚦 Routes principales (extraits)

- Publiques :  
  `/` (accueil), `/categories`, `/categories/{id}`, `/puzzles/{puzzle}`
- Auth / Dashboard :  
  `/dashboard/panier` (index, add, update, remove, checkout)  
  `/dashboard/commandes` (index, create, store, show, facture, merci)  
  `/commandes/{commande}/avis/create` (create) • `/commandes/{commande}/avis` (store)

---

## 🔧 Commandes utiles

```bash
# Créer migration Avis (exemple)
php artisan make:migration create_avis_table

# Rafraîchir la base
php artisan migrate:fresh --seed
```

---

## 🧭 Organisation du code (raccourci)

- **app/Models** : `Categorie`, `Puzzle`, `Panier`, `Commande`, `Adresse`, `Appartient`, `Avis`, `User`
- **app/Http/Controllers** : `CategorieController`, `PuzzleController`, `PanierController`, `CommandeController`, `AdresseController`, `AvisController`, `PaypalController`, `HomeController`
- **resources/views** : `categories`, `puzzles`, `panier`, `commandes` (`index`, `create`, `show`, `facture`, `merci`), `avis` (`create`), `layouts`

---

## 🛠️ Roadmap (idées)

- Paiement PayPal/Stripe **réel**
- Notation/avis par **produit**
- Codes promo / transport / TVA
- Admin CRUD (produits, catégories, commandes, avis)

---

## 📄 Licence

Projet pédagogique — usage libre pour l’apprentissage.
