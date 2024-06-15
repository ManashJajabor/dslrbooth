<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>A Photo Booth Platform</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        body {
            background: url({{asset('img/background-2.png')}});
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
            background-color: #000;
        }

        .main {
            margin: 5%;
            height: auto;
            background-color: black;
            border-radius: 20px;
        }

        .btn {
            background-color: #f1b82e;
            border-radius: 10px;
            color: #000;
            font-size: 1.4rem;
            height: 50px;
            padding: 10px;
            width: 70%;
            margin-top: 20%;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row main">
        <div class="col-sm-5">
            <img src="{{asset('img/main.png')}}" width="540px" style="padding-left: 0px">
        </div>
        <div class="col-sm-4">
            <center>
                <img src="{{asset('img/logo.png')}}" width="330px" align="center" style="margin-top: 40%">
                <br>
                <span style="font-family: Baumans,system-ui;font-size: 22px;color: white;">A Photo Booth Platform</span>
            </center>
            <center>
                <span>
                    <div
                        style="background-color: #f1b82e; width:400px;height: 100px;border-radius: 20px;padding-top: 35px"
                        id="hide">
                        <span style="text-align: center;font-size: 20px;font-family:'Courier New';color: white"><b>Now Enjoy The DslrBooth......</b></span>
                    </div>
                    <div id="printBtn" style="display:none;">
                        <form method="post" action="{{route('print')}}">
                            @csrf
                            <button class="btn" type="submit">
                                <span style="font-family:'Courier New';font-size: 25px"><b>Print</b></span>
                            </button>
                        </form>
                    </div>

                </span>
            </center>
        </div>
        <div class="col-sm-3">
            <img src="{{asset('img/app.png')}}" width="330px" style="float: right">
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        setInterval(function () {
            $("#printBtn").fadeIn();
        }, 15000);
        setTimeout(function () {
            $('#hide').fadeOut('fast');
        }, 15000);
    });
</script>

</body>
</html>
