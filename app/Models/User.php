<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'users';
    protected $primaryKey = 'id_user'; // Primary key baru
    public $incrementing = false; // Karena id_user bukan auto-increment
    protected $keyType = 'string'; // id_user adalah string

    protected $fillable = [
        'id_user',
        'nama_user',
        'level',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
