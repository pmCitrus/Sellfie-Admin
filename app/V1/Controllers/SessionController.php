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
        
        $remember       = (bool)$request->get('remember', false);
        $result         = $this->authManager->authenticate($credentials, $remember);
        session()->put('errors', '');
        if(($user_arr['deleted_at'] != '') || ($user_arr['block_status'] != 'n'))
        {
            session()->put('errors', 'User account disabled, Contact Us on support@sellfie.me for more details.');
            $path       = session()->pull('url.intended', route('auth.login.form'));
        }
        else if($user_arr['last_login'] != '')
        {
            if($result->isSuccessful())
            {
               $path        = session()->pull('url.intended', route('dashboard')); 
            }
            else
            {
                session()->put('errors', 'Incorrect email / password.');
                $path           = session()->pull('url.intended', route('auth.login.form'));
            }
        }
        else
        {
            $reminder   = Reminder::create($user);
            $code       = $reminder->code;
            return redirect('password/reset/'.$code);
        }

        return $result->dispatch($path);
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