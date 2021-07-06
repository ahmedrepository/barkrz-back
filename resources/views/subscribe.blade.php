@extends('layouts.app')

@section('content')
    <div class="container" style="padding-top: 100px">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> Subscribers </h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>email</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($subscribers as $index => $subscriber)
                                <tr>
                                    <td>{{$index + 1}}</td>
                                    <td>{{$subscriber['email']}}</td>
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
