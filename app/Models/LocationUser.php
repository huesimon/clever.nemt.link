<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class LocationUser extends Pivot
{
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
