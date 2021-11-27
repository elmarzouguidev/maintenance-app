<?php

namespace App\Http\Controllers\Site;

use App\Domain\Support\SaveModel\SaveModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Category\CategoryFormRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{


    public function index()
    {
        return view('form-1');
    }

    public function create(CategoryFormRequest $request): string
    {

        $data = $request->except('_token');

        $data['slug'] = Str::slug($request->name);
        $data['is_published'] = false;
        (new SaveModel(new Category(),$data))->execute();

        return 'Yes';
    }
}
