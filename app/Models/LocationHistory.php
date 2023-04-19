<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationHistory extends Model
{
    use HasFactory;

    /*
    * TODO: Better name for this attribute
    */
    public function getCreatedAtEuAttribute()
    {
         return $this->created_at->timezone('Europe/Copenhagen');
    }
}
