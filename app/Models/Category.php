<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    protected $fillable = ['name','slug','parent_id','description','emoji'];

    // Une catégorie peut avoir plusieurs sous-catégories
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Une sous-catégorie a un parent
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Une catégorie peut avoir plusieurs sites
    public function sites(): HasMany
    {
        return $this->hasMany(Site::class);
    }
}
