<?php

namespace App\Http\Controllers;

use App\Models\Scraper;
use Illuminate\Http\Request;
use Goutte\Client;
use Illuminate\Support\Facades\Http;

class ScraperControler extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('post_data');
    }


    /**
     * Show the form for creating a new resource.
     */

    public function create(Request $request)
    {
        $url = $request->input('url');
        // Check if a URL is provided
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
                'title' => $crawler->filterXPath('//*[@id="__next"]/div/main/main/div/p[2]'),
                'price' => $crawler->filterXPath('//*[@id="__next"]/div/main/main/div/p[3]'),
                'notes' => $crawler->filterXPath('//*[@id="__next"]/div/main/main/div/p[5]'),
                /* 'warranty' => $crawler->filterXPath('//*[@id="__next"]/div/main/main/div/p[8]'),
                 'approvals' => $crawler->filterXPath('//*[@id="__next"]/div/main/main/div/p[10]'), */
            ];

            foreach ($elements as $key => $element) {
                if ($element->count() > 0) {
                    $scrapedData[$key] = $element->text();
                } else {
                    $scrapedData[$key] = '';
                }
            }
            $imgElement =  $crawler->filter('#__next > div > main > main > div > img');
            if ($imgElement->count() > 0) {
                // Extract the src attribute of the img tag
                $imageUrl = $imgElement->attr('src');
                $scrapedData['pic1'] = "https:" . $imageUrl;
            } else {
                // Handle the case where the image is not found
                $scrapedData['pic1'] = "Image not found on the page.";
            }
            $featuresContainer = $crawler->filter('div.pb-4');
            $features = $featuresContainer->filter('p')->each(function ($node) {
                return $node->text();
            });
            $scrapedData['features'] = implode(',', $features);
            $scrapedData['features'] = explode(',', $scrapedData['features']);
            $table = $crawler->filterXPath('//*[@id="__next"]/div/main/main/div/div[2]/table');
            $specificationData = [];
            $table->filter('tr')->each(function ($row) use (&$specificationData) {
                $searchReplace = array(
                    " " => "_",
                    "(in.)" => "",
                    "(lb.)" => "",
                    "-" => "_"
                );
                $key = str_replace(array_keys($searchReplace),  array_values($searchReplace), strtolower(trim($row->filter('th')->text())));
                $value = $row->filter('td')->text();
                $specificationData[$key] = $value;
            });
            $scrapedData['specification'] = $specificationData;
            $scrapedData['price'] = str_replace('$', '', $scrapedData['price']);
            $scrapedData['manufacture'] = $specificationData['manufacturer'];
            $scrapedData['src'] = $url;
            //dd(request()->all());

        }
        $response = Http::post('https://dashboard.restaurantnetworks.net/api/index.php?action=get_category');
        // Check for a successful response
        if ($response->successful()) {
            $jsonResponse = $response->json();
        } else {
            // Handle the error response
            $jsonResponse =  response()->json(['error' => 'Failed to send data to the API'], $response->status());
        }
        // Decode the JSON strings within the response
        $sts =  "success";
        $msg = "Data retrieved";
        @$fetchCategory = $jsonResponse['data'];
        $fetchListing = $scrapedData;
        $data = compact('fetchListing', 'sts', 'msg', 'fetchCategory');
        return view('post_data')->with($data);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $response = Http::post('https://dashboard.restaurantnetworks.net/api/index.php?action=add_listing_by_scraper', $data);
        // Check for a successful response
        if ($response->successful()) {
            $jsonResponse = $response->json();
        } else {
            // Handle the error response
            $jsonResponse =  response()->json(['error' => 'Failed to send data to the API'], $response->status());
        }
        // Decode the JSON strings within the response
        $status =   $jsonResponse['sts'];
        $message = $jsonResponse['msg'];
        @$postData = $jsonResponse['data'];
        $data = compact('status', 'message', 'postData');
        return view('post_data')->with($data);
    }


    /**
     * Display the specified resource.
     */
    public function show(Scraper $scraper)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scraper $scraper)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Scraper $scraper)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scraper $scraper)
    {
        //
    }
}
