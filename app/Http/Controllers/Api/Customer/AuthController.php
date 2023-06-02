<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Requests\Api\AuthProfileImageRequest;
use App\Http\Requests\Api\FCMRequest;
use App\Http\Requests\Api\AuthLoginRequest;
use App\Http\Requests\Api\AuthProfileRequest;
use App\Http\Requests\Api\AuthRegisterRequest;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\Http\Requests\Api\ResetCodeRequest;
use App\Http\Requests\Api\ResetRequest;
use App\Http\Resources\Api\UserResource;
use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use App\Repositories\Eloquent\AuthRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\UserMobile;

class AuthController extends Controller
{
    protected $repo;

    public function __construct(AuthRepository $repo)
    {
        $this->repo = $repo;
    }

    public function login(AuthLoginRequest $request)
    {
        return $this->repo->login($request->validated());
    }

    public function register(AuthRegisterRequest $request)
    {
        $data = $this->repo->register($request->validated());

        if ($data) {
            return $this->repo->login($request->validated());
        } else {
            return responseFail("Cannot add user or your account still pending", 401);
        }
    }

    public function logout()
    {
        try {
            $this->repo->logout(request()->all());
            return responseSuccess([], 'User logged out successfully', 200);
        } catch (\Throwable $exception) {
            Log::error('logout', [$exception->getMessage()]);
            return responseFail('Sorry, the user cannot be logged out', 400);
        }
    }


    public function forgotEmail(ResetRequest $request)
    {
        try {
            $email = $request->email;
            $user = UserMobile::where('user_email', $email)->first();
            if ($user) {
                $code = $this->repo->generateRandomString(6);
                Mail::to($email)->send(new ResetPassword($code));
                UserMobile::where("user_email", $request->email)->update([
                    "user_otp" => $code
                ]);
            }

            return responseSuccess([], 'Reset password link sent on your email id.');
        } catch (\Swift_TransportException $ex) {
            return responseFail($ex->getMessage(), 400);
        } catch (\Exception $ex) {
            return responseFail($ex->getMessage(), 400);
        }
    }

    public function forgot(ResetRequest $request)
    {
        try {
            $user = UserMobile::where('user_mobile', $request->user_mobile)->firstorFail();
            if ($user) {
                $otpCode = $this->repo->generateRandomString(4);
                $user->update([
                    "user_otp" => 1234
                ]);
//                Mail::to($request->email)->send(new ResetPassword($otpCode));
                return responseSuccess([], 'We Send you Code at your phone please copy and paste it here', 200);

//                $message = "Your Reset Password OTP Code is " . $otpCode;
//                 $smsResult = $this->repo->sendSMS($request->phone, $message);
//                 if ($smsResult->getStatusCode() != 200 || $smsResult["code"] != 1901) {
//                     return responseFail("Cannot send reset code please try again later");
//                 } else {
//                     return responseSuccess([], 'We Send you Code at your phone please copy and paste it here');
//                 }
            } else {

                return responseFail("Your number not found please register first", 400);
            }
        } catch (\Exception $ex) {
            return responseFail($ex->getMessage(), 400);
        }
    }

    public function checkcode(ResetRequest $request)
    {
        try {
            $found = UserMobile::where("user_mobile", $request->user_mobile)
                ->where("user_otp", $request->user_otp)->first();

            if ($found) {
                return responseSuccess([], 'Success code is correct', 200);
            }
            return responseFail("Error Code you enter not correct", 400);
        } catch (\Exception $ex) {
            return responseFail($ex->getMessage(), 400);
        }
    }

    public function reset(ResetCodeRequest $request)
    {
        try {
            $user = UserMobile::where("user_otp", $request->user_otp)->update([
                'user_password' => Hash::make($request->password),
            ]);
            if ($user) {
                $user = UserMobile::where("user_otp", $request->user_otp)->update([
                    'user_otp' => null,
                ]);
                return responseSuccess([], __('messages.updated_successfully'), 200);
            } else {
                return responseFail("Cannot reset  password please try again later", 400);
            }
        } catch (\Exception $ex) {
            return responseFail($ex->getMessage(), 400);
        }
    }

    public function updateFcmToken(FCMRequest $token)
    {
        $user = UserMobile::find(auth()->user()->id);
        if ($user) {
            $user->update(['fcm_token' => $token->token]);
            return responseSuccess([], __('messages.updated_successfully'));

        }
        return responseFail("Cannot send Notification", 401);
    }

    public function profile(AuthProfileRequest $request)
    {
        $currentUser = $this->repo->findOrFail(auth()->user()->user_id);
        $data = $this->repo->update($request->validated() + [
                'user_name_en' => $request->name,
                'user_name_ar' => $request->name,
            ], $currentUser);

        if ($data) {
            return responseSuccess(new UserResource($currentUser->refresh()), __('messages.updated_successfully'));
        } else {
            return responseFail("Cannot Update user", 401);
        }
    }

    public function profileImage(AuthProfileImageRequest $request)
    {
        $currentUser = $this->repo->findOrFail(auth()->user()->user_id);
        $file = request()->file('image');
        $fileName = uploadFile($file, 'Users');
        $input['user_profile_url'] = $fileName;
        $data = $this->repo->update($input, $currentUser);

        if ($data) {
            return responseSuccess(new UserResource($currentUser->refresh()), __('messages.updated_successfully'));
        } else {
            return responseFail("Cannot Update user", 401);
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $currentUser = $this->repo->findOrFail(auth()->user()->user_id);
        $data = $this->repo->update(['user_password' => Hash::make($request->password)], $currentUser);
        if ($data) {
            return responseSuccess([], __('messages.updated_successfully'));
        } else {
            return responseFail("Cannot Update user", 401);
        }
    }

    public function me()
    {
        $data = $this->repo->currentUser();
        if ($data) {
            return responseSuccess(UserResource::make($data), 'retrieved data successfully');
        } else {
            return responseFail("Cannot find user", 401);
        }
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'success' => true,
            'message' => 'Retrieved successfully',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL()
        ]);
    }

    public function guard()
    {
        return auth('api')->guard();
    }

    public function deleteAccount()
    {
        try {
            $this->repo->logout(request()->all());
            $this->repo->changeStatus([
                'user_status_id' => 0
            ], auth()->user());
            return responseSuccess([], __('messages.deleted_successfully'));
        } catch (\Exception $e) {
            Log::error('auth deleteAccount', [$e]);
            return responseFail(__('messages.wrong_data'));
        }
    }
}
