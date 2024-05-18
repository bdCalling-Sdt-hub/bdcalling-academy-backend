<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = User::where('email', $request->email)
            ->where('verify_email', 0)
            ->first();

        if ($user) {
            $otp = Str::random(6);
            Mail::to($request->email)->send(new SendOtp($otp));
            $user->update(['otp' => $otp]);
            $user->update(['verify_email' => 0]);

            return response(['message' => 'Please check your email for validate your email.'], 200);
        } else {
            Validator::extend('contains_dot', function ($attribute, $value, $parameters, $validator) {
                return strpos($value, '.') !== false;
            });

            $validator = Validator::make($request->all(), [
                'fullName' => 'required|string|min:2|max:100',
                'email' => 'required|string|email|max:60|unique:users|contains_dot',
                'password' => 'required|string|min:6|confirmed',
                'userType' => ['required', Rule::in(['STUDENT', 'ADMIN', 'SUPER ADMIN', 'RECRUITER'])],
            ], [
                'email.contains_dot' => 'without (.) Your email is invalid',
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 400);
            }

            $userData = [
                'fullName' => $request->fullName,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'userType' => $request->userType,
                'otp' => Str::random(6),
                'verify_email' => 0
            ];

            $user = User::create($userData);

            Mail::to($request->email)->send(new OtpMail($user->otp));
            return response()->json([
                'message' => 'Please check your email to valid your email',
            ]);
        }
    }
}
