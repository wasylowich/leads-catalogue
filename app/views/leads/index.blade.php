@extends('layouts.master')

@section('content')

    @if (!empty($leads))
        <ul>
            @foreach ($leads as $lead)
                <li>{{ $lead->name }} | {{ $lead->email }}</li>
            @endforeach
        </ul>
    @else
        <p>No leads found</p>
    @endif

    <hr>

    {{ Form::open(['url' => 'foo/bar', 'method' => 'post']) }}
        {{ Form::label('name', 'Name'); }}
        {{ Form::text('name'); }}

        {{ Form::label('email', 'E-Mail Address'); }}
        {{ Form::email('email'); }}

        {{ Form::submit('Subscribe')}}
    {{ Form::close() }}

@stop
