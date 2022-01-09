<?php

namespace App\Http\Controllers\Administration\Category;

use App\Http\Controllers\Controller;
use App\Repositories\Category\CategoryInterface;
use Illuminate\Http\Request;

class CategoryController extends Controller
{


    public function index()
    {
        $categories = app(CategoryInterface::class)->getCategories();
        return view('theme.pages.Category.index', compact('categories'));
    }
}
