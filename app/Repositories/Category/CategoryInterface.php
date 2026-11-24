<?php

namespace App\Repositories\Category;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CategoryInterface
{
    public function getCategories(array $colunms = []);

    /**
     * One page of categories, sorted by name.
     *
     * @return LengthAwarePaginator<int, Category>
     */
    public function paginateCategories(int $perPage);

    public function getCategory(int $id);

    public function getFirst();

    public function addCategory(array $data);
}
