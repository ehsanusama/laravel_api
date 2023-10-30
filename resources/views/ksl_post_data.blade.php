<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>KSL Data Scraper</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-3">
        <center>
            <h4>KSL Data Scraper</h4>
        </center>
        <div class="row mt-3">
            <div class="col-sm-12">
                <form action="kslscraper" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="">Url</label>
                        <input type="url" name="url" id="" class="form-control" placeholder="Enter URL" required aria-describedby="helpId">
                        @error('url')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <button type="submit" class="btn btn-success pull-right w-100 m-1 text-right">Scrap</button>
                        <br>
                    </div>
                </form>
            </div>
        </div>
        @isset($status)
        <div class="alert alert-{{ $status }}" role="alert">
            <p class="mb-0">{{ $message }}</p>
        </div>
        <pre class="code">{{ json_encode($postData, JSON_PRETTY_PRINT) }}</pre>
        @endisset
        @isset($sts)
        @php
        // $fetchListing['price'] = 'Call for Special pricing';
        $fetchListing['notes'] = 'Hours: Mon-Fri 10 am-5pm | Saturday by appointment | Closed Sunday
        Call Office : 801-355-3900
        PAUL CALL OR TEXT 801-573 -2788';
        $details_arry = ['MOHEBCO Restaurants-Bakeries-Cafés-Coffee Shops-Food Trucks-Retirement Homes', '929 S. 500 W', 'Salt Lake City', 'Ut', '84101 ', '', 'info@mohebcousa.com', ''];

        @endphp
        <div class="row">
            <div class="col-sm-12">
                {{-- <pre>{{ json_encode($fetchCategory, JSON_PRETTY_PRINT) }}</pre> --}}
            </div>
            <br>
            <div class="col-sm-12">

                <div class="card mt-1">
                    <div class="card-body">
                        <div class="alert alert-{{ $sts }}" role="alert">
                            <p class="mb-0">{{ $msg }}</p>
                        </div>
                        {{-- <p>{{ @$fetchListing['pic1'] }}</p> --}}

                        <center>
                            <img src="{{ @$fetchListing['pic1'] }}" alt="" width="250" height="250">
                        </center>
                        <form method="post" action="ksl_add_listing" enctype="multipart/form-data" class="ajax-form-with-file-listing">
                            @csrf
                            <input type="hidden" name="subscription_id" value="22">
                            <input type="hidden" name="user_id" value="10269">
                            <input type="hidden" name="pic1" value="{{ @$fetchListing['pic1'] }}">
                            <input type="hidden" name="src" value="{{ @$fetchListing['src'] }}">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="">Title</label>
                                        <input type="text" required name="title" value="{{ @$fetchListing['title'] }}" class="form-control" placeholder="Item Title">
                                    </div><!-- form-group -->
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="">Manufacture</label>
                                        <input type="text" placeholder="Manufacture" value="{{ @$fetchListing['manufacture'] }}" class="form-control" name="manufacture" autocomplete="off">
                                    </div><!-- form-group -->
                                </div><!-- col -->
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="">Model</label>
                                        <input type="text" placeholder="Model" value="" class="form-control" name="model" autocomplete="off">
                                    </div><!-- form-group -->
                                </div><!-- col -->
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="">Category</label>
                                        <select name="category" class="form-control" id="">
                                            <option value="" selected disabled>Select Category</option>
                                            @foreach ($fetchCategory as $val)
                                            <option value="{{ $val['id'] }}">{{ strtoupper($val['name']) }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div><!-- form-group -->
                                </div>
                            </div><!-- row -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="">Feature(s)</label>
                                        @php
                                        $features = $fetchListing['features'];
                                        @endphp

                                        @for ($i = 0; $i < 5; $i++) <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon3">Feature
                                                    {{ $i + 1 }}</span>
                                            </div>
                                            <input type="text" class="form-control" value="{{ isset($features[$i]) ? $features[$i] : '' }}" name="features[]" id="basic-url" aria-describedby="basic-addon3">
                                    </div>
                                    @endfor
                                </div><!-- form-group -->
                                <div class="form-group">
                                    <label for="">Miscellaneous Notes</label>
                                    <textarea name="notes" id="" cols="30" rows="5" placeholder="Any Miscellaneous Notes (Optional)" class="form-control">{{ @strip_tags(nl2br($fetchListing['notes'])) }}</textarea>
                                </div>
                            </div><!-- col -->
                            <div class="col-sm-6">
                                <label for="">Detail(s)</label>
                                @php
                                $listing_detail_array = ['company', 'address', 'city_name', 'state_name', 'zip', 'phone', 'email', 'contact'];
                                @endphp
                                @foreach ($listing_detail_array as $key => $detail)
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3">{{ ucwords($detail) }}</span>
                                    </div>
                                    <input type="text" value="{{ @$details_arry[$key] }}" class="form-control" name="{{ strtolower($detail) }}" id="basic-url" aria-describedby="basic-addon3">
                                </div>
                                @endforeach

                            </div><!-- col -->
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="">Price ($)</label>
                                    <input type="text" placeholder="$$$" value="{{ @$fetchListing['price'] }}" class="form-control" name="price">
                                </div><!-- form-group -->
                            </div><!-- col -->
                    </div><!-- row -->
                    <br>
                    <button class="btn btn-primary w-100" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endisset
    </div>
</body>

</html>