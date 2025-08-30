@extends('theme.layouts.app')

@section('content')

<div class="container-fluid">

    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                    <i class="bx bx-wrench me-2"></i>
                    Traitement de ticket {{ $ticket->code }}
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin:tickets.list') }}">Tickets</a>
                        </li>
                        <li class="breadcrumb-item active">Traitement de ticket {{ $ticket->code }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @include('theme.pages.Ticket.__diagnostic.detail')

</div>

@endsection

@section('css')
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@once

    @push('scripts')

       <script src="{{ asset('assets/libs/magnific-popup/jquery.magnific-popup.min.js') }}"></script>

      <script src="{{ asset('js/pages/lightbox.init.js') }}"></script>

      <script src="{{asset('assets/libs/tinymce/tinymce.min.js')}}"></script>
      <script src="{{asset('js/pages/ticket-create.init.js')}}"></script>

      <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

      <script>
          //Warning Message
          $('#sendTicketReport').click(function () {
              Swal.fire({
                  title: "Est-ce que vous êtes sûr ?",
                  text: "vous ne pouvez pas modifier le rapport autre fois !",
                  icon: "info",
                  showCancelButton: true,
                  confirmButtonColor: "#34c38f",
                  cancelButtonColor: "#f46a6a",
                  confirmButtonText: "Oui, envoyer le!"
              }).then(function (result) {
                  if (result.value) {

                      Swal.fire("Envoyé!", "Le Rapport est envoyé avec succès.", "success");

                      setTimeout(function () {
                          document.getElementById('TicketReportForm').submit();
                      }, 2000);
                  }
              });
          });
      </script>
    @endpush

@endonce
