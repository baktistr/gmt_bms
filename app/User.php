<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Nova\Actions\Actionable;

class User extends Authenticatable
{
    use Notifiable, Actionable, SoftDeletes;

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
        'is_super_admin' => 'boolean',
        'is_admin' => 'boolean',
        'is_manager' => 'boolean',
        'is_viewer' => 'boolean',
    ];

    /**
     * {@inheritDoc}
     */
    protected $attributes = [
        'is_super_admin' => false,
        'is_admin'       => false
    ];


    /**
     * Check if Super Admin Role
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->is_super_admin;
    }
    /**
     * Check if admin Role
     * @return bool
     */
    public function isAdmin()
    {
        return $this->is_admin;
    }

    /**
     * check if is Manager Role
     * @return bool
     */
    public function isManager()
    {
        return $this->is_manager;
    }
    /**
     * check if is Viewer Role
     * @return bool
     */
    public function isViewer()
    {
        return $this->is_viewer;
    }

    /**
     * Scope the query get role of user
     * @param $query , $role
     * @return bool
     */
    public function scopeRole($query, $role)
    {
        return $query->where($role, true)->get();
    }
}
