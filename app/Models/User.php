<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Panel;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function locations()
    {
        return $this->belongsToMany(Location::class);
    }

    public function radius()
    {
        return $this->hasMany(LocationRadius::class);
    }

    public function locationsWithinRadii($createdAfter = null, $createdBefore = null)
    {
        $locations = collect();
        $this->radius()->each(function ($radius) use ($locations, $createdAfter, $createdBefore) {
            $locations->add($radius->locations($createdAfter, $createdBefore));
        });

        return $locations->flatten();
    }

    public function toggleFavorite(Location $location)
    {
        if ($this->locations->contains($location)) {
            $this->locations()->detach($location);
        } else {
            $this->locations()->attach($location);
        }
    }

    public function scopeNotifyAboutNewLocations($query)
    {
        return $query->where('email', '!=', null)
            ->where('notify_locations', true);
    }

    public function getIsAdminAttribute(): bool
    {
        return in_array($this->email, explode(',', config('nemt.admin_emails')));
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin;
    }
}
