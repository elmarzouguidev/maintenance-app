@extends('theme.layouts.app')

@section('content')

<div class="container-fluid">

    <x-app.page-header
        :title="__('navbar.clients_add')"
        :breadcrumbs="[
            ['label' => __('navbar.clients'), 'route' => 'admin:clients.index'],
            ['label' => __('navbar.clients_add')],
        ]" />

    @include('theme.pages.Client.__create.form_2')

</div>

@endsection

@section('css')

@endsection

@once

    @push('scripts')
      <script src="{{asset('assets/libs/jquery.repeater/jquery.repeater.min.js')}}"></script>
      <script src="{{asset('js/pages/form-repeater.int.js')}}"></script>
    @endpush

@endonce
