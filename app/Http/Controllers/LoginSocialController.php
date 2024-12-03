<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use App\Models\Social;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LoginSocialController extends Controller
{
    public function login_google()
    {
        return Socialite::driver('google')->redirect();
    }
    public function callback_google()
    {
        try {

            $googleUser = Socialite::driver('google')->user();
            Log::info('Thông tin người dùng từ Google: ' . json_encode($googleUser));


            $authUser = $this->findOrCreateUser($googleUser, 'google');
            Log::info('Thông tin authUser: ' . json_encode($authUser));

            if (!$authUser) {
                return redirect('/shop')->with('error', 'Đăng nhập thất bại');
            }


            $account_name = User::find($authUser->user_id);
            Log::info('Thông tin account_name: ' . json_encode($account_name));

            if ($account_name) {
                Auth::guard('web')->login($account_name, true);
                Log::info('Trạng thái Auth sau khi login: ' . json_encode(Auth::guard('web')->user()));


                return redirect()->route('account.profile')->with('message', 'Đăng nhập thành công');
            } else {
                return redirect('/shop')->with('error', 'Không tìm thấy thông tin người dùng');
            }
        } catch (\Exception $e) {
            Log::error('Lỗi trong callback_google: ' . $e->getMessage());
            return redirect('/shop')->with('error', 'Đăng nhập thất bại');
        }
    }

    public function findOrCreateUser($googleUser, $provider)
    {
        try {

            $authUser = Social::where('provider_user_id', $googleUser->id)->first();

            if ($authUser) {
                return $authUser;
            }


            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {

                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => bcrypt('123456'),
                ]);
            }

            $social = Social::create([
                'provider_user_id' => $googleUser->id,
                'provider' => strtoupper($provider),
                'user_id' => $user->id,
            ]);

            return $social;
        } catch (\Exception $e) {
            Log::error('Lỗi trong findOrCreateUser: ' . $e->getMessage());
            return null;
        }
    }
}
