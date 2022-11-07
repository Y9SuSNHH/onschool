<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Student extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'username',
        'firstname',
        'lastname',
        'gender',
        'address',
        'identification',
        'phone',
        'email',
        'password',
        'created_by',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }
}
