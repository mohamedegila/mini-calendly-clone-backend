<!DOCTYPE html>
<html>
<head>
    <title>Egila</title>
</head>
<body>
    <h1>{{ $details['title'] }}</h1>
    <p>{{ $details['body'] }}</p>

    <div>
       <p> Date: {{ $details['date'] }}</p>
       <p> From: {{ $details['start_time'] }}</p>
       <p> To:   {{ $details['end_time'] }}</p>
       <p> Duration: {{ $details['duration'] }}</p>
    </div>
    <span>
        Zoom linl:  <a href="{{$details['link']}}" >{{ $details['link'] }}</a>
    </span>


    <p>Thank you</p>
</body>
</html>
