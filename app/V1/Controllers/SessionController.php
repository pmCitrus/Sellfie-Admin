<?php

namespace App\V1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Centaur\AuthManager;
use Reminder;
use Sentinel;

use App\User;

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
        $remember       = (bool)$request->get('remember', false);
        
        //First Time Login Check
        $is_first_time  = User::whereNull('last_login')
                                ->where('email', $credentials['email'])
                                ->first();
        if(is_null($is_first_time))
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
            $user       = Sentinel::findUserByCredentials(['email' => $credentials['email']]);
            $reminder   = Reminder::create($user);
            $code       = $reminder->code;
            return redirect('password/reset/'.$code);
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