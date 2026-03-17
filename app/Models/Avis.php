<?php

// app/Models/Avis.php
// app/Models/Avis.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avis extends Model
{
    protected $table = 'avis';

    protected $fillable = [
        'commande_id',
        'user_id',
        'note',
        'commentaire',
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
