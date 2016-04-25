<?php

namespace App\V1\Controllers;

use DB;
use Mail;
use Session;
use Reminder;
use Sentinel;
use App\Http\Requests;
use Centaur\AuthManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PasswordController extends Controller
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
        $this->middleware('sentinel.guest');
        $this->authManager = $authManager;
    }

    /**
     * Show the password reset form if the reset code is valid
     * @param  Request $request
     * @param  string  $code
     * @return View
     */
    public function getReset(Request $request, $code)
    {
        // Is this a valid code?
        if (!$this->validatePasswordResetCode($code)) {
            // This route will not be accessed via ajax;
            // no need for a json response
            Session::flash('error', 'Invalid or expired password reset code; please request a new link.');
            return redirect()->route('dashboard');
        }

        return view('v1.auth.reset-password')
            ->with('code', $code);
    }

    /**
     * Process a password reset form submission
     * @param  Request $request
     * @param  string  $code
     * @return Response|Redirect
     */
    public function postReset(Request $request, $code)
    {
        // Validate the form data
        $result = $this->validate($request, [
            'password' => 'required|confirmed|min:6|max:8',
        ]);

        // Attempt the password reset
        $result         = $this->authManager->resetPassword($code, $request->get('password'));
        
        if ($result->isFailure()) {
            return $result->dispatch();
        }
        else {
            $user_data  = DB::table('reminders')
                            ->where('code', $code)
                            ->select(['user_id'])
                            ->first();
            
            $user       = DB::table('users')
                            ->where('users_id', $user_data->user_id)
                            ->select(['email'])
                            ->first();
            $credentials    = [
                            'email'     => $user->email,
                            'password'  => $request->get('password'),
                            ];
            $remember       = (bool) false;
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
    }

    /**
     * @param  string $code
     * @return boolean
     */
    protected function validatePasswordResetCode($code)
    {
        return DB::table('reminders')
                ->where('code', $code)
                ->where('completed', false)->count() > 0;
    }
}
