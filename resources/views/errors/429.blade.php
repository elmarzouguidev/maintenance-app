@extends('errors::illustrated-layout')

@section('title', __('Too Many Requests'))
{{--@section('code', '429')--}}
@section('message', __("Plusieurs requêtes détectées attendez s'il vous plaît 4 minutes pour contenu"))
