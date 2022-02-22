@extends('theme.layouts.app')

@section('content')

    <div class="container-fluid">

        @include('theme.pages.Commercial.Invoice.section_0_title')

        @include('theme.pages.Commercial.Invoice.__edit.__form_edit')

    </div>

@endsection

@section('css')

    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"
          type="text/css">
    <style>
        .select2-selection__choice {
            background: #f00;
            color: #0000ff;
            font-size: 16px;
        }
    </style>

@endsection

@once

@push('scripts')
    <script src="{{ asset('assets/libs/jquery.repeater/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('js/pages/form-repeater.int.js') }}"></script>
    <script src="{{asset('js/pages/select_2_init.js')}}"></script>

    <script>

        $('.ticketers').hover(function () {

            var optionSelected = $(this).find('option:selected');
            // var optionSelectedUrl = $(this).find('option:selected').attr('data-url');
            // console.log(optionSelected);//this will show the value of the atribute of that option.

            //  window.location = optionSelected.attr('data-url');

        });

    </script>

    <script>
        $(".deleteArticle").click(function (event) {
            event.preventDefault();

            var result = confirm('Are you sure you want to delete this record?');

            var article = $(this).data("article");
            var invoice = $(this).data("invoice");
            var token = $("meta[name='csrf-token']").attr("content");

            if (result) {

                $.ajax({
                    url: "{{ route('commercial:invoices.delete.article') }}",
                    type: 'DELETE',
                    data: {
                        "article": article,
                        "invoice": invoice,
                        "_token": token
                    },
                    success: function () {
                        console.log("it Works");
                        $("#articles_list").load(window.location.href + " #articles_list");
                        window.location.reload();
                    }
                });
            }

        });
    </script>

@endpush

@endonce
