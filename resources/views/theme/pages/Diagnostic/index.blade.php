@extends('theme.layouts.app')

@section('content')
    <div class="container-fluid">
        <x-diagnostic.workflow-layout :tickets="$tickets" />
    </div>
@endsection

<x-diagnostic.assets />
