<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adresse extends Model
{
    use HasFactory;
    protected $fillable = [
        'numero',
        'rue',
        'ville',
        'code_postal',
        'pays',
        'user_id',
        'type'
    ];

    public function user()
    { 
        return $this->belongsTo(User::class); 
    }

}
