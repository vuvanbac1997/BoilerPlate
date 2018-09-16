<?php

namespace App\Models;

/**
 * App\Models\UserNotification.
 *
 * @property int                   $id
 * @property int                   $user_id
 * @property string                $category_type
 * @property string                $type
 * @property string                $data
 * @property string                $content
 * @property string                $locale
 * @property bool                  $read
 * @property \Carbon\Carbon        $sent_at
 * @property \Carbon\Carbon        $created_at
 * @property \Carbon\Carbon        $updated_at
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereCategoryType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereData($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereLocale($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereRead($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereSentAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserNotification whereUpdatedAt($value)
 * @mixin \Eloquent
 *
 * @property-read \App\Models\User $user
 */
class UserNotification extends Notification
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'category_type',
        'type',
        'data',
        'content',
        'locale',
        'read',
        'sent_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates = ['sent_at'];

    protected $presenter = \App\Presenters\UserNotificationPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\UserNotificationObserver);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id'            => $this->id,
            'user_id'       => $this->user_id,
            'category_type' => $this->category_type,
            'type'          => $this->type,
            'data'          => $this->data,
            'content'       => $this->content,
            'locale'        => $this->locale,
            'read'          => $this->read,
            'sent_at'       => $this->sent_at,
        ];
    }
}
