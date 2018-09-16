<?php
namespace App\Models;

class OauthRefreshToken extends Base
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oauth_refresh_tokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'access_token_id',
        'revoked',
        'expires_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = ['expires_at'];

//    protected $presenter = \App\Presenters\OauthRefreshTokenPresenter::class;

    // Relations
        public function accessToken()
        {
//            return $this->belongsTo(\App\Models\AccessToken::class, 'access_token_id', 'id');
        }

    // Utility Functions
}
