<?php namespace App\Models;


class AdminUserNotification extends Notification {


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_user_notifications';

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

    protected $presenter = \App\Presenters\AdminUserNotificationPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\AdminUserNotificationObserver);
    }

    // Relations
    public function adminUser() {
        return $this->belongsTo( \App\Models\AdminUser::class, 'user_id', 'id' );
    }



    // Utility Functions

    /*
     * API Presentation
     */
    public function toAPIArray() {
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
