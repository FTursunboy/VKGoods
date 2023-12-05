<?php

namespace App\Services;

use App\Http\Resources\GoodResource;
use App\Models\Category;
use App\Models\Good;
use App\Models\Price;
use App\Models\Reviews;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class GetGoods
{
    public function connect()
    {
        $accessToken = "vk1.a.n_eCSEnjkuv2V5u9QGOcVz7kIAVsXZXvyx5qJZ48q9wKk3lTgsZ4IfRA7GMUQTJ2nbJmnj5ow5My3ZNlKlYGbfJcgnzIpq1_CmWdfBd8WwqFmGWDVaHd4WFj6sU6suS1rjjD0rcQChwHra9YQn3tS6L8_Pb97vVjZ2lcjwFDLNOoiUnSAytaxQ2qzmH6jxsSw6kAvx5m-UW3ri8dksX2GA";
        $groupId = "-197845770";

        $client = new Client();

        try {
            $response = $client->get("https://api.vk.com/method/market.get", [
                'query' => [
                    'owner_id' => $groupId,
                    'access_token' => $accessToken,
                    'v' => '5.131', // Версия API
                ],
            ]);

            $data = json_decode($response->getBody(), true);



            $items = $data['response']['items'] ?? [];

            Category::query()->truncate();
            Reviews::query()->truncate();
            Price::query()->truncate();
            Good::query()->truncate();

            foreach ($items as $item) {

                $category = Category::updateOrCreate(
                    ['id' => $item['category']['id']],
                    [
                    'id' => $item['category']['id'],
                    'name' => $item['category']['name'],
                    'inner_type' => $item['category']['inner_type'],
                    'section' =>  json_encode($item['category']['section'])
                    ]
                );

                $rating = Reviews::create(
                    [
                    'rating' => $item['item_rating']['rating'],
                    'reviews_count' => $item['item_rating']['reviews_count'],
                    'reviews_count_text' => $item['item_rating']['reviews_count_text'],
                ]);

                $price = Price::create(
                    [
                        'amount' => $item['price']['amount'],
                        'currency' => json_encode($item['price']['currency']),
                        'text' => $item['price']['text']
                    ]
                );

                Good::create(
                    [
                        'id' => $item['id'],
                        'title' => $item['title'],
                        'description' => $item['description'],
                        'availability' => $item['availability'],
                        'category_id' => $category->id,
                        'price_id' => $price->id,
                        'item_rating' => $rating->id,
                        'owner_id' => $item['owner_id'],
                        'is_owner' => $item['is_owner'],
                        'date' => $item['date'],
                        'is_adult' => $item['is_adult'],
                        'thumb_photo' => $item['thumb_photo']
                    ]
                );

            }

            $urls = $this->download();

            return redirect()->back()->with($urls);
        }
        catch (\Exception $e) {
            dd($e->getMessage());
        }
}


    public function returnGoods() {
        $goods = Good::all();

        return GoodResource::collection($goods);
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
}
