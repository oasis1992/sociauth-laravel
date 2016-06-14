<?php

namespace Oasis1992\Sociauth;

use Illuminate\Support\Facades\Auth;

class AuthenticateUser{
    private $array;
    private $mUser = null;
    private $language;
    private $provider;
    const USER_HAS_LOGGED_IN = "user_has_logged_in";
    const ERROR_LOGIN = "error_login";
    const FIRST_LOGIN = "first_login";
    const DENEGATE_BY_USER = "denegate_by_user";

    // messages
    const MESSAGE_ERROR = "Error, avise a administrador";
    const MESSAGE_DENEGATE_ERROR = "No autorizaste el acceso a Facebook";

    //messages english

    //messages spanish

    //languages
    const LANGUAGE_ES = "es";
    const LANGUAGE_EN = "en";

    public function __construct($provider, $language = null)
    {
        if($language != null){
            $this->language = $language;
        }else{
            $this->language = self::LANGUAGE_EN;
        }
        $this->provider = $provider;
        $this->array = array('kind' => '', 'url_provider' => '', 'message' => '');
    }


    public function execute($request)
    {
        $this->accessPermissionByUser($request);
        $arrayKindAndParams =  $this->getKindAndParam();

        return $arrayKindAndParams;
    }

    public function handleProviderCallback($request)
    {
        $state = $request->get('state');
        $request->session()->put('state',$state);
        if(Auth::check() == false){
            session()->regenerate();
        }

        return  \Socialite::with($this->provider)->user();
    }

    public function accessPermissionByUser($request)
    {
        // TODO checar si la sesion sigue activa, redireccionara admision, devolver KIND [USER_HAS_LOGGED_IN]

        if($request->has('code'))
        {
            $this->mUser = $this->handleProviderCallback($request);
            $this->setKindAndParam(self::USER_HAS_LOGGED_IN /* kind */, null /* not params */);

        }else if($request->has('error')) {
            if($request->get('error') == 'access_denied'){
                $this->setKindAndParam(self::DENEGATE_BY_USER /* kind */,array('url_provider'=> $this->generateUrlfacebook(),
                    'message' => self::MESSAGE_DENEGATE_ERROR));
            }else{
                $this->setKindAndParam(self::ERROR_LOGIN /* kind */, array('url_provider'=> $this->generateUrlfacebook(),
                    'message' =>  self::MESSAGE_ERROR));
            }
        }else{
            $this->setKindAndParam(self::FIRST_LOGIN, array('url_provider' => $this->generateUrlfacebook()));
        }
    }

    /**
     * @return mixed
     */
    public function generateUrlfacebook()
    {
        return \Socialite::with($this->provider)->redirect()->getTargetUrl();
    }

    private function setKindAndParam($kind, $params)
    {
        $this->array['kind'] = $kind;
        if($params != null)
        {
            foreach ($params as $param)
            {
                if(key($params) == 'url_provider'){
                    $this->array['url_provider'] = $param;
                }else{
                    $this->array['message'] = $param;
                }
            }
        }

        return $this->array;
    }

    public function getKindAndParam() {
        return $this->array;
    }

    public function getUserProvider() {
        return $this->mUser;
    }
    public function getLanguage() {
        return $this->language;
    }

    public function setAuth($user)
    {
        Auth::login($user, true);
    }
}