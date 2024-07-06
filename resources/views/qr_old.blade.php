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
                <img src="{{$qrImage}}" width="300px" align="center" height="550px">

            </center>
            <center>
                <span>


                </span>
            </center>
        </div>
        <div class="col-sm-3">
            <img src="{{asset('img/app.png')}}" width="330px" style="float: right">
        </div>
    </div>
</div>
<script>
    function checkStatus() {

        var formData = {
            qr_id: '{{$qrID}}',
            action: 'checkPaymentStatus',

        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{route('payment-check')}}',
            data: {
                qr_id: '{{$qrID}}',
                action: 'checkPaymentStatus',
                "_token": "{{ csrf_token() }}",
            },
            dataType: 'json',
            encode: true,
        }).done(function (data) {
            console.log(data);
            if (data.res == 'success') {

                window.location = '{{route('success')}}';
            }

            if (data.res == 'error') {
                // checkStatus();
                window.location = "payment-failed.php?status=failed";
            }

        });
    }

    setInterval(checkStatus, 3000);

</script>

</body>
</html>
