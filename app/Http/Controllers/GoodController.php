<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\PriceResource;
use App\Http\Resources\ReviewResource;
use App\Models\Category;
use App\Models\Good;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\Yaml\Yaml;

class GoodController extends Controller
{
    public function index()
    {
        $goods = Good::paginate(20);
        $categories = Category::get();

        return view('welcome', compact('goods', 'categories'));
    }

    public function download()
    {
        $products = Good::all();
        $categories = Category::all();
        $content = View::make('yml', compact('products', 'categories'))->render();

        file_put_contents(storage_path('goods.yml'), $content);

        return response($content, 200, [
            'Content-Type' => 'application/xml'
        ]);


    }

    public function update(Request $request, Good $good)
    {

        $price = $good->price;

        $price->amount = $request->amount * 100;

        $amount_formatted = number_format($price->amount  / 100, 0, ',', ' ');
        $text = ucfirst($amount_formatted) . ' ₽';
        $price->text = $text;
        $price->save();
        $good->price->save();

        return redirect()->back();
    }
}
