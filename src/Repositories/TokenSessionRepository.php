<?php
/**
 * User: gabriel_rodriguez_diaz
 * Date: 18/07/16
 * Time: 1:29 PM
 */

namespace Oasis1992\Sociauth\Repositories;


use App\TimeSession;

class TokenSessionRepository
{
    public function setSession($userSocial){

        $timeSession = TimeSession::where('user_id',$userSocial->id)->first();

        if($timeSession){
            $timeSession->token_init = \time();
            $timeSession->token_end = env('FACEBOOK_DURATION_SESSION');
            $timeSession->save();
        }else{
            $timeSession = new TimeSession();
            $timeSession->token_init = \time();
            $timeSession->token_end = env('FACEBOOK_DURATION_SESSION');
            $timeSession->user_id = $userSocial->id;
            $timeSession->save();
        }
    }
}