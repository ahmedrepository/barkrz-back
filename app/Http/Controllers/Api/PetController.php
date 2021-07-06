<?php

namespace App\Http\Controllers\Api;

use App\Pet;
use App\Owner;
use App\PhoneNumber;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Stripe;
use Illuminate\Support\Facades\Log;

class PetController extends Controller
{
    public function Index(Request $request) {

        if ($request->loggedUser) {

            $user = User::findOrFail($request->loggedUser['id']);
            $pets = $user->Pets()->get();

            $data = array();
            foreach($pets as $pet) {
                $owners = $pet->owners()->get();

                $owners_data = [];
                foreach($owners as $owner) {
                    $phone_numbers = $owner->PhoneNumbers()->get();
                    array_push($owners_data,['owner'=>$owner,'phone_numbers'=>$phone_numbers]);
                }
                array_push($data,["pet" => $pet , "owners"=>$owners_data]);
            }
            return $data;
        } else {
            return ['expired' => true];
        }
    }
    public function SaveImage($data,$path) {
        $filename = ''.date('mdYHis') . uniqid();
        if (!is_dir($path )) {
            mkdir($path);
        }

        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
                throw new \Exception('invalid image type');
            }

            $data = base64_decode($data);

            if ($data === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            return null;
        }
        $image_path = $path.'/'.$filename.".".$type;
        file_put_contents($image_path, $data);

//        return env('APP_URL', 'http://barkrz.toplev.io/admin/').$image_path;
        return $image_path;
    }

    public function Create(Request $request) {
        if ($request->loggedUser) {
            $user = User::findOrFail($request->loggedUser['id']);
             //check for available
            $memebership = $user->membership_plan;
            if ($memebership == 0) {
                return ['success' => false , 'error'=>'You have to buy a membership to create a pet'];
            }
            $today = date('Y/m/d');
            $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $stripeclient = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            try{
                if($user->membership_sub_token) {
                    $subscription_mem = \Stripe\Subscription::retrieve($user->membership_sub_token);
                    if($subscription_mem['status'] != 'active'){
                        $stripeclient->subscriptions->cancel($user->membership_sub_token, []);
                        User::where('id',$user->id)->update([
                            'membership_plan'=>0,
                            'membership_sub_token'=>"",
                        ]);
                        return ['success' => false , 'error'=>'Your membership expired, You need to charge a card or try another card'];
                    }
                }

                if($user->pet_sub_token) {
                    $subscription_pet = \Stripe\Subscription::retrieve($user->pet_sub_token);
                    if($subscription_pet['status'] != 'active'){
                        $stripeclient->subscriptions->cancel($user->pet_sub_token, []);
                        User::where('id',$user->id)->update([
                            'membership_plan'=>0,
                            'pet_sub_token'=>"",
                        ]);
                        return ['success' => false , 'error'=>'You need to charge a card or try another card'];
                    }
                }
            } catch(\Exception $e) {
                Log::debug(__FUNCTION__.$e->getMessage());
            }

            $pets = Pet::where('user_id',$user->id)->get()->toArray();
            
            // charge stripe
            // paid for creating a pet +1$ stripe
            $expiry_month = substr($user->expiry,0,2);
            $expiry_year = substr($user->expiry,5,strlen($user->expiry)-5);
            $pets_with_user = count($pets);
            try {
                if($pets_with_user > 0){
                    if($user->pet_sub_token){
                        
                        $subscription = \Stripe\Subscription::retrieve($user->pet_sub_token);
                        $stripeclient->subscriptions->update(
                            $user->pet_sub_token,
                            [
                                'quantity' => $pets_with_user,
                                'proration_behavior' => 'always_invoice'
                            ]
                        );
                        if($subscription['status'] != 'active'){
                            return ['success'=>false,'message'=>"You can't create pet since Payment Not Completed."];
                        }
                    } else {
                        $subscription_pet = \Stripe\Subscription::create([
                            'customer' => $user->customer_id,
                            'items' => [
                                [
                                    'price' => env('PET_METERED_Billing'),
                                ],
                            ],
                        ]);
                        if($subscription_pet['status'] != 'active'){
                            return ['success'=>false,'message'=>"You can't create pet since Payment Not Completed."];
                        }
                        //save to db subscription
                        $pet_subscription['pet_sub_token'] = $subscription_pet['id'];
                        
                        User::where('id',$user->id)->update($pet_subscription);
                    }
                }
            } catch(\Exception $e) {
                Log::debug(__FUNCTION__.$e->getMessage());
                return ['success'=>false,'error'=>$e->getMessage()];
            }
           

            $data = json_decode($request->data);
            $image = $request->file('file');
            
            if($image) {
                $file = $request->file('file')->storePublicly('PETS');
                $image_path = Storage::url($file);
            } else {
                $image_path = 'https://barkrz.s3.us-east-2.amazonaws.com/PETS/sample.png';
            }

            $created = $today;
            $updated = $today;
            $pet_array = [
                'name' => $data->name,
                'gender' => $data->gender,
                'identity_code' => $data->identity_code,
                'image' => $image_path,
                'address' => $data->address,
                'breed' => $data->breed,
                'age' => $data->age,
                'neutered' => $data->neutered,
                'medicalCondition' => $data->medicalCondition,
                'temperament' => $data->temperament,
                'weight' => $data->weight,
                'created' => $created,
                'updated' => $updated,
                'paid' => true,
            ];
            
            $pet = $user->Pets()->create($pet_array);

            $owners = $data->ownerNames;
            foreach($owners as $owner) {
                $owner = json_decode(json_encode($owner), true);
                $owner_array=[
                    'name' => $owner["name"],
                ];

                $owner_eloquent = Pet::where('id',$pet->id)->first()->owners()->create($owner_array);
                $phone_numbers = $owner["phone_numbers"];
                foreach($phone_numbers as $phone_number) {
                    Owner::where('id',$owner_eloquent->id)->first()->PhoneNumbers()->create(['phone_number'=>$phone_number['phone']]);
                }
            }

            $new_owners = $pet->owners()->get();
            $owners_data  = [];
            foreach($new_owners as $new_owner) {
                $phone_numbers = $new_owner->PhoneNumbers()->get();
                array_push($owners_data,['owner'=>$new_owner,'phone_numbers'=>$phone_numbers]);
            }
            return ['success'=>true , 'data'=>["pet" => $pet , "owners"=>$owners_data]];
        } else {
            return ['expired' => $request->error];
        }
    }

    public function Update(Request $request)
    {
        if ($request->loggedUser) {
            $user = User::findOrFail($request->loggedUser['id']);
            $data = json_decode($request->data);

            $pet_id = $data->id;
            $pet = Pet::where('id', $pet_id)->get()->first();
            $image = $request->file('file');

            if($image) {
                $file = $request->file('file')->storePublicly('PETS');
                $image_path = Storage::url($file);
                // unless sample.png
                if(parse_url($pet->image, PHP_URL_PATH) != '/PETS/sample.png'){
                    Storage::disk('s3')->delete(parse_url($pet->image, PHP_URL_PATH));
                }
            } else {
                $image_path = $pet->image;
            }

            $pet_array = [
                'name' => $data->name,
                'gender' => $data->gender,
                'image' => $image_path,
                'address' => $data->address,
                'breed' => $data->breed,
                'age' => $data->age,
                'neutered' => $data->neutered,
                'medicalCondition' => $data->medicalCondition,
                'temperament' => $data->temperament,
                'weight' => $data->weight,
            ];

            $pet->update($pet_array);
            $owners = $data->ownerNames;

            foreach ($owners as $owner) {
                $owner = json_decode(json_encode($owner), true);
                if (isset($owner['owner']["id"])) {
                    if (isset($owner['owner']["deleted"]) && $owner['owner']["deleted"] == true) {
                        Owner::where('id', $owner['owner']['id'])->delete();
                    } else {
                        $owner_array = [
                            'name' => $owner["owner"]["name"],
                        ];
                        Owner::where('id', $owner["owner"]['id'])->update($owner_array);
                        $phone_numbers = $owner["phone_numbers"];
                        foreach ($phone_numbers as $phone_number) {
                            if (isset($phone_number['id'])) {
                                if (isset($phone_number['deleted']) && $phone_number['deleted'] == true) {
                                    PhoneNumber::where('id', $phone_number['id'])->delete();
                                }
                                else {
                                    PhoneNumber::where('id', $phone_number['id'])->update(['phone_number' => $phone_number['phone_number']]);
                                }
                            } else {
                                PhoneNumber::create([
                                    'phone_number' => $phone_number['phone_number'] ,
                                    'owner_id' => $owner["owner"]['id'],
                                ]);
                            }
                        }
                    }
                } else {
                    $owner_array=[
                        'name' => $owner['owner']["name"],
                    ];
                    $owner_eloquent = $pet->owners()->create($owner_array);
                    $phone_numbers = $owner["phone_numbers"];

                    foreach($phone_numbers as $phone_number) {
                        $owner_eloquent->PhoneNumbers()->create(['phone_number'=>$phone_number['phone_number']]);
                    }
                }
            }
            $new_owners = $pet->owners()->get();
            $owners_data = [];
            foreach ($new_owners as $new_owner) {
                $phone_numbers = $new_owner->PhoneNumbers()->get();
                array_push($owners_data, ['owner' => $new_owner, 'phone_numbers' => $phone_numbers]);
            }
            return ['success' => true, 'data' => ["pet" => $pet, "owners" => $owners_data]];
        } else {
             return ['expired' => $request->error];
        }
    }

    public function Delete(Request $request) {
        $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $stripeclient = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        
        if ($request->loggedUser) {
            $user = User::findOrFail($request->loggedUser['id']);
            $pet_id = $request->id;
            $pet = Pet::where('id',$pet_id)->get()->first();
            
            // unless sample.png
            if(parse_url($pet->image, PHP_URL_PATH) != '/PETS/sample.png'){
                Storage::disk('s3')->delete(parse_url($pet->image, PHP_URL_PATH));
            }
            
            try {
                $pets = Pet::where('user_id',$user->id)->get()->toArray();
                $pets_with_user = count($pets);
                if($pets_with_user > 2){
                    // modify quantity
                    $subscription = \Stripe\Subscription::retrieve($user->pet_sub_token);
                    $stripeclient->subscriptions->update(
                        $user->pet_sub_token,
                        [
                            'quantity' => $pets_with_user-2,
                        ]
                    );
                } else {
                    // remove pet_sub_token if exists
                    if($user->pet_sub_token){
                        $stripeclient->subscriptions->cancel($user->pet_sub_token, []);
                        User::where('id',$user->id)->update(['pet_sub_token'=>""]);
                    }
                }
                Pet::where('id',$pet_id)->delete();
            } catch(\Exception $e) {
                Log::debug(__FUNCTION__.$e->getMessage());
                return ['success'=>false,'error'=>$e->getMessage()];
            }
            return ['success'=>true];
        } else {
            return ['expired' => $request->error];
        }
    }

    public function Profile(Request $request) {
        $identity_code = $request->code;
        $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $stripeclient = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        
        $pet = Pet::where('identity_code',$identity_code)->get()->first();
        if (!$pet) {
            return ['error'=>'Invalid Pet ID', 'created' => false];
        }
        $user = User::where('id',$pet->user_id)->get()->first();
        
        $inactive = false;
        if($user->membership_sub_token) {
            $subscription_mem = \Stripe\Subscription::retrieve($user->membership_sub_token);
            if($subscription_mem['status'] != 'active'){
                $stripeclient->subscriptions->cancel($user->membership_sub_token, []);
                User::where('id',$user->id)->update([
                    'membership_plan'=>0,
                    'membership_sub_token'=>"",
                ]);
                $inactive = true;
            }
        }

        if($user->pet_sub_token) {
            $subscription_pet = \Stripe\Subscription::retrieve($user->pet_sub_token);
            if($subscription_pet['status'] != 'active'){
                $stripeclient->subscriptions->cancel($user->pet_sub_token, []);
                User::where('id',$user->id)->update([
                    'membership_plan'=>0,
                    'pet_sub_token'=>"",
                ]);
                $inactive = true;
            }
        }
        
        if ( $user->email != "sample@barkrz.com" && ($user->membership_plan == 0 || $inactive == true)) {
            return ['error'=>'This pet is not available',  'created' => true];
        }

        $new_owners = $pet->owners()->get();
        $owners_data = [];
        foreach ($new_owners as $new_owner) {
            $phone_numbers = $new_owner->PhoneNumbers()->get();
            array_push($owners_data, ['owner' => $new_owner, 'phone_numbers' => $phone_numbers]);
        }
        return ['pet'=>$pet,'owners'=>$owners_data];
    }
    public function Sample(Request $request) {
        $id = $request->id;
        $pet = Pet::where('id',$id)->get()->first();

        $new_owners = $pet->owners()->get();
        $owners_data = [];
        foreach ($new_owners as $new_owner) {
            $phone_numbers = $new_owner->PhoneNumbers()->get();
            array_push($owners_data, ['owner' => $new_owner, 'phone_numbers' => $phone_numbers]);
        }
        return ['pet'=>$pet,'owners'=>$owners_data];
    }
}