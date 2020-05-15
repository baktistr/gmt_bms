<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Nova\Actions\Actionable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use Notifiable, Actionable, SoftDeletes, InteractsWithMedia, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Check if Super Admin Role
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('Super Admin');
    }

    /**
     * check if is Manager Role
     * @return bool
     */
    public function isManager()
    {
        return $this->hasRole('Building Manager');
    }

    /**
     * check if is Viewer Role
     * @return bool
     */
    public function isViewer()
    {
        return $this->hasRole('Viewer');
    }

    /**
     * check if is Viewer Role
     * @return bool
     */
    public function isHelpDesk()
    {
        return $this->hasRole('Help Desk');
    }

    /**
     * A user manager can be assigned to 1 building.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function building(): HasOne
    {
        return $this->hasOne(Building::class, 'manager_id');
    }

    /**
     * A user (viewer/help-desks) can be assigned to building.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignedBuilding(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    /**
     * Determine which user can impersonate other user.
     *
     * @return bool
     */
    public function canImpersonate()
    {
        return $this->hasRole('Super Admin');
    }

    /**
     * Register the media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->registerMediaConversions(function () {
                $this->addMediaConversion('tiny')
                    ->fit(Manipulations::FIT_CROP, 75, 75)
                    ->performOnCollections('avatar')
                    ->nonQueued();

                $this->addMediaConversion('small')
                    ->fit(Manipulations::FIT_CROP, 150, 150)
                    ->performOnCollections('avatar')
                    ->nonQueued();

                $this->addMediaConversion('medium')
                    ->fit(Manipulations::FIT_CROP, 300, 300)
                    ->performOnCollections('avatar')
                    ->nonQueued();

                $this->addMediaConversion('large')
                    ->fit(Manipulations::FIT_CROP, 600, 600)
                    ->performOnCollections('avatar')
                    ->nonQueued();
            });
    }
}
