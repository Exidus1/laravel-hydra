<?php

namespace Exidus\Hydra\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout()
    {
        $this->beforeLogout();

        Auth::user()->token()->revoke();

        $this->afterLogout();

        return ['success' => true];
    }

    public function beforeLogout()
    {

    }

    public function afterLogout()
    {

    }
}
