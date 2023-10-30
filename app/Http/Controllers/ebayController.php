<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ebayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $url = $request->input('url');
        if (!$url) {
            $data = [
                "status" => false,
                "message" => "Please enter a URL",
            ];
        } else {
            $client = new Client();
            $crawler = $client->request('GET', $url);
            // Initialize an array to store the scraped data
            $scrapedData = [];
            $scrapedData['page_title'] = $crawler->filter('title')->text();
            $elements = [
                'title' => $crawler->filterXPath('//*[@id="mainContent"]/div[1]/div[1]/h1/span'),
                'condition' => $crawler->filterXPath('//*[@id="mainContent"]/div[1]/div[3]/div[2]/div[1]/div/span/span[1]/span'),
                'warranty' => $crawler->filterXPath('//*[@id="mainContent"]/div[1]/div[3]/div[2]/div[2]/span[2]'),
                'price' => $crawler->filterXPath('//*[@id="mainContent"]/div[2]/div/div[1]/div[1]/div/div[2]/div[1]/span'),
            ];
            foreach ($elements as $key => $element) {
                if ($element->count() > 0) {
                    $scrapedData[$key] = $element->text();
                } else {
                    $scrapedData[$key] = '';
                }
            }
            $imgElement =  $crawler->filter('#mainImgHldr > div.ux-image-carousel-container > div.ux-image-carousel.img-transition-medium > div.ux-image-carousel-item.active.image > div > div > img');
            if ($imgElement->count() > 0) {
                // Extract the src attribute of the img tag
                $imageUrl = $imgElement->attr('src');
                $scrapedData['image'] = "https:" . $imageUrl;
            } else {
                // Handle the case where the image is not found
                $scrapedData['image'] = "Image not found on the page.";
            }
            $data = [
                "status" => true,
                "message" => "Data Fetched successfully",
                "data" => $scrapedData
            ];
        }
        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
