<?php
namespace App\Http\Middleware\API\V1;

use Illuminate\Auth\AuthenticationException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use League\OAuth2\Server\ResourceServer;

class DetectUser
{
    /**
     * The Resource Server instance.
     *
     * @var \League\OAuth2\Server\ResourceServer
     */
    private $server;

    public function __construct(
        ResourceServer              $server
    ) {
        $this->server               = $server;
    }

    public function handle($request, \Closure $next)
    {
        $psr = (new DiactorosFactory())->createRequest($request);

        try {
            $psr = $this->server->validateAuthenticatedRequest($psr);
        } catch (OAuthServerException $e) {
            throw new AuthenticationException;
        }
        $psr = $psr->getAttributes();

        $request->attributes->oauth_access_token_id = $psr['oauth_access_token_id'];
        $request->attributes->oauth_client_id       = $psr['oauth_client_id'];
        $request->attributes->oauth_user_id         = $psr['oauth_user_id'];
        $request->attributes->oauth_scopes          = $psr['oauth_scopes'];

        return $next($request);
    }
}
