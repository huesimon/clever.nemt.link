<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LocationHistory extends Model
{
    use HasFactory;
    use Prunable;

    /*
    * TODO: Better name for this attribute
    */
    public function getCreatedAtEuAttribute()
    {
         return $this->created_at->timezone('Europe/Copenhagen');
    }

    public function prunable(): Builder
    {
        return static::where('created_at', '<=', now()->subWeek());
    }
}
