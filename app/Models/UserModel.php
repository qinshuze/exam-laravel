<?php


namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;


/**
 * App\Models\UserModel
 *
 * @property int $id
 * @property string $nickname
 * @property string|null $avatar
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserModel permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserModel role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserModel whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserModel whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserModel whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserModel whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserModel wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserModel whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserModel extends Authenticatable implements JWTSubject
{
    use Notifiable, HasRoles;

    protected $table = 'users';
    protected $guard_name = 'api';
    protected $fillable = [
        'nickname',
        'avatar',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}