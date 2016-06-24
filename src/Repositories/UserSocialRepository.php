<?php
namespace Oasis1992\Sociauth\Repositories;
use App\UserSocial;


/**
 * Created by PhpStorm.
 * User: gabriel_gerardo_rodriguez_diaz (oasis1992)
 * Date: 17/06/2016
 * Time: 11:48
 */
class UserSocialRepository
{
    public function findByUserOrCreate($userData)
    {
        $user = UserSocial::where('email', '=', $userData->email)->first();
        if($user)
        {
            return $user;
        }else{
            return UserSocial::firstOrCreate([
                'name' => $userData->name,
                'email' => $userData->email,
                'avatar_original' => $userData->avatar_original,
                'avatar' => $userData->avatar,
                'gender' => $userData->user['gender'],
                'id_facebook' => $userData->user['id'],
            ]);
        }
    }
}