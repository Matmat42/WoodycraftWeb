<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LigneCommande extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id', 
        'puzzle_id', 
        'quantite', 
        'prix_unitaire', 
        'total_ligne',
    ];

    public function commande() { return $this->belongsTo(Commande::class); }
    public function puzzle()   { return $this->belongsTo(Puzzle::class); }
}
