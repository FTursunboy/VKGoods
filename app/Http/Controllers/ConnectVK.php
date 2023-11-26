<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ConnectVK extends Controller
{
    public function connect() {
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

            $currentPage = Paginator::resolveCurrentPage('page');
            $perPage = 20;
            $currentItems = array_slice($items, ($currentPage - 1) * $perPage, $perPage);

            $paginator = new LengthAwarePaginator(
                $currentItems,
                count($items),
                $perPage,
                $currentPage,
                ['path' => Paginator::resolveCurrentPath(), 'pageName' => 'page']
            );

            return [
                'market' => $paginator,
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }

    }
}
