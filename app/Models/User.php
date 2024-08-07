<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        'person_type',
        'person_id',
        'birthday',
        'address',
        'tel',
        'gender',
        'photo',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Hash::make($value)
        );
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName("user")->logOnly([
            "name",
            "email",
            "roles"
        ]);
    }

    public function person()
    {
        return $this->morphTo();
    }

    public function birthday(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format("Y.m.d")
        );
    }

    public function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format("Y.m.d H:m:s"),
        );
    }

    public function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format("Y.m.d H:m:s"),
        );
    }

    public function deletedAt(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value) {
                    Carbon::parse($value)->format("Y.m.d H:m:s");
                } else {
                    return "Not deleted";
                }
            },
        );
    }
}
