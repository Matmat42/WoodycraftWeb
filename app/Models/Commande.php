<?php

// app/Models/Commande.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commande extends Model
{
    protected $fillable = [
        'user_id',
        'adresse_livraison_id',
        'adresse_facturation_id',
        'mode_paiement',
        'montant_total',      // <-- AJOUTE ICI
        'date_commande',      // (utile si tu l’écris)
    ];

    protected $casts = [
        'montant_total' => 'float',
        'date_commande' => 'datetime',
    ];

    public function adresseLivraison(): BelongsTo
    {
        return $this->belongsTo(Adresse::class, 'adresse_livraison_id');
    }

    public function adresseFacturation(): BelongsTo
    {
        return $this->belongsTo(Adresse::class, 'adresse_facturation_id');
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(Appartient::class, 'commande_id');
        // ou: return $this->hasMany(LigneCommande::class, 'commande_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function avis(): HasMany
    {
        return $this->hasMany(Avis::class, 'commande_id');
    }
}
