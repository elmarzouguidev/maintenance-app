<?php

use Illuminate\Pagination\LengthAwarePaginator;

/*
 * The paginator view of the UI kit pages (resources/views/vendor/pagination/admin.blade.php).
 * Rendering only: no database.
 */

function adminPaginator(int $total, int $currentPage): LengthAwarePaginator
{
    return new LengthAwarePaginator(
        items: array_fill(0, min(15, max(0, $total - ($currentPage - 1) * 15)), 'ligne'),
        total: $total,
        perPage: 15,
        currentPage: $currentPage,
        options: ['path' => 'http://localhost/app/categories'],
    );
}

it('renders nothing when everything fits on one page', function () {
    $view = $this->blade('{{ $paginator->links("pagination::admin") }}', ['paginator' => adminPaginator(12, 1)]);

    expect(trim((string) $view))->toBe('');
});

it('disables the previous button and marks the current page on the first page', function () {
    $view = $this->blade('{{ $paginator->links("pagination::admin") }}', ['paginator' => adminPaginator(40, 1)]);

    $view->assertSeeInOrder(['1', '–', '15', 'sur', '40'], false)
        ->assertSee('aria-label="Page précédente" title="Page précédente" class="admin-icon-button admin-icon-button-sm" disabled', false)
        ->assertSee('aria-current="page"', false)
        ->assertSee('Page 1 sur 3')
        ->assertSee('href="http://localhost/app/categories?page=2"', false)
        ->assertSee('rel="next"', false);
});

it('disables the next button on the last page', function () {
    $view = $this->blade('{{ $paginator->links("pagination::admin") }}', ['paginator' => adminPaginator(40, 3)]);

    $view->assertSeeInOrder(['31', '–', '40', 'sur', '40'], false)
        ->assertSee('rel="prev"', false)
        ->assertSee('aria-label="Page suivante" title="Page suivante" class="admin-icon-button admin-icon-button-sm" disabled', false)
        ->assertDontSee('rel="next"', false);
});
