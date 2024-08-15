<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Mail\SendOtp;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{

    public function guard()
    {
        return Auth::guard('api');
    }

    public function register(Request $request)
    {
        $user = User::where('email', $request->email)
            ->where('email_verified_at', null)
            ->first();

        if ($user) {
            $encryptedPhoneNumber = Crypt::encryptString($request->phone_number);
            $verificationLink = route('verify.email', [
                'token' => $user->otp,
                'phone_number' => $encryptedPhoneNumber,
            ]);

            Mail::to($request->email)->send(new SendOtp($verificationLink));

            return response(['message' => 'Please check your email for validate your email.'], 200);
        } else {
            Validator::extend('contains_dot', function ($attribute, $value, $parameters, $validator) {
                return strpos($value, '.') !== false;
            });

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:2|max:100',
                'email' => 'required|string|email|max:60|unique:users|contains_dot',
                'password' => 'required|string|min:6|confirmed',
                'role' => ['required', Rule::in(['STUDENT', 'ADMIN', 'SUPER ADMIN', 'MENTOR'])],
                'phone_number' => 'required|string|min:10|max:15',
            ], [
                'email.contains_dot' => 'without (.) Your email is invalid',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 400);
            }

            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'otp' => Str::random(6),
            ];

            $user = User::create($userData);

            $encryptedPhoneNumber = Crypt::encryptString($request->phone_number);

            $verificationLink = route('verify.email', [
                'token' => $user->otp,
                'phone_number' => $encryptedPhoneNumber,
            ]);

            Mail::to($request->email)->send(new SendOtp($verificationLink));

            return response()->json([
                'message' => 'Please check your email to verify your account',
            ]);
        }
    }

    public function login(LoginRequest $request)
    {
        $userData = User::where('email', $request->email)->first();
        if ($userData && Hash::check($request->password, $userData->password)) {
            if ($userData->email_verified_at ==  null) {
                return response()->json(['message' => 'Your email is not verified'], 401);
            }
        }

        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            return $this->responseWithToken($token);
        }

        return response()->json(['message' => 'Your credential is wrong'], 402);
    }

    public function emailVerifiedOtp(Request $request, $token)
    {
        $user = User::where('otp', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid verification link'], 400);
        }

        try {
            $phone_number = Crypt::decryptString($request->query('phone_number'));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid phone number'], 400);
        }

        $user->email_verified_at = Carbon::now();
        $user->otp = 0;
        $user->save();

        // Insert into student table
        Student::create([
            'user_id' => $user->id,
            'phone_number' => $phone_number,
        ]);

        $result = app('App\Http\Controllers\NotificationController')->sendNotification('Account Setup Successful', $user->created_at, $user->name, $user);

        return response()->json(['message' => 'Email verified successfully'], 200);
    }

    public function emailVerified(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()], 422);
        }
        if ($request->otp) {
            $user = User::where('otp', $request->otp)->first();
            if ($user != null) {
                $token = $this->guard()->login($user);
            }
        }

        $user = User::where('otp', $request->otp)->first();

        if (!$user) {
            return response(['message' => 'Invalid'], 422);
        }
        $user->email_verified_at = new Carbon();
        $user->otp = 0;
        $user->save();

        return response([
            'message' => 'Email verified successfully',
            'token' => $this->respondWithToken($token),
        ]);
    }

    public function responseWithToken($token)
    {
        $user = Auth::guard('api')->user()->makeHidden([ 'otp', 'created_at', 'updated_at']);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $user,
            'expires_in' => auth('api')
                    ->factory()
                    ->getTTL() * 600000000000,  // hour*seconds
        ]);
    }

    public function forgetPassword(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['error' => 'Email not found'], 401);
        } else {
            $random = Str::random(6);
            Mail::to($request->email)->send(new SendOtp($random));
            $user->update(['otp' => $random]);
            $user->email_verified_at = new Carbon();
            return response()->json(['message' => 'Please check your email for get the OTP']);
        }
    }

    public function emailVerifiedForResetPass(Request $request)
    {
        $user = User::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$user) {
            return response()->json(['error' => 'Your verified code does not matched'], 401);
        } else {
            $user->email_verified_at = new Carbon();
            $user->otp = 0;
            return response()->json(['message' => 'Now your email is verified'], 200);
        }
    }

    public function updatePassword(Request $request)
    {
        $user = $this->guard()->user();

        if ($user) {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:6|different:current_password',
                'confirm_password' => 'required|string|same:new_password',
            ]);

            if ($validator->fails()) {
                return response(['errors' => $validator->errors()], 409);
            }
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'Your current password is wrong'], 409);
            }
            $user->update(['password' => Hash::make($request->new_password)]);

            return response(['message' => 'Password updated successfully'], 200);
        } else {
            return response()->json(['message' => 'You are not authorized!'], 401);
        }
    }

    public function resendOtp(Request $request)
    {
        $user = User::where('email', $request->email)
            //            ->where('verify_email', 0)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'User not found or email already verified'], 404);
        }

        // Check if OTP resend is allowed (based on time expiration)
        $currentTime = now();
        $lastResentAt = $user->last_otp_sent_at;  // Assuming you have a column in your users table to track the last OTP sent time

        // Define your expiration time (e.g., 5 minutes)
        $expirationTime = 5;  // in minutes

        if ($lastResentAt && $lastResentAt->addMinutes($expirationTime)->isFuture()) {
            // Resend not allowed yet
            return response()->json(['message' => 'You can only resend OTP once every ' . $expirationTime . ' minutes'], 400);
        }

        $newOtp = Str::random(6);
        Mail::to($user->email)->send(new SendOtp($newOtp));

        // Update user data
        $user->update(['otp' => $newOtp]);
        $user->update(['last_otp_sent_at' => $currentTime]);

        return response()->json(['message' => 'OTP resent successfully']);
    }
    public function resetPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Your email is not exists'
            ], 401);
        }
        if ($user->email_verified_at == null) {
            return response()->json([
                'message' => 'Your email is not verified'
            ], 401);
        }
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            $user->update(['password' => Hash::make($request->password)]);
            return response()->json(['message' => 'Password reset successfully'], 200);
        }
    }
    public function loggedUserData()
    {
        if ($this->guard()->user()) {
            $user = $this->guard()->user();

            if($this->guard()->user()->role == 'STUDENT'){
                $user = User::with('student')->where('id', $user->id)->first();
                return response()->json([
                    'user' => $user
                ]);
            }
            elseif ($this->guard()->user()->role == 'MENTOR')
            {
                $user = User::with('teacher')->where('id', $user->id)->first();
                return response()->json([
                    'user' => $user
                ]);
            }
            elseif ($this->guard()->user()->role == 'SUPER ADMIN')
            {
                return response()->json([
                    'user' => $user
                ]);
            }
            elseif ($this->guard()->user()->role == 'ADMIN')
            {
                return response()->json([
                    'user' => $user
                ]);
            }
        } else {
            return response()->json(['message' => 'You are unauthorized'],401);
        }
    }
}
