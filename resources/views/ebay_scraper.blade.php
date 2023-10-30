<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Scraper</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-3">
        <center>
            <h4>ebay Data Scraper</h4>
        </center>
        <div class="row mt-3">
            <div class="col-sm-12">
                <form action="scraper" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="">Url</label>
                        <input type="url" name="url" id="" class="form-control" placeholder="Enter URL"
                            required aria-describedby="helpId">
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
    </div>
</body>

</html>
