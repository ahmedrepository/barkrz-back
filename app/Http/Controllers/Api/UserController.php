<?php

namespace App\Http\Controllers\Api;

use App\coupon;
use App\User;
use App\Pet;
use App\documents;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Stripe;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function handShake(Request $request) {
        if ($request->loggedUser) {
            return ['expired'=>false];
        } else {
            return ['expired' => true];
        }
    }

    public function Documents(Request $request) {
        if ($request->loggedUser) {            
            $user = User::findOrFail($request->loggedUser['id']);
            $document = $request->file('document');
            $reqData = json_decode($request->data);
            $document = $request->file('document')->storePublicly('documents');
            Log::debug("here");
            $document_path = Storage::url($document);
            $user->Documents()->create(['name'=>$reqData->name,'url'=>$document_path]);
            $documents = documents::where('user_id',$user->id)->orderby('created_at','asc')->get()->toArray();
            $data = [];
            
            foreach($documents as $document) {
               
                $date = $document['created_at'];
                $date = date('m/d/Y', strtotime($date));
                Log::debug($date);
                if (!isset($data[$date])) {
                    $data[$date] = [];
                } 
                array_push($data[$date],$document);

            }
            return [
                'documents' => $data
            ];
        } else {
            return ['expired' => $request->error];
        }
    }

    public function FetchDocuments(Request $request) {
        if ($request->loggedUser) {         
            $user = User::findOrFail($request->loggedUser['id']);
            $data = [];
            $documents = documents::where('user_id',$user->id)->orderby('created_at','asc')->get()->toArray();
            foreach($documents as $document) {
                $date = $document['created_at'];
                $date = date('m/d/Y', strtotime($date));
                Log::debug($date);
                if (!isset($data[$date])) {
                    $data[$date] = [];
                }
                array_push($data[$date],$document);
            }
            return [
                'documents' => $data
            ];
        } else {
            return ['expired' => $request->error];
        }
    }

    public function CheckCode(Request $request){
        try {
            $existance = 0;
            if(Pet::where('identity_code', $request->identity_code)->get()->first()){
                $existance = 1;
            } else {
                $existance = 0;
            }
            
            return ['success' => true, 'message' =>"Success!", 'existance' => $existance];    
        } catch(\Exception $e) {
            Log::debug(__FUNCTION__.$e->getMessage());
            return ['success'=>false,'message'=>$e->getMessage(), 'existance' => $existance];
        }
    }

    public function Coupon(Request $request) {
        $cc = coupon::where('id',1)->get()->first();
        $barkrz_beta = $cc->beta;
        $barkrz_fam = $cc->fam;
        
        if ($request->loggedUser) {
            $user = $request->loggedUser;
            $coupon = $request->coupon;
            
            if ($coupon == $barkrz_beta) {
                return ['success' => 1];
            } else if ($coupon == $barkrz_fam) {
                return ['success' => 2];
            } else {
                return ['success' => 0, 'message' =>"Wrong Coupon Code!"];
            }
        }
    }

    public function CheckCouponCode($couponcode){
        $cc = coupon::where('id',1)->get()->first();
        $barkrz_beta = $cc->beta;
        $barkrz_fam = $cc->fam;
        if($couponcode == $barkrz_beta) {
            return 1;
        } else if ($couponcode == $barkrz_fam){
            return 2;
        } else {
            return 0;
        }
    }

    public function SaveCard(Request $request) {

        if ($request->loggedUser) {

            $user = $request->loggedUser;
            $today = date('Y/m/d');
            $updated = $user->membership_updated;
            $card_data = [
                "name" => $request->input('name')?$request->input('name'):$user->name,
                'expiry' => $request->input('expiry')?$request->input('expiry'):$user->expiry,
                'membership_plan' => $request->input('membership')?$request->input('membership'):$user->membership_plan,
                'membership_created' => $user->membership_created?$user->membership_created:$today,
                'membership_updated' => $updated,
            ];
            
            $coupon = $this->CheckCouponCode($request->input('couponcode'));
            $couponSuccess = false;
            $couponMessage = "";
            if($request->input('couponcode')) {
                if($coupon == 0){
                    return ['success'=>false,'message'=>'Wrong coupon code'];
                } else if ($coupon == 1) {
                    if ($card_data['membership_plan'] == 1) {
                        $couponMessage = "$0 (will renew at $2.99 in 1 month)";
                    } else {
                        $couponMessage = "$0 (will renew at $29.99 in 1 month)";
                    }
                    $couponSuccess = true;
                } else if ($coupon == 2) {
                    if ($card_data['membership_plan'] == 1) {
                        $couponMessage = "$0 (will renew at $2.99 in 3 month)";
                    } else {
                        $couponMessage = "$0 (will renew at $29.99 in 3 month)";
                    }
                    $couponSuccess = true;
                }
            }
            
            
            $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $stripeclient = new \Stripe\StripeClient(env('STRIPE_SECRET'));

            $expiry_month = substr($card_data['expiry'],0,2);
            $expiry_year = substr($card_data['expiry'],5,strlen($card_data['expiry'])-5);

            // nocard is "Yes", use the new card,
            
            if($request->input('nocard') == "Yes"){
                // Create card_token, then save that record to db.
                try {
                    $response = \Stripe\Token::create(array(
                        "card" => array(
                            "exp_month" => $expiry_month,
                            "exp_year" => $expiry_year,
                            "number" => $request->input('number'),
                            "cvc" => $request->input('cvc'),
                            "name" => $request->input('name'),
                        )));
                    
                    // if exists, then update customer
                    
                    if($request->loggedUser['customer_id']){
                        // Get the customer
                        $customer = \Stripe\Customer::retrieve($request->loggedUser['customer_id']);
                        // Add a new card to the customer
                        $card = $stripeclient->customers->createSource($request->loggedUser['customer_id'], ['source'=>$response['id']]);
                        // API version
                        // $card = $customer->sources->create(['source' => $response['id']]);
                        // Set the new card as the customers default card
                        $customer->default_source = $card['id'];
                        $customer->save();
                        
                    } else{
                        // create customer
                        $customer = \Stripe\Customer::create(array(
                            'email' => $request->loggedUser['email'],
                            'source' => $response['id'],
                            'description' => "barkrz"
                        ));
                    }

                    // update stripe token, brand, last4, 
                    $response_card_data = [
                        'card_brand'        => $response->card['brand'],
                        'card_last_four'    => $response->card['last4'],
                        'customer_id'        => $customer['id'],
                    ];
                    User::where('id',$user->id)->update($response_card_data);
                    
                } catch(\Exception $e) {
                    Log::debug(__FUNCTION__.$e->getMessage());
                    return ['success'=>false,'message'=>$e->getMessage()];
                }              
            } else {
                // retrieve customer
                $customer = \Stripe\Customer::retrieve($request->loggedUser['customer_id']);
            }
            
            
            if(($user->membership_plan != 0) && ($request->input('membership') != '') && ($user->membership_plan != (int)$request->input('membership'))){
                // choose another membership
                try{
                    $stripeclient->subscriptions->cancel($user->membership_sub_token, []);
                    User::where('id',$user->id)->update(['membership_sub_token'=>""]);
                    $plan = $request->input('membership');
                    $price = $plan=='2' ? env('YEARLY_MEMBERSHIP') : env('MONTHLY_MEMBERSHIP');
                    if($coupon == 1){
                        $trial_end = strtotime('+1 month',strtotime('today'));
                    } else if($coupon == 2){
                        $trial_end = strtotime('+3 month',strtotime('today'));
                    } else {
                        $trial_end = 0;
                    }
                    if($trial_end){
                        $subscription = \Stripe\Subscription::create([
                            'customer' => $customer['id'],
                            'items' => [
                                [
                                    'price' => $price,
                                ],
                            ],
                            'trial_end' => $trial_end,
                        ]);
                    } else {
                        $subscription = \Stripe\Subscription::create([
                            'customer' => $customer['id'],
                            'items' => [
                                [
                                    'price' => $price,
                                ],
                            ],
                        ]);
                    }
                    //save to db subscription
                    $updated = $today;
                    $card_data['membership_sub_token'] = $subscription['id'];
                    User::where('id',$user->id)->update($card_data);
                } catch(\Exception $e) {
                    Log::debug(__FUNCTION__.$e->getMessage());
                    return ['success'=>false,'message'=>$e->getMessage()];
                }
            }
            
            if ($user->membership_created) {
                if(!$user->membership_updated){
                    // if canceled membership,
                    try{
                        // Log::debug("here");
                        $plan = $request->input('membership');
                        $price = $plan=='2' ? env('YEARLY_MEMBERSHIP') : env('MONTHLY_MEMBERSHIP');
                        if($coupon == 1){
                            $trial_end = strtotime('+1 month',strtotime('today'));
                        } else if($coupon == 2){
                            $trial_end = strtotime('+3 month',strtotime('today'));
                        } else {
                            $trial_end = 0;
                        }
                        if($trial_end){
                            $subscription = \Stripe\Subscription::create([
                                'customer' => $customer['id'],
                                'items' => [
                                    [
                                        'price' => $price,
                                    ],
                                ],
                                'trial_end' => $trial_end,
                            ]);
                        } else {
                            $subscription = \Stripe\Subscription::create([
                                'customer' => $customer['id'],
                                'items' => [
                                    [
                                        'price' => $price,
                                    ],
                                ],
                            ]);
                        }
                        //save to db subscription
                        $updated = $today;
                        $card_data['membership_sub_token'] = $subscription['id'];
                        User::where('id',$user->id)->update($card_data);
                    } catch(\Exception $e) {
                        Log::debug(__FUNCTION__.$e->getMessage());
                        return ['success'=>false,'message'=>$e->getMessage()];
                    }
                }
            } else {
                //never charged before, need to charge stripe first time , +2.05$ , +2.95$
                $plan = $request->input('membership')?$request->input('membership'):$user->membership_plan;
                $price = $plan=='2' ? env('YEARLY_MEMBERSHIP') : env('MONTHLY_MEMBERSHIP');
                
                try {
                    if($coupon == 1){
                        $trial_end = strtotime('+1 month',strtotime('today'));
                    } else if($coupon == 2){
                        $trial_end = strtotime('+3 month',strtotime('today'));
                    } else {
                        $trial_end = 0;
                    }
                    if($trial_end){
                        $subscription = \Stripe\Subscription::create([
                            'customer' => $customer['id'],
                            'items' => [
                                [
                                    'price' => $price,
                                ],
                            ],
                            'trial_end' => $trial_end,
                        ]);
                    } else {
                        $subscription = \Stripe\Subscription::create([
                            'customer' => $customer['id'],
                            'items' => [
                                [
                                    'price' => $price,
                                ],
                            ],
                        ]);
                    }
                    //save to db subscription
                    $card_data['membership_sub_token'] = $subscription['id'];
                    User::where('id',$user->id)->update($card_data);
                    $updated = $today;
                    
                } catch(\Exception $e) {
                    Log::debug(__FUNCTION__.$e->getMessage());
                    return ['success'=>false,'message'=>$e->getMessage()];
                }
            }
            
            $card_data['membership_updated'] = $updated;
            User::where('id',$user->id)->update($card_data);
            $user = User::where('id',$user->id)->get()->first();

            return [
                'token' => $request->token,
                'user_name' => $user->name,
                'expiry'=>$user->expiry,
                'email' => $user->email,
                'card_last_four' => $user->card_last_four,
                'card_brand' => $user->card_brand,
                'membership'=>$user->membership_plan,
                'membership_created'=>$user->membership_created,
                'success' => true,
                'message' => null,
                'couponSuccess' => $couponSuccess,
                'couponMessage' => $couponMessage
            ];
        } else {
            return ['expired' => true];
        }
    }

    public function CancelMembership(Request $request) {
        if ($request->loggedUser) {         
            $user = User::findOrFail($request->loggedUser['id']);
                
            $stripeclient = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            if($user->membership_sub_token){
                try{
                    $stripeclient->subscriptions->cancel($user->membership_sub_token, []);
                } catch(\Exception $e) {
                    Log::debug(__FUNCTION__.$e->getMessage());
                    // return ['success'=>false,'message'=>$e->getMessage()];
                }
            }
            
            User::where('id',$user->id)->update([
                'membership_plan'=>0,
                'membership_updated'=>null,
                'membership_sub_token'=>"",
            ]);

            return ['success'=>true];
        } else {
            return ['expired' => $request->error];
        }
    }
}