@extends('layouts.app')

@section('meta')
    <title> barkrz/pets </title>
@endsection

@section('content')
    <div class="container" style="padding-top: 100px">
        <div class="justify-content-center">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> Pets </h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Breed</th>
                            <th>Address</th>
                            <th>Birth Year</th>
                            <th>Weight</th>
                            <th>Medical Condition</th>
                            <th>Neutered</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pets as $index=>$pet)
                            <tr>
                                <td>{{$index + 1}}</td>
                                <td>{{$pet['name']}}</td>
                                <td>{{$pet['gender']}}</td>
                                <td>{{$pet['breed']}}</td>
                                <td>{{$pet['address']}}</td>
                                <td>{{$pet['age']}}</td>
                                <td>{{$pet['weight'].' lbs'}}</td>
                                <td>{{$pet['medicalCondition']}}</td>
                                <td>
                                    @if ($pet['gender'] == 'Male')
                                        @if ($pet['neutered'] == '1')
                                            Neutered
                                        @else
                                            Not Neutered
                                        @endif
                                    @else
                                        @if ($pet['neutered'] == '1')
                                            Spayed
                                        @else
                                            Not Spayed
                                        @endif
                                    @endif
                                </td>
                                <td><a href="{{route('pets.view',['id'=>$pet['id']])}}" class="btn btn-primary">View</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

