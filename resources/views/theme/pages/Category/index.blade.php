{{--
    Categories (K1): the UI kit list pattern, with the add form beside the list on large screens.
    The delete action posts categoryId in the query string (x-admin.delete-action has no extra
    fields); CategoryController::delete() reads it from the request like the former hidden field.
--}}
@extends('layouts.admin')

@section('title', 'Catégories')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Catégories" description="Les catégories classent les clients et les fournisseurs." />

        <div class="grid gap-6 lg:grid-cols-3">
            <section aria-labelledby="categories-title" class="min-w-0 space-y-4 lg:col-span-2">
                <h2 id="categories-title" class="sr-only">Liste des catégories</h2>

                @if ($categories->isEmpty())
                    <div class="admin-card">
                        <x-admin.empty-state icon="squares" title="Aucune catégorie" description="Ajoutez la première catégorie avec le formulaire." />
                    </div>
                @else
                    <x-admin.table :headers="['N°', 'Nom', 'Publiée', '']">
                        @foreach ($categories as $category)
                            <tr>
                                <td class="text-fg-muted tabular-nums">{{ $category->id }}</td>
                                <td class="font-medium">{{ $category->name }}</td>
                                <td>{{ $category->is_published }}</td>
                                <td class="text-end">
                                    <x-admin.delete-action :action="route('admin:categories.delete', ['categoryId' => $category->id])"
                                        :confirm="'Supprimer la catégorie « '.$category->name.' » ?'" />
                                </td>
                            </tr>
                        @endforeach
                    </x-admin.table>

                    {{ $categories->links('pagination::admin') }}
                @endif
            </section>

            <section aria-labelledby="category-form-title" class="min-w-0">
                <form method="POST" action="{{ route('admin:categories.store') }}" class="admin-card space-y-6 p-5 sm:p-6">
                    @csrf

                    <h2 id="category-form-title" class="admin-section-title">Ajouter une catégorie</h2>

                    <div class="grid gap-4">
                        <x-admin.form-input name="name" label="Nom" required />
                        <x-admin.textarea name="description" label="Description" rows="4" />
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                        <x-admin.button type="submit">Enregistrer</x-admin.button>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
