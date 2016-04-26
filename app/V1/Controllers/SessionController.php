<?php

namespace App\V1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Centaur\AuthManager;
use Reminder;
use Sentinel;

use App\User;
use Log;
use DB;

class SessionController extends Controller
{
    /** @var Centaur\AuthManager */
    protected $authManager;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(AuthManager $authManager)
    {
        $this->middleware('sentinel.guest', ['except' => 'getLogout']);
        $this->authManager  = $authManager;
    }

    /**
     * Show the Login Form
     * @return View
     */
    public function getLogin()
    {
        return view('v1.auth.login');
    }

    /**
     * Handle a Login Request
     * @return Response|Redirect
     */
    public function postLogin(Request $request)
    {
        // Validate the Form Data
        $result         = $this->validate(
                                    $request,
                                    [
                                    'email'     => 'required',
                                    'password'  => 'required'
                                    ]
                                );
        // Get Login Credentials
        $credentials    = [
                            'email'     => trim($request->get('email')),
                            'password'  => $request->get('password'),
                            ];
        
        $user           = Sentinel::findUserByCredentials(['email' => $credentials['email']]);
        $user_arr       = json_decode($user, true);
        if($user_arr['deleted_at'] == '')
        {
            $remember       = (bool)$request->get('remember', false);
            if($user_arr['last_login'] != '')
            {
                $result         = $this->authManager->authenticate($credentials, $remember);
                if($result->isSuccessful())
                {
                   $path        = session()->pull('url.intended', route('dashboard')); 
                }
                else
                {
                    $path       = session()->pull('url.intended', route('auth.login.form'));
                }
                return $result->dispatch($path);
            }
            else
            {
                $reminder   = Reminder::create($user);
                $code       = $reminder->code;
                return redirect('password/reset/'.$code);
            }
        }
        else
        {
            $data['title']          = 'Login';
            $data['panel_title']    = 'Login Denied';
            $data['error_message']  = 'User account disabled, Contact Us on support@sellfie.me for more details';
            $data['url']            = url('login');
            $data['url_title']      = 'Login as different user';
            return view('v1.auth.layout-error', $data);
        }
    }

    /**
     * Handle a Logout Request
     * @return Response|Redirect
     */
    public function getLogout(Request $request)
    {
        // Terminate the user's current session.  Passing true as the
        // second parameter kills all of the user's active sessions.
        $result = $this->authManager->logout(null, null);
        
        // Return the appropriate response
        return $result->dispatch(route('auth.login.form'));
    }
}