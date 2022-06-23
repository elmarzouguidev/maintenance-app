<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{$title}}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin:tickets.create') }}">créer un nouveau
                            ticket</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="{{ route('admin:tickets.list') }}"> {{$title}}</a>
                    </li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
