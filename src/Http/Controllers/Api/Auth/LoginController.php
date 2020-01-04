<?php

namespace Exidus\Hydra\Http\Controllers\Api\Auth;

use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;

class LoginController extends AccessTokenController
{
    public function login(ServerRequestInterface $request)
    {
        $this->beforeLogin();

        $body = $request->getParsedBody();
        if (is_array($body) && !empty($body['username'])) {
            $user = (config('hydra.models.user'))::where(config('hydra.passport.extras.field'),
                $body[config('hydra.passport.extras.input')])->first();
            if ($user && $user->banned) {
                abort(403, $user->banned);
            }
        }

        $this->afterLogin();

        return $this->issueToken($request)->withUserExtras($request);
    }

    public function beforeLogin()
    {

    }

    public function afterLogin()
    {

    }

}
