<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = [ 'name' , 'description' ];
    protected $casts   = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d'
    ];

    public function getImagePathAttribute()
    {
        return getImagePathFromDirectory($this->image, 'Services');
    }

    public function getNameAttribute()
    {
        return $this->attributes['name_' . getLocale() ];
    }

    public function getDescriptionAttribute()
    {
        return $this->attributes['description_' . getLocale() ];
    }

}
