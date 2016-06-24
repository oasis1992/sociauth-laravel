<?php
/**
 * Created by PhpStorm.
 * User: gabriel_gerardo_rodriguez_diaz (oasis1992)
 * Date: 17/06/2016
 * Time: 5:27
 */

Route::group(['middleware' => ['web'], 'namespace' => 'Oasis1992\Sociauth\Controllers', 'prefix' => 'login'], function () {
    Route::get('/{provider}', [
        'as'   => 'login_provider',
        'uses' => 'AuthenticateUserController@execute'
    ]);

    Route::get('/logout', [
        'as'   => 'logout_provider',
        'uses' => 'AuthenticateUserController@logout'
    ]);
});


