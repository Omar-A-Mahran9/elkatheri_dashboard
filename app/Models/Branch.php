<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $appends = [ 'name','address' ];
    protected $guarded = ['lat','lng'];
    protected $table = 'branches';
    protected $casts  = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
        "start_time" => "datetime:H:i",
        "end_time" => "datetime:H:i",
    ];

    public function getNameAttribute()
    {
        return $this->attributes['name_' . getLocale()];
    }

    public function getTimeOfWorkAttribute()
    {
        return $this->attributes['time_of_work_' . getLocale()];
    }

    public function getAddressAttribute()
    {
        return $this->attributes['address_' . getLocale()];
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class,'branch_id');
    }


public function unavailableDatesBetween($startDate, $endDate)
{
    $availableDays = $this->schedule()
        ->where('is_available', true)
        ->pluck('day_of_week')
        ->map(function ($day) {
            return ucfirst(strtolower($day)); // Ensure consistent casing like "Thursday"
        })
        ->toArray();

    $dates = [];
    $date = Carbon::parse($startDate);
    $end = Carbon::parse($endDate);

    while ($date->lte($end)) {
        if (in_array($date->format('l'), $availableDays)) {
            $dates[] = $date->format('Y-m-d');
        }
        $date->addDay();
    }

    return $dates;
}


}
