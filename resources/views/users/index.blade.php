@extends('layouts.app')

@section('content')
    <div class="container" style="padding-top: 100px">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> Users </h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>e-Mail</th>
                                <th>User</th>
                                <th>Membership</th>
                                <th>action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $index=>$user)
                                <tr>
                                    <td>{{$index + 1}}</td>
                                    <td>{{$user['email']}}</td>
                                    <td>{{$user['name']}}</td>
                                    <td>@if($user['membership_plan'] == 1)
                                            Monthly Membership
                                        @elseif($user['membership_plan'] == 2)
                                            Yearly Membership
                                        @else
                                            No Membership
                                        @endif
                                    </td>
                                    <td> <a href="{{route('pets.my-pets',['user_id'=>$user['id']])}}" class="btn btn-primary">My Pets</a> </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
