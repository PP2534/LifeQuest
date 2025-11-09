<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'icon', 'description'
    ];

    /**
     * The challenges that belong to the category.
     */
    public function challenges()
    {
        return $this->belongsToMany(Challenge::class, 'challenge_categories');
    }
}