<?php


namespace App\Repositories\Category;


interface CategoryInterface
{

    public function getCategories();

    public function getCategory(int $id);

    public function getFirst();

    public function addCategory(array $data);

}
