{{--
    Backups (K2): the backup manager (admin:backup:manager.index, SuperAdmin), formerly the Skote
    file manager page. Same actions: create a backup (admin:backup:make), browse the local backups
    or a cloud disk (?disk=google / dropbox), download (admin:backup:download) and delete
    (admin:backup:delete) a file.
--}}
@extends('layouts.admin')

@section('title', 'Sauvegardes')

@section('content')
    @php
        $disk = request()->input('disk');
        $disks = [null => 'Ce serveur', 'google' => 'Google Drive', 'dropbox' => 'Dropbox'];
    @endphp

    <div class="space-y-6">
        <x-admin.page-header title="Sauvegardes" description="Les sauvegardes de la base de données, les plus récentes en premier.">
            <x-slot:actions>
                <form method="POST" action="{{ route('admin:backup:make') }}">
                    @csrf
                    <x-admin.button type="submit" icon="plus">Créer une sauvegarde</x-admin.button>
                </form>
            </x-slot:actions>
        </x-admin.page-header>

        <nav class="flex flex-wrap gap-2" aria-label="Emplacement">
            @foreach ($disks as $key => $label)
                @php($isActive = (string) $disk === (string) $key)
                <x-admin.button href="{{ route('admin:backup:manager.index', $key ? ['disk' => $key] : []) }}" size="sm" :variant="$isActive ? 'primary' : 'secondary'" :aria-current="$isActive ? 'page' : null">{{ $label }}</x-admin.button>
            @endforeach
        </nav>

        <section aria-labelledby="backups-title" class="space-y-4">
            <h2 id="backups-title" class="sr-only">Fichiers de sauvegarde</h2>

            @if (empty($files))
                <div class="admin-card">
                    <x-admin.empty-state icon="download" title="Aucune sauvegarde" description="Aucun fichier de sauvegarde à cet emplacement." />
                </div>
            @else
                <x-admin.table :headers="['Fichier', 'Date', 'Taille', '']">
                    @foreach ($files as $file)
                        <tr>
                            <td class="whitespace-normal font-medium">{{ $file['path'] }}</td>
                            <td class="tabular-nums">{{ $file['date'] }}</td>
                            <td class="tabular-nums">{{ $file['size'] }}</td>
                            <td class="text-end">
                                <div class="flex items-center justify-end gap-2">
                                    <form method="POST" action="{{ route('admin:backup:download') }}">
                                        @csrf
                                        <input type="hidden" name="fileName" value="{{ $file['path'] }}">
                                        <x-admin.button type="submit" size="sm" variant="secondary" icon="download">Télécharger</x-admin.button>
                                    </form>
                                    <x-admin.delete-action :action="route('admin:backup:delete', array_filter(['fileName' => $file['path'], 'diskName' => $disk]))"
                                        :confirm="'Supprimer la sauvegarde '.$file['path'].' ?'" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-admin.table>
            @endif
        </section>
    </div>
@endsection
