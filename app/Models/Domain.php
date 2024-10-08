<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    protected $table = 'domains';

    // Указываем, какие поля можно массово заполнять
    protected $fillable = [
        'cloudflare_account_id', // Внешний ключ, связывающий с аккаунтами
        'name',    // Имя аккаунта
        'status',   // Email аккаунта Cloudflare
        'ssl_mode',
        "cloudflare_zone_id"
    ];

    public function cloudflareAccount()
    {
        return $this->belongsTo(CloudflareAccount::class);
    }

    public function pageRules()
    {
        return $this->hasMany(PageRule::class);
    }


}
