<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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

class DailyQuote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quote:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Respectively send a quote to User via email if subscription expired.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $stripeclient = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $users = User::all();
        foreach ($users as $user) {
            if($user->email == "sample@barkrz.com" || $user->email == "barkrz@admin.com")
            {
                continue;
            } else {
                try{
                    if($user->membership_sub_token) {
                        $subscription_mem = \Stripe\Subscription::retrieve($user->membership_sub_token);

                        $data = array(
                            'bodyMessage' => $subscription_mem,
                            'subject' => 'Membership Subscription',
                            'name' => $user->name, 
                            'email'=> $user->email);

                        Mail::send('email.contact', $data, function ($message) use($data) {
                            $message->to($data['email'], $data['name'])
                                ->from('contact@barkrz.com')
                                ->subject('Membership Subscription');
                        });
                        // if($subscription_mem['status'] != 'active'){
                        //     $stripeclient->subscriptions->cancel($user->membership_sub_token, []);
                        //     User::where('id',$user->id)->update([
                        //         'membership_plan'=>0,
                        //         'membership_sub_token'=>"",
                        //     ]);

                        //     $data = array(
                        //         'bodyMessage' => 'Your membership expired, You need to charge a card or try another card',
                        //         'subject' => 'Membership Expired',
                        //         'name' => $user->name, 
                        //         'email'=> $user->email);

                        //     Mail::send('email.contact', $data, function ($message) use($data) {
                        //         $message->to($data['email'], $data['name'])
                        //             ->from('contact@barkrz.com')
                        //             ->subject('Membership Expired');
                        //     });
                        // }
                    }
        
                    // if($user->pet_sub_token) {
                    //     $subscription_pet = \Stripe\Subscription::retrieve($user->pet_sub_token);
                    //     if($subscription_pet['status'] != 'active'){
                    //         $stripeclient->subscriptions->cancel($user->pet_sub_token, []);
                    //         User::where('id',$user->id)->update([
                    //             'membership_plan'=>0,
                    //             'pet_sub_token'=>"",
                    //         ]);

                    //         $data = array(
                    //             'bodyMessage' => 'You need to charge a card or try another card',
                    //             'subject' => 'Pet fee expired',
                    //             'name' => $user->name, 
                    //             'email'=> $user->email);

                    //         Mail::send('email.contact', $data, function ($message) use($data) {
                    //             $message->to($data['email'], $data['name'])
                    //                 ->from('contact@barkrz.com')
                    //                 ->subject('Pet fee expired');
                    //         });
                    //     }
                    // }
                } catch(\Exception $e) {
                    Log::debug(__FUNCTION__.$e->getMessage());
                    $this->info('Error.'.$e->getMessage());
                }
            }
        }
        $this->info('Successfully sent quote to expired user.');
    }
}