<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;
use Illuminate\Support\Facades\DB;

#[OA\Tag(name: 'Auth', description: 'Authentication')]
class AuthController extends Controller
{
    #[OA\Post(
        path: '/api/auth/register',
        summary: 'Register a new user',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password', 'password_confirmation'],
                properties: [
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'email', type: 'string', format: 'email'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', minLength: 8),
                    new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', minLength: 8),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'User registered',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'user', ref: '#/components/schemas/User'),
                        new OA\Property(property: 'access_token', type: 'string'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        $user->notify(new \App\Notifications\WelcomeNotification());

        return response()->json([
            'message' => 'User registered successfully. A welcome email has been sent to your email.',
            'data' => [
                'user' => $user,
                'access_token' => $token,
            ],
            'status' => 201
        ], 201);
    }

    #[OA\Post(
        path: '/api/auth/login',
        summary: 'Login and retrieve an API token',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email'),
                    new OA\Property(property: 'password', type: 'string', format: 'password'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Authenticated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'access_token', type: 'string'),
                        new OA\Property(property: 'token_type', type: 'string', example: 'bearer'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation / auth error'),
        ]
    )]
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
            ],
            'status' => 200
        ]);
    }

    #[OA\Post(
        path: '/api/auth/logout',
        summary: 'Logout current user (revoke token)',
        tags: ['Auth'],
        security: [['bearer' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Logged out'),
        ]
    )]
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out successfully',
            'status' => 200
        ]);
    }

    #[OA\Post(
        path: '/api/auth/forgot-password',
        summary: 'Request password reset OTP',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Reset OTP sent'),
            new OA\Response(response: 422, description: 'Error sending OTP'),
        ]
    )]
    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $expire = config('auth.passwords.users.expire', 15);
        DB::table('password_reset_tokens')
            ->where('created_at', '<', now()->subMinutes($expire))
            ->delete();

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return response()->json(['message' => __('passwords.user')], 422);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($otp),
                'created_at' => now()
            ]
        );

        $user->notify(new \App\Notifications\ResetPasswordNotification($otp));

        return response()->json([
            'message' => __('passwords.sent'),
            'status' => 200
        ]);
    }

    #[OA\Post(
        path: '/api/auth/reset-password',
        summary: 'Reset password using OTP',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['otp', 'email', 'password', 'password_confirmation'],
                properties: [
                    new OA\Property(property: 'otp', type: 'string', minLength: 6, maxLength: 6),
                    new OA\Property(property: 'email', type: 'string', format: 'email'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', minLength: 8),
                    new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', minLength: 8),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Password reset'),
            new OA\Response(response: 422, description: 'Invalid OTP or data'),
        ]
    )]
    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'otp' => ['required', 'string', 'size:6'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $validated['email'])->first();

        if (!$record || !Hash::check($validated['otp'], $record->token)) {
            return response()->json(['message' => __('passwords.token')], 422);
        }

        $expire = config('auth.passwords.users.expire', 15);
        if (now()->subMinutes($expire)->gt($record->created_at)) {
            DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();
            return response()->json(['message' => __('passwords.token')], 422);
        }

        $user = User::where('email', $validated['email'])->first();
        if (!$user) {
            return response()->json(['message' => __('passwords.user')], 422);
        }

        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

        $user->tokens()->delete();

        return response()->json([
            'message' => __('passwords.reset'),
            'status' => 200
        ]);
    }
}
