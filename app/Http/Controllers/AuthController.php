<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Helpers\CMail;

class AuthController extends Controller
{
    /**
     * Login Form
     */
    public function loginForm()
    {
        $data = [
            'pageTitle' => 'Admin Login'
        ];
        return view('back.pages.auth.login', $data);
    }

    /**
     * Login Handler
     */
    public function loginHandler(Request $request)
    {
        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if ($fieldType == 'email') {
            $request->validate([
                'login_id' => 'required|email|exists:users,email',
                'password' => 'required|min:5',
            ], [
                'login_id.required' => 'Email or Username is required',
                'login_id.email' => 'Email is invalid',
                'login_id.exists' => 'Email does not exist',
                'password.required' => 'Password is required',
                'password.min' => 'Password must be at least 5 characters long',
            ]);
        } else {
            $request->validate([
                'login_id' => 'required|exists:users,username',
                'password' => 'required|min:5',
            ], [
                'login_id.required' => 'Email or Username is required',
                'login_id.username' => 'Username is invalid',
                'login_id.exists' => 'Username does not exist',
                'password.required' => 'Password is required',
                'password.min' => 'Password must be at least 5 characters long',
            ]);
        }

        $creds = array(
            $fieldType => $request->login_id,
            'password' => $request->password
        );

        if (Auth::attempt($creds)) {
            // Check if account is inactive mode
            if (auth()->user()->status == UserStatus::INACTIVE) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.login')->with('fail', 'Your account is inactive');
            }

            // Check if account is in pending mode
            if (auth()->user()->status == UserStatus::PENDING) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.login')->with('fail', 'Your account is pending');
            }

            // Check if account is admin
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('admin.login')->withInput()->with('fail', 'Invalid credentials');
        }
    }

    /**
     * Forget Password Form
     */
    public function forgetForm(Request $request)
    {
        $data = [
            'pageTitle' => 'Forget Password'
        ];
        return view('back.pages.auth.forget', $data);
    }

    /**
     * Send Password Reset Link
     */
    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            'email.exists' => 'Email does not exist',
        ]);

        //GEt User Details
        $user = User::where('email', $request->email)->first();

        //Generate Token
        $token = base64_encode(Str::random(64));

        //Check if there is an existing token
        $oldToken = DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->first();

        if ($oldToken) {
            //Update Old Token
            DB::table('password_reset_tokens')
                ->where('email', $user->email)
                ->update([
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]);
        } else {
            //Insert New Token
            DB::table('password_reset_tokens')
                ->insert([
                    'email' => $user->email,
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]);
        }

        //Create clickable action link
        $actionLink = route('admin.reset_password_form', $token);

        $data = array(
            'actionLink' => $actionLink,
            'user' => $user,
        );

        $mail_body = view('email_templates.forget_template', $data)->render();

        $mailConfig = array(
            'recipient_email' => $user->email,
            'recipient_name' => $user->name,
            'subject' => 'Password Reset',
            'body' => $mail_body,
        );

        if (CMail::sendMail($mailConfig)) {
            return redirect()->route('admin.forget')->with('success', 'We have e-mailed your password reset link');
        } else {
            return redirect()->route('admin.forget')->with('fail', 'Something went wrong, please try again later');
        }
    }

    /**
     * Reset Password Form
     */
    public function resetPasswordForm(Request $request, $token = null)
    {
        //Check if token is exists
        $isTokenExists = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->first();

        if (!$isTokenExists) {
            return redirect()->route('admin.forget')->with('fail', 'Invalid or expired token');
        } else {
            //Check if token is not expired
            $diffMins = Carbon::createFromFormat('Y-m-d H:i:s', $isTokenExists->created_at)->diffInMinutes(Carbon::now());

            if ($diffMins > 15) {
                return redirect()->route('admin.forget')->with('fail', 'The Password reset link you clicked has expired. Please request a new link.');
            }

            $data = [
                'token' => $token,
                'pageTitle' => 'Reset Password',
            ];

            return view('back.pages.auth.reset_password', $data);
        }
    }

    /**
     * Reset Password Handler
     */
    public function resetPasswordHandler(Request $request, $token = null)
    {
        //validate the form   
        $request->validate([
            'new_password' => 'required|min:5|required_with:new_password_confirmation|same:new_password_confirmation',
            'new_password_confirmation' => 'required',
        ]);

        //Check if token is exists
        $isTokenExists = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->first();

        //Get User details
        $user = User::where('email', $isTokenExists->email)->first();

        //Update user password
        User::where('email', $user->email)
            ->update([
                'password' => Hash::make($request->new_password),
            ]);

        //Send notification email to this user email address that containes new password
        $data = array(
            'user' => $user,
            'new_password' => $request->new_password,
        );

        $mail_body = view('email_templates.password_changes_template', $data)->render();

        $mailConfig = array(
            'recipient_email' => $user->email,
            'recipient_name' => $user->name,
            'subject' => 'Password Changes',
            'body' => $mail_body,
        );

        if (CMail::sendMail($mailConfig)) {
            //delete token from db
            DB::table('password_reset_tokens')
                ->where('email', $isTokenExists->email)
                ->where('token', $isTokenExists->token)
                ->delete();

            return redirect()->route('admin.login')->with('success', 'Password reset successfully, you can login now');
        } else {
            return redirect()->route('admin.reset_password_form', ['token' => $isTokenExists->token])->with('fail', 'Something went wrong, please try again later');
        }
    }
}
