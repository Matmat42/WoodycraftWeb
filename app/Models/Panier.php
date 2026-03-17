<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Panier extends Model
{
    protected $table = 'paniers';
    protected $fillable = ['user_id', 'statut']; // statut: 0 brouillon, 1 validé
    protected $casts = ['statut' => 'integer'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Lignes du panier => Appartient (précharge puzzle) */
    public function lignes(): HasMany
    {
        return $this->hasMany(Appartient::class, 'panier_id')->with('puzzle');
    }

    /** Total du panier (somme des lignes) */
    public function getTotalAttribute(): float
    {
        return (float) $this->lignes->sum(function ($l) {
            $pu = $l->prix_unitaire ?? ($l->puzzle->prix ?? 0);
            return $pu * max(0, (int)($l->quantite ?? 0));
        });
    }

    /** Nombre total d’articles */
    public function getItemsCountAttribute(): int
    {
        return (int) $this->lignes->sum('quantite');
    }
}
