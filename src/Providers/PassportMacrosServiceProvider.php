<?php

namespace Exidus\Hydra\Providers;

use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Message\ServerRequestInterface;

class PassportMacrosServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('withUserExtras', function (ServerRequestInterface $request, $extras = null) {
            if ($this && $this->getStatusCode() === 200) {

                $user = $request->getParsedBody();
                if ($user && !empty($user[config('hydra.passport.extras.input')])) {
                    $user = (config('hydra.models.user'))::where(config('hydra.passport.extras.field'),
                        $user[config('hydra.passport.extras.input')])->first();

                    if ($user) {
                        if (!$extras) {
                            $extras = config('hydra.passport.extras.default');
                        }
                        dump($extras);

                        $content = $this->getContent();
                        $content = json_decode($content);

                        $data = [];
                        foreach ($extras as $index => $field) {
                            $i = is_numeric($index) ? $field : $index;

                            if (strpos($field, '()') === strlen($field) - 2) {
                                $data[$i] = $user->{substr($field, 0, strlen($field) - 2)}();
                            } else {
                                $data[$i] = $user->{$field};
                            }
                        }
                        $content->extras = $data;
                        $this->setContent(json_encode($content));
                    }
                }
            }

            return $this;
        });
    }
}
