@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="single-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'single-title', 'title' => 'Image with the current one', 'text' => 'The native file input covers the drop zone (click, keyboard and drop all work) and keeps its name. Chosen images preview below the zone.'])
        <div class="admin-card p-5 sm:p-6">
{{-- snippet:single --}}
<x-admin.file-upload name="logo" label="Logo" preview preview-url="../assets/brand/logo-mark.svg" help="Affiché sur les documents et dans l’en-tête." />
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'single'])
    </section>

    <section aria-labelledby="multiple-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'multiple-title', 'title' => 'Documents, with an error', 'text' => 'hint replaces the default format line when the accepted types differ. One upload per page: the preview script looks up the first preview area.'])
        <div class="admin-card p-5 sm:p-6">
{{-- snippet:multiple --}}
<x-admin.file-upload name="error_document" label="Justificatifs" accept="image/*,application/pdf" multiple max-size="5 Mo" hint="Images ou PDF, 5 Mo au plus par fichier." required />
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'multiple'])
    </section>
@endsection
