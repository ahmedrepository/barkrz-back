@extends('layouts.app')

@section('content')
    <div class="container" style="padding-top: 100px">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Coupon Code </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('coupon.save')}}" method="post">
                            {{ csrf_field() }}
                            <label for="beta">Barkrz BETA</label>
                            <input type="text" name="beta" value="{{$beta}}" class="form-control" />
                            <label for="fam" class="mt-2">Barkrz FAM</label>
                            <input type="text" name="fam" value="{{$fam}}"  class="form-control" />
                            <input type="submit" name="SAVE" value="Save" class="btn btn-primary mt-3"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
