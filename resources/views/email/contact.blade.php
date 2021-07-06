<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon-->
    <!-- Author Meta -->
    <meta name="author" content="codepixer">
    <!-- Meta Description -->
    <meta name="description" content="">
    <!-- Meta Keyword -->
    <meta name="keywords" content="">
    <!-- meta character set -->
    <meta charset="UTF-8">
    <!-- Site Title -->
    <title>Ruft Blog</title>


    <style>
        /*
    ################
                   Email page style
    ################
    */
        body{
            background-color: #9600b3;
        }
        .section-gap {
            padding: 70px; }
        @media (max-width: 419px) {
            .section-gap {
                padding: 20px 0px; } }
        @media (min-width: 420px) and (max-width: 767px) {
            .section-gap {
                padding: 20px 0; } }
        .container {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }
        @media (min-width: 576px) {
            .container {
                max-width: 540px;
            }
        }

        @media (min-width: 768px){
            .container {
                max-width: 720px;
            }
        }

        @media (min-width: 992px){
            .container {
                max-width: 960px;
            }
        }

        #email_section {
            margin-top: -70px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat; }
        @media (max-width: 420px) {
            #email_section {
                margin-top: -20px; } }
        #email_section #email_detail_home {
            position: relative;
            background-color: #fff;
            border-radius: 15px; }
        #email_section #email_detail_home img {
            max-height: 35px; }
        @media (max-width: 420px) {
            #email_section #email_detail_home img {
                max-height: 30px; } }
        #email_section #email_detail_home hr {
            border: solid 1.2px #9600b3; }
        #email_section #email_detail_home #email_footer {
            margin-top: 20rem;
            padding-bottom: 20px; }
        #email_section #email_detail_home #email_footer p {
            font-size: 14px; }
        @media (max-width: 420px) {
            #email_section #email_detail_home #email_footer {
                margin-top: 5rem; } }
        #email_section #email_detail_home p {
            font-family: 'open-sans';
            font-style: normal;
            font-size: 18px;
            line-height: 1.3;
        }
        @media (max-width: 420px) {
            #email_section #email_detail_home {
                display: block !important;
                margin-top: 20px !important; } }
        .mt-70 {
            margin-top: 70px;
        }

        .text-center {
            text-align: center !important;
        }

        .align-items-center {
            -ms-flex-align: center !important;
            align-items: center !important;
        }

        .justify-content-center {
            -ms-flex-pack: center !important;
            justify-content: center !important;
        }

        .row {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }
        .mt-15 {
            margin-top: 15px;
        }

        @media (min-width: 768px){
            .col-md-4 {
                -ms-flex: 0 0 33.333333%;
                flex: 0 0 33.333333%;
                max-width: 33.333333%;
            }
        }

        @media (min-width: 576px){
            .col-sm-10 {
                -ms-flex: 0 0 83.333333%;
                flex: 0 0 83.333333%;
                max-width: 83.333333%;
            }
        }

        .col-10 {
            -ms-flex: 0 0 83.333333%;
            flex: 0 0 83.333333%;
            max-width: 83.333333%;
        }

        .save-btn {
            background: #9600b3;
            color: #fff;
            line-height: 24px;
            border-radius: 25px;
            border: 1px solid #9600b3;
            display: inline-block;
            padding: 5px 30px;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            position: relative;
        }

        .save-btn:hover {
            background: #fff;
            color: #9600b3;
        }

        .text-left {
            text-align: left !important;
        }

        a, a:focus, a:hover {
            color: #9600b3;
            font-family: 'open-sans';
            font-weight: normal;
            font-size: 17px;
            text-decoration: none;
            outline: 0;
        }

        h3 {
            font-size: 24px;
        }

        h1, h2, h3, h4, h5, h6 {
            line-height: 1.2em;
        }
    </style>
</head>

<body>
<!-- Start main body Area -->
<div id="email_section" class="main-body section-gap">
    <div class="container box_1170">
        <div id="email_detail_home" class="mt-70">
            <div class="row align-items-center justify-content-center text-center">
                <div class="col-md-10 col-sm-10 col-10 text-left mt-20">
                    <h3>{{$subject}}</h3>
                    <p class="mt-20">
                        {{$bodyMessage}}
                    </p>
                </div>
            </div>
            <div id="email_footer" class="row align-items-center justify-content-center text-center">
                <div class="col-md-12">
                    <p>Copyright ©2020 contact@barkrz.com</p>
                    <p>All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

</html>