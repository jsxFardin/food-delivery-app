<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nid',
        'address',
    ];

    public function riderLocations()
    {
        return $this->hasMany(RiderLocation::class);
    }
}
