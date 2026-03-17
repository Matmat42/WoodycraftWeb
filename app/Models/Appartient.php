<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appartient extends Model
{
    protected $table = 'appartients'; // confirme que ta table s’appelle bien comme ça
    protected $fillable = ['panier_id','commande_id','puzzle_id','quantite','prix_unitaire'];
    protected $casts = [
        'quantite' => 'integer',
        'prix_unitaire' => 'float',
    ];

    public function panier(): BelongsTo
    {
        return $this->belongsTo(Panier::class, 'panier_id');
    }

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }

    public function puzzle(): BelongsTo
    {
        return $this->belongsTo(Puzzle::class, 'puzzle_id');
    }

    protected static function booted()
    {
        static::saving(function (self $l) {
            $pu = $l->prix_unitaire ?? ($l->puzzle->prix ?? 0);
            $q  = max(1, (int)($l->quantite ?? 1));
            $l->total_ligne = $pu * $q;       // <-- remplit la colonne avant INSERT/UPDATE
        });
    }


    /** Total de la ligne (affichage) */
    public function getTotalLigneAttribute(): float
    {
        $pu = $this->prix_unitaire ?? ($this->puzzle->prix ?? 0);
        return (float) $pu * max(0, (int)($this->quantite ?? 0));
    }
}
