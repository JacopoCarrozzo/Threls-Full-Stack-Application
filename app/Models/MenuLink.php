<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuLink extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'menu_id',
        'page_id',
        'label',
        'url',
        'order',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
    
}
