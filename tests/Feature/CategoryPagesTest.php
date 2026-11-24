<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

/*
 * K1: the categories page (theme/pages/Category) runs on the UI kit layout (first on Tailwind in B4).
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    useFakeViteServer();

    $this->actingAs(User::factory()->create());
});

function makeCategory(string $name): Category
{
    $category = new Category;
    $category->name = $name;
    $category->slug = str($name)->slug();
    $category->save();

    return $category;
}

it('renders the categories page on the kit layout', function () {
    $category = makeCategory('Imprimantes');

    $this->get(route('admin:categories.index'))
        ->assertOk()
        ->assertSee('<title>Catégories - CASAMAINTENANCE</title>', false)
        ->assertSee('http://vite.test/resources/css/ui-kit.css', false)
        ->assertDontSee('http://vite.test/resources/css/app.css', false)
        ->assertDontSee('data-update-uri=', false)
        ->assertDontSee('legacy', false)
        ->assertSee('<h1 class="admin-page-title">Catégories</h1>', false)
        ->assertSee('Imprimantes')
        ->assertSee('action="'.route('admin:categories.delete', ['categoryId' => $category->id]).'"', false)
        ->assertSee('Supprimer la catégorie « Imprimantes » ?')
        ->assertSee('Ajouter une catégorie')
        ->assertSee('action="'.route('admin:categories.store').'"', false);
});

it('shows the empty state when there is no category', function () {
    $this->get(route('admin:categories.index'))
        ->assertOk()
        ->assertSee('Aucune catégorie')
        ->assertDontSee('admin-table', false);
});

it('lists the categories by name, fifteen per page', function () {
    foreach (range(1, 16) as $number) {
        makeCategory(sprintf('Catégorie %02d', $number));
    }

    $this->get(route('admin:categories.index'))
        ->assertOk()
        ->assertSeeInOrder(['Catégorie 01', 'Catégorie 15'])
        ->assertDontSee('Catégorie 16')
        ->assertSee('href="'.route('admin:categories.index', ['page' => 2]).'"', false);

    $this->get(route('admin:categories.index', ['page' => 2]))
        ->assertOk()
        ->assertSee('Catégorie 16')
        ->assertDontSee('Catégorie 01');
});

it('adds a category and shows the success message', function () {
    $this->followingRedirects()
        ->from(route('admin:categories.index'))
        ->post(route('admin:categories.store'), ['name' => 'Écrans', 'description' => 'Moniteurs'])
        ->assertOk()
        ->assertSee("L'ajoute a éte effectuer avec success")
        ->assertSee('Écrans');

    expect(Category::where('slug', 'ecrans')->exists())->toBeTrue();
});

it('shows the duplicate name error next to the field', function () {
    makeCategory('Onduleurs');

    $this->followingRedirects()
        ->from(route('admin:categories.index'))
        ->post(route('admin:categories.store'), ['name' => 'Onduleurs'])
        ->assertOk()
        ->assertSee('aria-invalid="true"', false);

    expect(Category::where('name', 'Onduleurs')->count())->toBe(1);
});

it('keeps the delete action as before: success message, category kept', function () {
    $category = makeCategory('Scanners');

    $this->followingRedirects()
        ->from(route('admin:categories.index'))
        ->delete(route('admin:categories.delete'), ['categoryId' => $category->id])
        ->assertOk()
        ->assertSee('La suppression a éte effectuer avec success');

    expect(Category::whereKey($category->id)->exists())->toBeTrue();
});

it('keeps the delete action when the category id comes from the list\'s action URL', function () {
    $category = makeCategory('Scanners');

    $this->followingRedirects()
        ->from(route('admin:categories.index'))
        ->delete(route('admin:categories.delete', ['categoryId' => $category->id]))
        ->assertOk()
        ->assertSee('La suppression a éte effectuer avec success');

    expect(Category::whereKey($category->id)->exists())->toBeTrue();
});
