<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

/*
 * B4: the categories page (theme/pages/Category) runs on the Tailwind layout.
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

it('renders the categories page on the Tailwind layout', function () {
    makeCategory('Imprimantes');

    $this->get(route('admin:categories.index'))
        ->assertOk()
        ->assertSee('http://vite.test/resources/css/app.css', false)
        ->assertDontSee('data-update-uri=', false)
        ->assertDontSee('legacy', false)
        ->assertSee('Categories List')
        ->assertSee('Imprimantes')
        ->assertSee('Ajouter une catégorie')
        ->assertSee('action="'.route('admin:categories.delete').'"', false)
        ->assertDontSee('javascript: void(0)', false);
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
