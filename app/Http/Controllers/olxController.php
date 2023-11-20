<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class olxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('olx_scraper');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
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
                'title' => $crawler->filterXPath('//*[@id="body-wrapper"]/div[1]/header[2]/div/div/div/div[4]/div[1]/div[2]/div/h1'),
                'condition' => $crawler->filterXPath('//*[@id="body-wrapper"]/div[1]/header[2]/div/div/div/div[4]/div[1]/div[3]/div[2]/div[3]/div/span[2]'),
                'details' => $crawler->filter('#body-wrapper > div._1075545d.d059c029._4c726bfa._1709dcb4 > header:nth-child(3) > div > div > div > div._0a9bc591 > div.f4a99e5c > div:nth-child(4) > div._0f86855a > span'),
                'price' => $crawler->filterXPath('//*[@id="body-wrapper"]/div[1]/header[2]/div/div/div/div[4]/div[1]/div[2]/div/div[1]/div[1]/span'),
            ];
            foreach ($elements as $key => $element) {
                if ($element->count() > 0) {
                    $scrapedData[$key] = $element->text();
                } else {
                    $scrapedData[$key] = '';
                }
            }
            $imgElement =  $crawler->filter('#body-wrapper > div._1075545d.d059c029._4c726bfa._1709dcb4 > header:nth-child(3) > div > div > div > div._0a9bc591 > div.f4a99e5c > div.cf4781f0._765ea128 > div > div._852cbb9b > div.image-gallery > div > div > div > div > div.image-gallery-slide.center > picture > img');
            if ($imgElement->count() > 0) {
                // Extract the src attribute of the img tag
                $imageUrl = $imgElement->attr('src');
                $scrapedData['image'] =  $imageUrl;
            } else {
                // Handle the case where the image is not found
                $scrapedData['image'] = "Image not found on the page.";
            }
            // $data = [
            //     "scrapedData" => $scrapedData
            // ];
        }
        $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->format('F jS Y, g:i:s A');
        $status = "success";
        $message = "Data fetched successfully";
        $time = $formattedDateTime;
        // $postData =  $scrapedData;
        $show_data = compact('status', 'message', 'time', 'scrapedData', 'url');
        return view('olx_scraper')->with($show_data);
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
