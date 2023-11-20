<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class byAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('bayarea_post_data');
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
                'title' => $crawler->filterXPath('/html/body/div[3]/div[2]/div/div[2]/div[2]/div/h1'),
                'price' => $crawler->filter('body > div.elementor.elementor-329560.elementor-location-single.post-589222.product.type-product.status-publish.pwb-brand-vollrath.product_cat-chafing-dishes.product_cat-chafing-dishes-vollrath.product_cat-concessions-food-service.product_cat-equipment-supplies.product_cat-vendors.product_cat-vollrath.product_shipping_class-ltl_freight.first.instock.taxable.shipping-taxable.purchasable.product-type-simple.product > div.elementor-element.elementor-element-e70a019.e-flex.e-con-boxed.e-con.e-parent > div > div.elementor-element.elementor-element-fa714c3.e-con-full.e-flex.e-con.e-child > div.elementor-element.elementor-element-681c287.elementor-widget.elementor-widget-woocommerce-product-price > div > p'),
            ];

            foreach ($elements as $key => $element) {
                if ($element->count() > 0) {
                    $scrapedData[$key] = $element->text();
                } else {
                    $scrapedData[$key] = '';
                }
            }
            $featuresContainer = $crawler->filter('body > div.elementor.elementor-329560.elementor-location-single.post-589222.product.type-product.status-publish.pwb-brand-vollrath.product_cat-chafing-dishes.product_cat-chafing-dishes-vollrath.product_cat-concessions-food-service.product_cat-equipment-supplies.product_cat-vendors.product_cat-vollrath.product_shipping_class-ltl_freight.first.instock.taxable.shipping-taxable.purchasable.product-type-simple.product > div.elementor-element.elementor-element-e70a019.e-flex.e-con-boxed.e-con.e-parent > div > div.elementor-element.elementor-element-fa714c3.e-con-full.e-flex.e-con.e-child > div.elementor-element.elementor-element-9265164.elementor-widget.elementor-widget-woocommerce-product-short-description > div > div');
            $features = $featuresContainer->filter('li')->each(function ($node) {
                return $node->text();
            });
            $scrapedData['features'] = implode(',', $features);
            $scrapedData['features'] = explode(',', $scrapedData['features']);
            $imgElement =  $crawler->filter('body > div.elementor.elementor-329560.elementor-location-single.post-589222.product.type-product.status-publish.pwb-brand-vollrath.product_cat-chafing-dishes.product_cat-chafing-dishes-vollrath.product_cat-concessions-food-service.product_cat-equipment-supplies.product_cat-vendors.product_cat-vollrath.product_shipping_class-ltl_freight.first.instock.taxable.shipping-taxable.purchasable.product-type-simple.product > div.elementor-element.elementor-element-e70a019.e-flex.e-con-boxed.e-con.e-parent > div > div.elementor-element.elementor-element-015bf09.e-con-full.e-flex.e-con.e-child > div > div > div > div > div > img');
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
        return view('bayarea_post_data')->with($data);
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
        return view('bayarea_post_data')->with($data);
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
