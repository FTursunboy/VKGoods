<?php

namespace App\Services;

use App\Http\Resources\GoodResource;
use App\Models\Category;
use App\Models\Good;
use App\Models\Price;
use App\Models\Reviews;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class GetGoods
{
    public function connect()
    {
        $accessToken = "vk1.a.3CJJylQcILEqjyortfCFftnll8aBUsqRKoAgCDBBN-fFej_8iE13L9YzSOdhN9NJ5wgDdwK9_GJD8mR2o3MJSlQMT1z_jDKdg9zAS6-PpWHvDQ-kNpfFzcI66bbavzXPBYZyhM4FRl3JsPz77a7PXWRPtLZ_KxA7GGk0GywImLyGg2xSMgr8a6yvEWBTKbRCO-9_uJ5ds2hd95pLG_6DVw";
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


            foreach ($items as $item) {

                $category = Category::create(
                    [
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

                Good::updateOrCreate(
                    ['id' => $item['id']],
                    [
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

        }
        catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
}


    public function returnGoods() {
        $goods = Good::all();

        return GoodResource::collection($goods);
    }
}
