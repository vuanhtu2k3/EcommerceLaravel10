<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'provider',
        'provider_user_id',
        'user_id',
    ];

    protected $primaryKey = 'id';
    protected $table = 'login_social';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}