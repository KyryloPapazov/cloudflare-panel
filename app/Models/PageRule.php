<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageRule extends Model
{
    use HasFactory;

    protected $table = 'PageRule';

    protected $fillable = [
        'domain_id',
        'rule_name',
        'rule_settings',
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }


}
