<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{env('APP_NAME')}}::payment</title>
</head>

<body>
    <div style="text-align:center;">

        @if ($transaction['status'])
        <h1 style="color: green;">{{__('message.success')}} </h1>
        @else
        <h1 style="color: red;">{{__('messages.not_completed')}} </h1>
        @endif
        <h3>{{$transaction['msg']}}</h3>

        <!-- <a id="btn" href="#" onclick="window.close();">Close</a> -->
    </div>
    <script>

    </script>
</body>

</html>
