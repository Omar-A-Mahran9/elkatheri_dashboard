<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = [ 'name' ];
    protected $casts   = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d'
    ];

    public function getNameAttribute()
    {
        return $this->attributes['name_' . getLocale() ];
    }

    public function getImagePathAttribute()
    {
        return getImagePathFromDirectory($this->image, 'Brands');
    }

    public function parentModels() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CarModel::class)->whereNull('parent_model_id');
    }

    public function cars() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Car::class);
    }

    public function oldCars() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Car::class)->where("is_new" , 0);
    }

    public function newCars() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Car::class)->where("is_new" , 1);
    }
}
