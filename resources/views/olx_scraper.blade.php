<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>eBay Scraping</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.6.0/css/all.min.css"
        integrity="sha512-ykRBEJhyZ+B/BIJcBuOyUoIxh0OfdICfHPnPfBy7eIiyJv536ojTCsgX8aqrLQ9VJZHGz4tvYyzOM0lkgmQZGw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container-fluid" style="background-color: #2980b9; color: white">
        <div class="row justify-content-center">
            <div class="col-md-8 mb-4 mt-3">
                <form action="olx" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for=""><strong>JSON Data/URL</strong></label>
                        <textarea name="url" class="form-control" id="itemUrl" cols="30" rows="10" required
                            placeholder="Enter URL to Scrape"></textarea>
                    </div>
                    <center>
                        <button class="btn btn-lg" id="scrapeButton" style="background-color: #3498db; color: #ffffff">
                            <strong>Process</strong>
                        </button>
                    </center>
            </div>
            </form>
        </div>
    </div>
    <div class="container-fluid p-5" style="background-color: #222; color: white;min-height:500px">

        @isset($status)
            <div class="row clear_content mt-3">
                <div class="col-md-12">
                    <strong>{{ @$time }}</strong> <br>
                    <a href="{{ @$url }}"
                        style="color: #999;
                font-style: italic;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap">
                        @php
                            echo substr(@$url, 0, 50);
                        @endphp
                    </a>
                </div>
                <div class="col-md-8" id="details">

                    <div class="text-right">
                        <button class="btn btn-sm btn-dark copy_to_clip_board">
                            <i class="fa fa-copy" aria-hidden="true"></i>
                            copy
                        </button>
                        <button class="btn btn-sm btn-dark" id="downloadButton">
                            <i class="fa fa-download" aria-hidden="true"></i>
                            Download
                        </button>
                        <button class="btn btn-sm btn-dark" id="closeBtn">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-right">
                        <button class="btn btn-sm btn-dark download_html" id="downloadButton">
                            <i class="fa fa-download" aria-hidden="true"></i>
                            Copy HTML
                        </button>
                    </div>
                </div>
                <div class="col-md-8 message-container mt-3 p-3" style="background-color: #ffffff;">
                    <pre class="code textToCopy" id="textToCopy">{{ json_encode($scrapedData, JSON_PRETTY_PRINT) }}</pre>
                </div>
                <div class="col-md-4 htmlToCopy mt-3">
                    <div class="card" style="width: 23rem;color:#222">
                        <img class="card-img-top" src="{{ $scrapedData['image'] }}" alt="Card image cap">
                        <div class="card-body">
                            <span class="badge badge-info">{{ $scrapedData['condition'] }}</span>
                            <h5 class="card-title">{{ $scrapedData['title'] }}</h5>
                            <p class="card-text">{{ $scrapedData['details'] }}</p>
                            <span class="badge badge-pill badge-warning">{{ $scrapedData['price'] }}</span>
                        </div>
                    </div>

                </div>
            </div>
        @endisset
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).on('click', '.copy_to_clip_board', function() {
            $(this).closest("i").removeClass("fa-copy").addClass("fa-check");
            $(this).html('<i class="fa fa-check"></i> Copied');
            // var messageContainer = $(this).closest('.message-container');
            var textToCopy = $('.textToCopy');
            var text = textToCopy.text()
            var tempInput = $("<textarea></textarea>");
            $("body").append(tempInput);
            tempInput.val(text).select();
            document.execCommand("copy");
            tempInput.remove();
        });
        $(document).on('click', '.download_html', function() {
            $(this).closest("i").removeClass("fa-download").addClass("fa-check");
            $(this).html('<i class="fa fa-check"></i> Copied');
            // var messageContainer = $(this).closest('.message-container');
            var textToCopy = $('.htmlToCopy');
            var text = textToCopy.html()
            var tempInput = $("<textarea></textarea>");
            $("body").append(tempInput);
            tempInput.val(text).select();
            document.execCommand("copy");
            tempInput.remove();
        });
        $(document).on('click', '#closeBtn', function() {
            $('.clear_content').html('');
        })
    </script>
    <script>
        const textField = document.getElementById("textToCopy");
        const downloadButton = document.getElementById("downloadButton");
        downloadButton.addEventListener("click", function() {
            const text = textField.innerHTML;
            const filename = "text_file.txt";
            const blob = new Blob([text], {
                type: "text/plain"
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = filename;
            a.style.display = "none";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });
    </script>

</body>

</html>
