@extends('templates.layout')
<h1>STATISTIC</h1>
<hr>
@section('content')
    @foreach($info as $k => $v)
        {{ $k }} - {{ $v }} <br>
    @endforeach
@endsection

