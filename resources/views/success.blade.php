<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>HATK</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        body {
            {{--background: url({{asset('img/background-2.png')}});--}}
   background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
            background-color: #000;

        }


        .btton {
            margin-top: 41vh;
        }
        .img{
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            height: 100vh;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row main">
        <div class="col-sm-5">
            <img src="{{asset('img/ScreenLeft_1_Main(1).png')}}" class="img" style="padding-left: 0px">
        </div>
        <div class="col-sm-3">
            <center>
                <div
                    style="background-color: #f1b82e; width:400px;height: 100px;border-radius: 20px;padding-top: 35px;margin-top: 39vh;"
                    id="hide">
                    <span style="text-align: center;font-size: 20px;font-family:'Courier New';color: white"><b>DslrBooth is now starting.......</b></span>
                </div>
                <div id="printBtn" style="display:none;">
                    <a href="{{route('home')}}">
                        <button class="btn" type="button" id="home">
                            <span style="font-family:'Courier New';font-size: 25px"><b>Go To Home</b></span>
                        </button>
                    </a>
                </div>

            </center>

        </div>
        <div class="col-sm-4">
            <center>
                <img src="{{asset('img/logo.png')}}" width="50%" align="center" class="btn" style="margin-top: 38vh;">
                <br><br>
                <br><br>
                <p style="color:  #ebebeb ; font-size: 11px;">
                    For queries, please reach us at -<br>
                    support@hatk.in or call +91 6901470088
                </p>
            </center>
            <form action="{{ route('razorpay.payment.start') }}" method="POST" id="pamentForm">
                @csrf
            </form>
        </div>
    </div>
</div>


<script>


    $(document).ready(function () {

        $.ajax({
            url: '{{route('hitdslr')}}',
            type: 'GET',
            dataType: 'JSON'
        });


        setInterval(function () {
            $("#printBtn").fadeIn();
        }, 15000);
        setTimeout(function () {
            $('#hide').fadeOut('fast');
        }, 15000);
        setTimeout(function () {
            $('#home').click();
        }, 20000);
    });

</script>
</body>
</html>
