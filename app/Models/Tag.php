<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;
    use Translatable, SoftDeletes;

    public $translatedAttributes = ['title'];

    protected $fillable = ['created_at', 'updated_at'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(Meal::class);
    }
}
