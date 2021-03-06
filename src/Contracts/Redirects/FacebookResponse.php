<?php
namespace Oasis1992\Sociauth\Contracts\Redirects;
/**
 * User: gabriel_gerardo_rodriguez_diaz (oasis1992)
 * Date: 17/06/2016
 * Time: 11:08
 */
interface FacebookResponse{
    public function firsrLogin($url, $view);

    public function denegateByUser($url, $params, $view);

    public function errorLogin($url, $params, $view);

    public function userHasLoggedIn($route, $user);

    public function userLogout($url, $route, $provider);
}