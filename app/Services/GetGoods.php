<?php

namespace App\Services;

use App\Http\Resources\GoodResource;
use App\Models\Category;
use App\Models\Good;
use App\Models\Price;
use App\Models\Reviews;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class GetGoods
{
    public function getToken() {



            public function makeRequest()
            {
                $ipAddress = '185.114.245.107';
                $url = 'https://oauth.vk.com/authorize?client_id=51797475&scope=market&response_type=token';

                // Создаем экземпляр Guzzle клиента
                $client = new Client([
                    'base_uri' => $url,
                    'curl' => [CURLOPT_INTERFACE => $ipAddress],
                ]);

                try {
                    // Отправляем GET-запрос
                    $response = $client->request('GET');

                    // Получаем тело ответа
                    $body = $response->getBody()->getContents();

                    dd($body);
                    echo $body;
                } catch (\Exception $e) {
                    // Обрабатываем ошибку, если есть
                    echo 'Error: ' . $e->getMessage();
                }
            }
        }



    public function connect()
    {
        $accessToken = env('VK_ACCESS_TOKEN');
        $groupId = env('VK_GROUP_ID');

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
                            'section' => json_encode($item['category']['section'])
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
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }


    public function returnGoods()
    {
        $goods = Good::all();

        return GoodResource::collection($goods);
    }


    public function download()
    {
        $products = Good::all();
        $goods = Good::paginate(20);
        $categories = Category::all();
        $content = View::make('yml', compact('goods', 'products', 'categories'))->render();

        file_put_contents(storage_path('app/public/goods.yml'), $content);
        file_put_contents(storage_path('app/public/goods.xml'), $content);

        $yml = Storage::url('public/goods.yml');
        $xml = Storage::url('public/goods.xml');

        return ['yml' => $yml, 'xml' => $xml];

    }

    public function update(Request $request)
    {
        $accessToken = env('VK_ACCESS_TOKEN');
        $groupId = env('VK_GROUP_ID');

        $client = new Client();

        try {
            $response = $client->post("https://api.vk.com/method/market.edit", [
                'form_params' => [
                    'owner_id' => $groupId,
                    'item_id' => $this->item['id'],
                    'access_token' => $accessToken,
                    'v' => '5.131',
                    'price' => $request->price,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['error'])) {
                throw new \Exception($data['error']['error_msg']);
            }

        } catch (\Exception $e) {

        }

        return redirect()->route('platform.market');
    }
}
