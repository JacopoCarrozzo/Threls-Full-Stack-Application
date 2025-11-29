<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // Defines the One-to-Many relationship. A Menu can have many MenuLinks.
    public function links(): HasMany
    {
        return $this->hasMany(MenuLink::class)->orderBy('order');
    }
}
