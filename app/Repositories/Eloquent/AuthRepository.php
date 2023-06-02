<?php

namespace App\Repositories\Eloquent;

use App\Http\Requests\Api\AuthLoginRequest;
use App\Models\UserMobile;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository extends BaseRepository implements AuthRepositoryInterface
{
    use AuthenticatesUsers;

    protected function credentials(AuthLoginRequest $request)
    {
        return [
            'uid' => $request->get('username'),
            'user_password' => $request->get('user_password'),
        ];
    }

    public function username()
    {
        return 'userName';
    }

    public function __construct(UserMobile $model)
    {
        parent::__construct($model);
    }

    public function register(array $data)
    {
        $data['user_status_id'] = 1;  // 0 -> waiting admin account approved
        $data['user_type_id'] = isset($data['type']) ? $data['type'] : 1; // 1 -> type -> customer, 2 -> type -> user
        $data['company_group_id'] = 28; // 1 -> parent company related with & company_id = 29
        $data['parent_id'] = 2443; // customer related with(user or customer)
//        $data['company_group_id'] = 13; // 1 -> parent company related with & company_id = 29
//        $data['parent_id'] = isset($data['type']) && $data['type'] == 2 ? 70 : 2; // customer related with(user or customer)
        $data['user_name_ar'] = $data['name'];
        $data['user_name_en'] = $data['name'];
        $data['user_last_login'] = now();
        $data['user_start_date'] = now();
        $data['user_password'] = Hash::make($data['user_password']);
        unset($data['password_confirmation']);
        unset($data['name']);
        unset($data['type']);
        return UserMobile::create($data);
    }

    public function login(array $data)
    {
        try {
            $user = UserMobile::where("user_mobile", $data['user_mobile'])->firstOrFail();
            if ($user && Hash::check($data['user_password'], $user->user_password)) {
                if ($user->user_type_id != $data['type']) {
                    return responseFail(__('You Don\'t have permission to login'), 403);
                }
                if (!$user->user_status_id) {
                    return responseSuccess([], __('This user not active'));
                }
//                $userValid['phone'] = $data['user_mobile'];
//                $userValid['password'] = $data['user_password'];
//                if (!$token = Auth::attempt($userValid)) {
//                    return response()->json(['error' => 'Unauthorized'], 401);
//                }
                Auth::login($user);
                $token = auth()->user()->createToken($user->user_mobile)->accessToken;
                return $this->createNewToken($token);
            } else {
                return responseFail(__('could not find user'), 401);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'could_not_create_token',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout($request)
    {
//        Auth::logout();
        request()->session()->invalidate();
        return auth()->user()->token()->revoke();
    }

    protected function createNewToken($token)
    {
        return responseSuccess([
            'token' => $token,
            'token_type' => 'bearer',
//            'expires_in' => auth('api')->factory()->getTTL() * 60,
//            'user' => new UserResource(auth()->user())
        ], 'User logged In successfully', 200);

    }

    public function currentUser()
    {
        return Auth::user();
    }

    public function generateRandomString($length)
    {
        return rand(0000, 9999);
    }

    public function sendEmail($message, $email)
    {
        return true;
    }
}
