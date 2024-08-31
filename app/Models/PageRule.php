<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageRule extends Model
{
    use HasFactory;

    protected $table = 'pageRule';

    protected $fillable = [
        'domain_id',
        'target_url',
        'actions',
        'status',
        'cloudflare_rule_id',
    ];

    protected $casts = [
        'actions' => 'array',  // Преобразование JSON в массив
    ];
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }


}
