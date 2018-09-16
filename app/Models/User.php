<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * App\Models\User.
 *
 * @property int                    $id
 * @property string                 $name
 * @property string                 $email
 * @property string                 $password
 * @property string                 $locale
 * @property int                    $last_notification_id
 * @property string                 $api_access_token
 * @property int                    $profile_image_id
 * @property \Carbon\Carbon         $deleted_at
 * @property string                 $remember_token
 * @property \Carbon\Carbon         $created_at
 * @property \Carbon\Carbon         $updated_at
 * @property-read \App\Models\Image $profileImage
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereLocale($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereLastNotificationId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereApiAccessToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereProfileImageId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends AuthenticatableBase
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $presenter = \App\Presenters\UserPresenter::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'telephone',
        'birthday',
        'locale',
        'address',
        'remember_token',
        'api_access_token',
        'profile_image_id',
        'last_notification_id',
        'is_activated'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'facebook_token'];

    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\UserObserver);
    }

    /**
     * Find the user identified by the given $identifier.
     *
     * @param $identifier email|phone
     * @return mixed
     */
    public function findForPassport($identifier)
    {
        return $this->where('email', $identifier)->first();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function profileImage()
    {
        return $this->belongsTo(\App\Models\Image::class, 'profile_image_id', 'id');
    }

    /*
     * API Presentation
     */

    public function toAPIArray()
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'email'     => $this->email,
            'token'     => $this->api_access_token,
            'gender'    => $this->gender ? "Male" : "Female",
            'telephone' => $this->telephone,
            'birthday'  => $this->birthday,
            'locale'    => $this->locale,
            'address'   => $this->address,
            'avatar'    => !empty($this->present()->profileImage()) ? $this->present()->profileImage()->url : \URLHelper::asset('img/user_avatar.png', 'common'),
        ];
    }
}
