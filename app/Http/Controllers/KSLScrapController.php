<?php
namespace App\Http\Controllers;
use Goutte\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class KSLScrapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('ksl_post_data');
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
                'title' => $crawler->filter('h1.listingDetails-title'),
                'price' => $crawler->filter('h2.listingDetails-price'),
                'features' => $crawler->filter('div.listingDescription-content'),
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
            $imgElement =  $crawler->filter('img.photoDesktop-photo');
            if ($imgElement->count() > 0) {
                // Extract the src attribute of the img tag
                $imageUrl = $imgElement->attr('src');
                $scrapedData['pic1'] = $imageUrl;
            } else {
                // Handle the case where the image is not found
                $scrapedData['pic1'] = "Image not found on the page.";
            }
            $scrapedData['price'] = str_replace('$', '', $scrapedData['price']);
            // $feature = "TDD-4-S-HC New never been used.Holds (4) 1/2 barrel keg capacity, stainless steel cabinet, solid hinged doors with locks, 115 bolts, energy star rebate available. normally sells for $6800.00 we have two extra and need to sell. Contact John 1-208-602-3256";
            $scrapedData['features'] = explode('.', $scrapedData['features']);
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
        return view('ksl_post_data')->with($data);
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
        return view('ksl_post_data')->with($data);
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
