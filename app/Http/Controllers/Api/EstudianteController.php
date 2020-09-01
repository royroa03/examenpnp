<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException as JWTExceptionAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class EstudianteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student-api', ['except' => ['login']]);
        $this->guard = "student-api";
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth($this->guard)->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json($this->guard()->user());
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth($this->guard)->logout();

        return response()->json(['data' => ['message' => 'Se cerró tu sesión en este dispositivo.']]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => auth($this->guard)->factory()->getTTL()
            ]
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }

    /**
     * This method is useful when try testing endpoints with user required.
     *
     * @param Request $request
     * @return mixed|\App\Student
     */
    public function getUserFromRequestToken(Request $request)
    {
        try {
            if (!$user = Auth::guard($this->guard)->setToken($request->bearerToken())->user()) {
                $this->throwUnauthorizedException();
            }
            return $user;
        } catch (TokenExpiredException $e) {
            $this->throwUnauthorizedException('token_expired');
        } catch (TokenInvalidException $e) {
            $this->throwUnauthorizedException('token_invalid');
        } catch (JWTExceptionAuth $e) {
            $this->throwBadRequest('token_absent: ' . $e->getMessage());
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function myProfile(Request $request)
    {
        $user = $this->getUserFromRequestToken($request);
        return response()->json([
            'data' => $user
        ]);
    }

}
