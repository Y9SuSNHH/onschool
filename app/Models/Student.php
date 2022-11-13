<?php

namespace App\Models;

use App\Http\Controllers\ResponseTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory, Notifiable, SoftDeletes, ResponseTrait;

    protected $table = 'students';

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

    public function created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function updated_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }

    public function deleted_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by')->withTrashed();
    }

    public function list($filter = []): Builder
    {
        $query = DB::table($this->table)
            ->where("$this->table.deleted_at", '=', null)
            ->join('users', "$this->table.created_by", '=', 'users.id')
            ->select("$this->table.*", 'users.id', 'users.username')
            ->orderByDesc("$this->table.id");
        if (!empty($filter)) {
            $query->where($filter);
        }
        return $query;
    }

    public function validateRequired($request)
    {
        foreach ($request as $key => $value) {
            if ($value === '') {
                return $key;
            }
        }
        return true;
    }

    public function checkFind($id)
    {
        $user = self::query()->find($id);
        if (!$user) {
            return $this->errorResponse('This student does not exist');
        }
        return $user;
    }
}
