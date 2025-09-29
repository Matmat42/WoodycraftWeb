<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    // Définition de la relation avec le modèle Puzzle
    public function puzzles()
    {
        return $this->hasMany(Puzzle::class);
    }
}