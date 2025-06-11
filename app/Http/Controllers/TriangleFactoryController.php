<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TriangleFactoryController extends Controller
{
    private function triangleApiRequest($endpoint, $params)
    {
        $base    = config('services.breachers.base');
        $token   = config('services.breachers.token');
        $apiId   = config('services.breachers.id');
        $secret  = config('services.breachers.secret');

        $paramValues = array_values($params);
        $msg = $token . '.' . implode(',', $paramValues) . '.' . $secret;
        $hash = hash('sha256', $msg);

        $headers = [
            'x-api-token' => $token,
            'x-api-auth'  => $apiId . ':' . $hash,
        ];

        $url = rtrim($base, '/') . '/' . ltrim($endpoint, '/');

        // DEBUG
        echo "<pre>";
        echo "URL: " . $url . "\n";
        echo "HEADERS:\n";
        print_r($headers);
        echo "POST BODY:\n";
        print_r($params);
        echo "</pre>";

        $response = Http::withHeaders($headers)->get($url, $params);


        if ($response->failed()) {
            throw new \Exception('API request failed: ' . $response->body());
        }

        return $response->json();
    }

    public function playerSearch(Request $request)
    {
        $players = null;

        if ($request->isMethod('post')) {
            $request->validate([
                'player_name' => 'required|string|min:3|max:100'
            ]);
            $params = ['player_name' => $request->input('player_name')];

            try {
                // Use the CORRECT endpoint here:
                $players = $this->triangleApiRequest('player/search', $params);
            } catch (\Exception $e) {
                return back()->with('error', $e->getMessage());
            }
        }

        return view('player_search', ['players' => $players]);
    }
}
