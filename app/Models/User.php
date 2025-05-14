<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = "users";

    protected $fillable = [
        'name',
        'matricnum',
        'password',
        'role',
        'phone',
        'bio',
        'avatar',
        'siswa_email'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Override the default username field.
     */
    public function username()
    {
        return 'matricnum';
    }

    public function products()
{
    return $this->hasMany(Products::class, 'matricnum' , 'matricnum');
}

public function getEmailForPasswordReset()
{
    return $this->siswa_email;
}


}
