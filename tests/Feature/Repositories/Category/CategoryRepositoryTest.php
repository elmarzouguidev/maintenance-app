<?php

namespace Tests\Feature\Repositories\Category;

use App\Models\Category;
use App\Repositories\Category\CategoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryRepositoryTest extends TestCase
{

    public function testCategoryRepositoryCanReturnData()
    {
         Category::factory(5)->create();

        $data= app(CategoryInterface::class)->getCategories();

        $this->assertNotEmpty($data);

        $this->assertIsObject($data);
    }
}
