@extends('layouts.app')

@section('meta')
    <title> barkrz/pets </title>
@endsection

@section('content')
    <?php
        $qrCode = 'http://www.barkrz.com/a?p='.$pet->identity_code;
    ?>
    <div class="container" style="padding-top: 100px">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-widget widget-user-2" style="background-color: rgb(244 255 219);">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="card-footer" style="padding: 0 0 20px 0;">
                        <div class="pet_image">
                            <img class="img-circle elevation-2" src="{{$pet->image}}" alt="Pet Avatar">
                            <span> {{$pet->name}}</span>
                            <span>{{$pet->breed}}</span>
                        </div>
                        <div class=" pet_info">
                            <div>
                                Gender:
                            </div>
                            <div>
                                {{$pet->gender}}
                            </div>
                        </div>
                        <div class="pet_info">
                            <div>
                                Address:
                            </div>
                            <div>
                                {{$pet->address}}
                            </div>
                        </div>
                        <div class="pet_info">
                            <div>
                                Birth Year:
                            </div>
                            <div>
                                {{$pet->age}}
                            </div>
                        </div>
                        <div class="pet_info">
                            <div>
                                Weight:
                            </div>
                            <div>
                                {{$pet->weight.' lbs'}}
                            </div>
                        </div>
                        <div class="pet_info">
                            <div>
                                Medical Condition:
                            </div>
                            <div>
                                {{$pet->medicalCondition}}
                            </div>
                        </div>
                        <div class="pet_info">
                            <div>
                                Neutered:
                            </div>
                            <div>
                                @if ($pet->gender == 'Male')
                                    @if ($pet->neutered == '1')
                                        Neutered
                                    @else
                                        Not Neutered
                                    @endif
                                @else
                                    @if ($pet->neutered == '1')
                                        Spayed
                                    @else
                                        Not Spayed
                                    @endif
                                @endif

                            </div>
                        </div>
                        <div class="pet_info">
                            <div>
                                Temperament:
                            </div>
                            <div>
                                <?php
                                    $temperament = $pet->temperament;
                                    $temperaments = ['Friendly','With Kids','With Dogs','With Cats','Skittish',
                                                'Aggressive','Calm','Playful'];
                                    $cnt = 0;
                                    for ($i = 0 ; $i < 8; $i ++) {
                                        if ($temperament[$i] == '1') {
                                            if ($cnt == 0) {
                                                echo $temperaments[$i]." ";
                                            }
                                            else {
                                                echo ", ".$temperaments[$i]." ";
                                            }
                                            $cnt += 1;
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <div style="text-align: center; font-size: 30px; height: 50px" >
                            <span class="mt-auto mb-auto">Owners</span>
                        </div>
                        @foreach($owners as $owner)
                        <div class="pet_info">
                            <div>
                                {{$owner['owner']['name']}}:
                            </div>
                            <div style="display: flex; flex-wrap: wrap; flex-direction: column">
                                @foreach($owner['phone_numbers'] as $phone_number)
                                    <span>{{$phone_number['phone_number']}} &nbsp;&nbsp;</span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                        <div style="text-align: center; font-size: 30px; height: 50px" >
                            <span class="mt-auto mb-auto">Qr Code</span>
                        </div>
                        <div>
                            <div class="visible-print text-center">
                                {!! QrCode::size(200)->backgroundColor(244, 255, 219)->generate($qrCode); !!}
                                <form method="post" action="{{route('qr.update')}}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{$pet->id}}">
                                    <span>
                                        {{$qrCode}}
                                    </span>
                                    <input class="form-control ml-auto mr-auto mt-2 mb-2" style="width: 200px" name="qrCode" value="{{$pet->identity_code}}">
                                    @if (isset($_REQUEST['error']))
                                        <div class="text-red">
                                            <strong>{{ $_GET['error'] }}</strong>
                                        </div>
                                    @endif
                                    <button class="btn btn-primary" type="submit"> Update </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
