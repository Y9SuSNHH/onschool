<?php

namespace App\Models;

use App\Enums\UserActiveEnum;
use App\Enums\UserRoleEnum;
use App\Http\Controllers\ResponseTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes, ResponseTrait;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'firstname',
        'lastname',
        'gender',
        'phone',
        'email',
        'password',
        'active',
        'role',
        'created_by',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function list($filter = []): Builder
    {
        $query = DB::table($this->table)
            ->where('deleted_at', '=', null)
            ->orderByDesc('id');
        if (!empty($filter)) {
            $query->where($filter);
        }
        return $query;
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function handleFilter($request): array
    {
        $filter = [];
        if (auth()->user()->role === UserRoleEnum::USER) {
            $filter[] = ['role', '=', UserRoleEnum::USER];
        }

        if (!empty($request->get('username'))) {
            $username = $request->get('username');
            $filter[] = ['username', '=', $username];
        }
        if (!is_null($request->get('active')) && $request->get('active') !== 'All') {
            $active           = (int)$request->get('active');
            $checkActiveValue = UserActiveEnum::hasValue($active);
            if ($checkActiveValue) {
                $filter[] = ['active', '=', $active];
            }
        }

        return $filter;
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
}
