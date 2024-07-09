<!doctype html>
<html>
<head>

    <link rel="stylesheet" href="">
    <title>My Webpage</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<hr>
<div id="menu" style="font-size:30px  ">
    <a href="{{ route('startPage') }}">Главная</a><br>
    <a href="{{ route('startPage') }}">words</a><br>
    <a href="{{ route('training') }}">Тренировка</a><br>
    <a href="{{ route('info') }}">INFO</a><br>
    <a href="{{ route('startPage') }}">dictionary</a><br>
</div>
<hr>
<div id="content">
    @yield('content')
</div>
<div id="footer">
        <hr>
        FOOTER
        <hr>
</div>

</body>
</html>
