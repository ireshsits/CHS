<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Repositories\Auth\AuthInterface;

class UserComposer
{
    protected $auth;
    /**
     * Create a movie composer.
     *
     * @return void
     */
    public function __construct(AuthInterface $interface)
    {
        $this->auth = $interface;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('authUser', $this->auth->authUser());
    }
}