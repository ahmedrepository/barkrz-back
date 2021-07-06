<?php

namespace App\Http\Controllers\Api;

use App\coupon;
use App\Subscribe;
use Exception;
use Carbon\Carbon;
use App\User;
use App\Http\JWT\MyJWT;
use App\Pet;
use Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthApi extends Controller
{
    public function test()
    {
        return ['success' => 'true'];
    }

    public function login(Request $request)
    {
        if ($request->input('password') && $request->input('email')) {
            $user = User::where('email', $request->input('email'))->first();
            Log::error($user);
            if (!$user) {
                return [
                  "success" => false,
                  "message" => "Incorrect Email! Try again!"
                ];
            }
            $today = date('Y/m/d');
            
            if (Hash::check($request->input('password'), $user->password)) {
                $jwt = MyJWT::create('HS256');
                $jwt->user_id = $user->id;
                $jwt->exp = time() + (60 * 60 * 2);

                $encodedJwtHash = $jwt->encode(Config::get('remote.hash_key'));
                
                try{
                    $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                    $stripeclient = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                    if($user->membership_sub_token) {
                        $subscription_mem = \Stripe\Subscription::retrieve($user->membership_sub_token);
                        if($subscription_mem['status'] != 'active'){
                            $stripeclient->subscriptions->cancel($user->membership_sub_token, []);
                            User::where('id',$user->id)->update([
                                'membership_plan'=>0,
                                'membership_sub_token'=>"",
                            ]);
                        }
                    }
                    
                } catch(\Exception $e) {
                    Log::debug(__FUNCTION__.$e->getMessage());
                }

                User::where('id', $user->id)->update(['updated_at' => Carbon::now()]);
                return [
                    'token' => $encodedJwtHash,
                    'user_name' => $user->name,
                    'card_last_four' => $user->card_last_four,
                    'card_brand' => $user->card_brand,
                    'card_name' => $user->name,
                    'expiry'=>$user->expiry,
                    'email' => $user->email,
                    'membership'=>$user->membership_plan,
                    'membership_created'=>$user->membership_created,
                    'success' => true,
                    'message' => null,
                ];
            }
        }

        return [
            'token' => null,
            'user_name' => null,
            'success' => false,
            'message' => 'Incorrect Password! Try Again!',
        ];
    }

    public function register(Request $request)
    {
        $cc = coupon::where('id',1)->get()->first();
        $barkrz_beta = $cc->beta;
        $barkrz_fam = $cc->fam;
        if ($request->input('password') && $request->input('email')) {
            $user = new User();
            $first_name = $request->input('first_name') ? $request->input('first_name') : '';
            $last_name = $request->input('last_name') ? $request->input('last_name') : '';
            $user->name = $first_name." ".$last_name;
            $user->email = $request->input('email') ? $request->input('email') : '';
            $user->password = $request->input('password') ? Hash::make($request->input('password')) : '';
            $coupon = $request->input('coupon') ? $request->input('coupon') : '';

            if ($coupon != '' && $coupon == $barkrz_beta) {
                $user->membership_plan = 1;
                $user->membership_created = date('Y-m-d',strtotime('+1 month',strtotime('today')));
                $user->membership_updated = date('Y-m-d',strtotime('+1 month',strtotime('today')));
            } else if ($coupon != '' && $coupon == $barkrz_fam) {
                $user->membership_plan = 1;
                $user->membership_created = date('Y-m-d',strtotime('+3 month',strtotime('today')));
                $user->membership_updated = date('Y-m-d',strtotime('+3 month',strtotime('today')));
            }

            try {
                $user->save();
                return [
                    'success' => true,
                    'message' => 'Successfully Registered',
                ];
            }
            catch(Exception $e){
                return ['success' => false, 'message' =>"Email already Used!"];
            }
        } 

        return ['success' => false, 'message' => 'Something went wrong'];
    }

    public function logout()
    {

    }

    public function subscribe(Request $request) {
        $email = $request->input('email');
        Log::debug(__FUNCTION__.'=>'.$email);
        $already = Subscribe::where('email',$email)->get();
        if (count($already) == 0) {
            Subscribe::create(['email'=>$email]);
        }
        return ['success'=>true];
    }

    public function ContactMessage(Request $request) {
        $subject = $request->input('subject');
        $message = $request->input('message');
        Log::debug(__FUNCTION__.$message);
        try {
            Mail::send('email.contact', ['subject'=>$subject ,'bodyMessage' => $message], function($message) use ($request)  {
                $message->to('mgeagle75@outlook.com','Barkrz')->subject("Message from ".$request->name);
                $message->from('mgeagle75@gmail.com',$request->email);
            });
        } catch (Exception $exception) {
            Log::debug(__FUNCTION__.$exception->getMessage());
        }
        return ['success'=>true];
    }
}