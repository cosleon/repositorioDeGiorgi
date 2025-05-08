<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use HasFactory;

class Card extends Model
{
    

    protected $fillable = [
        'name',       // Nombre de la carta
        'expansion_id',  // Expansión
        'image_url',  // URL de la imagen
        'rarity'
    ];

    public function users()
{
    return $this->belongsToMany(User::class, 'user_cards')
                ->withPivot('quantity');
}
    public function expansion()
    {
        return $this->belongsTo(Expansion::class);
    }
}
