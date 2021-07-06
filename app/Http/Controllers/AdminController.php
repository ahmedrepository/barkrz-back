<?php

namespace App\Http\Controllers;

use App\Pet;
use App\Subscribe;
use App\User;
use Illuminate\Http\Request;
use App\coupon;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index() {
        return view('home');
    }

    public function Users() {
        $users = User::where('admin',0)->get()->toArray();
        return view('users.index',[
            'users' => $users,
            'users_tab' => true,
        ]);
    }

    public function Pets() {
        $pets = Pet::where('id','!=','1')->get()->toArray();
        return view('pets.index',[
            "pets" => $pets,
            'pets_tab'=>true,
        ]);
    }

    public function MyPets(Request $request) {
        $user_id = $request->input('user_id');
        $user = User::where('id',$user_id)->get()->first();
        $pets = $user->Pets()->get()->toArray();
        return view('pets.mPets',[
            'pets' => $pets,
            'user' => $user,
        ]);
    }

    public function Pet(Request $request) {
        $pet_id = $request->input('id');
        $pet = Pet::where('id',$pet_id)->get()->first();
        $new_owners = $pet->owners()->get();
        $owners_data = [];
        foreach ($new_owners as $new_owner) {
            $phone_numbers = $new_owner->PhoneNumbers()->get();
            array_push($owners_data, ['owner' => $new_owner->toArray(), 'phone_numbers' => $phone_numbers->toArray()]);
        }

        return view('pets.view',[
            'pet'=>$pet,'owners'=>$owners_data,
            'pets_tab'=>true,
        ]);
    }

    public function SubscriberList() {
        $subscribers = Subscribe::all()->toArray();
        return view('subscribe',[
            'subscribers' => $subscribers,
            'subscriber_tab'=>true,
        ]);
    }

    public function ContactMessageView() {
        return view('email.contact',['subject'=>'subject','message'=>'message']);
    }

    public function Sample() {
        return view('');
    }

    public function Coupon() {
        $coupon = coupon::where('id',1)->get()->first();

        return view('coupon',['beta' => $coupon->beta, 'fam' => $coupon->fam, 'coupon_tab' => true,]);
    }

    public function Coupon_Save(Request $request) {
        $beta = $request->beta;
        $fam = $request->fam;
        coupon::where('id',1)->update(['beta'=>$beta,'fam'=>$fam]);
        return redirect('coupon');
    }

    public function QrUpdate(Request $request) {
        $id = $request->id;
        $qrCode = $request -> qrCode;
        $same = Pet::where('id','!=',$id)->where('identity_code',$qrCode)->get()->toArray();

        if ( count($same) > 0) {
            return redirect(route('pets.view',['id'=>$id,'error'=>'You already have this code!']));
        }

        Pet::where('id',$id)->update([
            'identity_code'=>$qrCode
        ]);

        return redirect(route('pets.view',['id'=>$id]));

    }
}
