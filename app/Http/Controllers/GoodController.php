<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\PriceResource;
use App\Http\Resources\ReviewResource;
use App\Models\Category;
use App\Models\Good;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $goods = Good::paginate(20);
        $categories = Category::all();
        $content = View::make('yml', compact('goods', 'products','categories'))->render();

        file_put_contents(storage_path('app/public/goods.yml'), $content);
        file_put_contents(storage_path('app/public/goods.xml'), $content);

        $yml = Storage::url('public/goods.yml');
        $xml = Storage::url('public/goods.xml');


        return ['yml' => $yml, 'xml' => $xml];


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
        $good->category_id = $request->category_id;
        $good->save();

        $this->download();

        $urls = $this->download();

        return redirect()->back()->with($urls);
    }
}
