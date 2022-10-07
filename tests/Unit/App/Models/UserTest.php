<?php

namespace Tests\Unit\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserTest extends ModelTestCase
{
    public function model(): Model
    {
        return new User();
    }

    public function traits(): array
    {
        return [
            HasApiTokens::class,
            HasFactory::class,
            Notifiable::class
        ];
    }

    public function fillable(): array
    {
        return [
            'name',
            'email',
            'password'
        ];
    }

    public function casts(): array
    {
        return [
            'id' => 'string',
            'email_verified_at' => 'datetime',
        ];
    }
}
