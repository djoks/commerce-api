<?php

namespace App\Models;

use App\Traits\Broadcastable;
use App\Traits\HasQueryHelper;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, HasFactory, Notifiable, LogsActivity, HasQueryHelper, Broadcastable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phone',
        'email',
        'password',
        'has_set_password',
        'is_active',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'has_set_password' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            self::notifyUserCreated($model);
        });

        static::saved(function ($model) {
            if (request()->hasFile('avatar')) {
                $model->clearMediaCollection('avatars');
                $model->addMediaFromRequest('avatar')->toMediaCollection('avatars');
            }
        });
    }

    public function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    public function billings()
    {
        return $this->hasMany(Billing::class, 'customer_id');
    }
}
