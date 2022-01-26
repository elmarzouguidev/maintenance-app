@extends('theme.layouts.app')

@section('content')

<div class="container-fluid">

    @include('theme.pages.Commercial.Estimate.section_0_title')

    @include('theme.pages.Commercial.Estimate.__list.__estimates')

</div>

@endsection

@section('css')

  @livewireStyles

@endsection

@push('scripts')

 @livewireScripts
 
@endpush