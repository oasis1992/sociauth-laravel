<?php
namespace Oasis1992\Sociauth\Controllers;

use Oasis1992\Sociauth\Contracts\Redirects\FacebookResponse;

/**
 * User: gabriel_gerardo_rodriguez_diaz (oasis1992)
 * Date: 17/06/2016
 * Time: 11:20
 */
class ViewResponseController implements FacebookResponse
{
    private $param = "url_provider";
    /**
     * @param $url|string
     * @param $view|string
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function firsrLogin($url, $view)
    {
        return View($view)->with($this->param, $url);
    }

    /**
     * @param $url|string
     * @param $params|array
     * @param $view|string
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function denegateByUser($url, $params, $view)
    {
        return View($view)->with($this->param, $url);
    }

    /**
     * @param $url|string
     * @param $params|array
     * @param $view|string
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function errorLogin($url, $params, $view)
    {
        return View($view)->with($this->param, $url);
    }

    /**
     * @param $view|string
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userHasLoggedIn($route, $user_social)
    {
        return redirect()->route($route, ['token' => $user_social->token]);
    }

    public function userLogout($url, $route, $provider){
        return redirect()->route($route, ['provider' => $provider])->with($this->param, $url);
    }
}