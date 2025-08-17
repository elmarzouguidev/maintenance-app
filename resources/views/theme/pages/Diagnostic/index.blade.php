@extends('theme.layouts.app')

@section('content')
<div class="container-fluid">
    @include('theme.pages.Diagnostic.section_0_page_title')
    
    <x-diagnostic.diagnostic-layout :tickets="$tickets" />
</div>
@endsection

<x-diagnostic.assets />