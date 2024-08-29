<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloudflareAccount extends Model
{
    use HasFactory;

    // Указываем, с какой таблицей связана эта модель
    protected $table = 'cloudflare_accounts';

    // Указываем, какие поля можно массово заполнять
    protected $fillable = [
        'user_id', // Внешний ключ, связывающий с пользователем
        'name',    // Имя аккаунта
        'email',   // Email аккаунта Cloudflare
        'api_key', // API ключ аккаунта Cloudflare
    ];

    /**
     * Связь с моделью User
     * Каждый аккаунт Cloudflare принадлежит одному пользователю
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Связь с моделью Domain
     * Один аккаунт Cloudflare может иметь несколько доменов
     */
    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

}
