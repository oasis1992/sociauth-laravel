<?php

namespace Oasis1992\Sociauth\Controllers;
/**
 * User: gabriel_gerardo_rodriguez_diaz (oasis1992)
 */
use App\TimeSession;
use App\UserSocial;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Oasis1992\Sociauth\Contracts\Redirects\FacebookResponse;
use Oasis1992\Sociauth\Repositories\TokenSessionRepository;
use Oasis1992\Sociauth\Repositories\UserSocialRepository;
use Oasis1992\Sociauth\Support;

class AuthenticateUserController extends Controller{
    /* environment variables Views */
    const FACEBOOK_LOGIN_VIEW = "FACEBOOK_LOGIN_VIEW";
    const FACEBOOK_LOGOUT_REDIRECT_RESULT = "FACEBOOK_LOGOUT_REDIRECT_RESULT";
    const FACEBOOK_LOGIN_REDIRECT_RESULT = "FACEBOOK_LOGIN_REDIRECT_RESULT";

    
    /* Interface */
    private $facebookRespose;
    
    /* repositories */
    private $userSocialRespository;
    private $tokenSessionRepository;
    const FACEBOOK = "facebook";
    private $arrayMessages;
    private $user = null;
    private $language;
    private $provider;

    // messages
    const MESSAGE_ERROR = "Error, avise a administrador";
    const MESSAGE_DENEGATE_ERROR = "No autorizaste el acceso a Facebook";

    //messages english

    //messages spanish

    //languages
    const LANGUAGE_ES = "es";
    const LANGUAGE_EN = "en";

    /**
     * AuthenticateUser constructor.
     * @param FacebookResponse $facebookResponse
     */
    public function __construct(FacebookResponse $facebookResponse)
    {
        $this->facebookRespose = $facebookResponse;
        $this->userSocialRespository = new UserSocialRepository();
        $this->tokenSessionRepository = new TokenSessionRepository();
        $this->arrayMessages = array('message' => '');
        $this->provider = "facebook";
    }
    
    public function execute(Request $request, $provider)
    {
       return $this->accessPermissionByUser($request);
    }

    /**
     * @param $request
     * @return mixed
     */
    public function handleProviderCallback($request)
    {
        if(Auth::check() == false){
            $state = $request->get('state');
            $request->session()->put('state',$state);
            session()->regenerate();
        }
        return  Socialite::with($this->provider)->user();
    }

    public function accessPermissionByUser($request)
    {
        if($request->has('code'))
        {
            $this->user = $this->handleProviderCallback($request);
            $user_social = $this->userSocialRespository->findByUserOrCreate($this->user);
            // crear session por token
            $this->tokenSessionRepository->setSession($user_social);
            $isLogin = $this->setAuth($user_social);

            if($isLogin){
                return $this->facebookRespose->userHasLoggedIn(env(self::FACEBOOK_LOGIN_REDIRECT_RESULT), $user_social);
            }else{
                return $this->facebookRespose->firsrLogin($this->generateUrlfacebook(), env(self::FACEBOOK_LOGIN_VIEW)); // redirecccionar en caso de problema con login
            }
        }else if($request->has('error')) {
            if($request->get('error') == 'access_denied'){
                $this->setKindAndParam(array('message' => self::MESSAGE_DENEGATE_ERROR));
                return $this->facebookRespose->errorLogin($this->generateUrlfacebook(), $this->getKindAndParam(), env(self::FACEBOOK_LOGIN_VIEW));
            }else{
                $this->setKindAndParam(array('message' =>  self::MESSAGE_ERROR));
                return $this->facebookRespose->errorLogin($this->generateUrlfacebook(), $this->getKindAndParam(), env(self::FACEBOOK_LOGIN_VIEW));
            }
        }else{
            /* not send params */
            return $this->facebookRespose->firsrLogin($this->generateUrlfacebook(), env(self::FACEBOOK_LOGIN_VIEW));
        }
    }

    /**
     * @return mixed
     */
    public function generateUrlfacebook()
    {
        return Socialite::with($this->provider)->redirect()->getTargetUrl();
    }

    private function setKindAndParam($params)
    {
        if($params != null)
        {
            foreach ($params as $param)
            {
                $this->arrayMessages['message'][] = $param;
            }
        }
        return $this->arrayMessages;
    }

    public function getKindAndParam() {
        return $this->arrayMessages;
    }

    public function getUserProvider() {
        return $this->user;
    }
    public function getLanguage() {
        return $this->language;
    }
    
    public function setProvider($provider){
        $this->provider = $provider;
    }

    public function setAuth($userSocial)
    {
        Auth::login($userSocial, true);
        return Auth::check();
    }

    public function logout(Request $request){
        $user = UserSocial::where('token','=', $request->token)->first();
        $session = TimeSession::where('user_id','=', $user->id)->first();
        if($session){
            $session->delete();
        }

        return $this->facebookRespose->userLogout($this->generateUrlfacebook(), env('FACEBOOK_LOGOUT_REDIRECT_RESULT'), $this->provider);
    }
}