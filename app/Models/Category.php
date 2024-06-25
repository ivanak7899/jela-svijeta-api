<?php

namespace App\Models;

use App\Models\Meal;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    use Translatable, SoftDeletes;

    public $translatedAttributes = ['title'];

    protected $fillable = ['created_at', 'updated_at'];

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
}
