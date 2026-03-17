<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puzzle extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'image',
        'stock',
        'prix',
        'categorie_id', 
    ];

    public function getImageUrlAttribute(): string
    {
        $path = $this->image ?: '';
        $path = str_replace('\\', '/', $path); // sécurité Windows
        $path = ltrim($path, '/');             // évite // dans l’URL
        return asset($path);
    }


    public function categorie()
    {
        return $this->belongsTo(\App\Models\Categorie::class, 'categorie_id');
    }
    
    public function lignes()
    { 
        return $this->hasMany(Appartient::class); 
    }
}
