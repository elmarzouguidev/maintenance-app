@extends('theme.layouts.app')

@section('content')

<div class="container-fluid">

    <x-app.page-header
        title="Catégories"
        :breadcrumbs="[['label' => 'Administration'], ['label' => 'Catégories']]" />

    @include('theme.pages.Category.__list.index')

</div>

@endsection
