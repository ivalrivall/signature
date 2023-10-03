<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        html,
        body {
            width: 100%;
            height: 100%;
        }

        canvas {
            display: block;
        }
    </style>
</head>

<body>
    <div class="container my-3">
        <div class="col-md-12">
            <div class="text-center mx-auto mb-2">
                <button class="btn btn-primary" id="save-png">Save as PNG</button>
                <button class="btn btn-danger" id="clear">Clear</button>
            </div>
        </div>
        <canvas id="signature" class="signature border border-secondary mx-auto" width="250" height="100"></canvas>
        <div class="col-md-12">
            <div class="row">
                <div class="offset-2 col-8">
                    <div class="form-group text-center">
                        <label for="url" class="col-form-label">Image URL</label>
                        <input type="text" readonly class="form-control" readonly disabled id="url"
                            value="https://">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        var canvas = document.getElementById('signature');

        function resizeCanvas() {
            var ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            // canvas.getContext("2d").scale(ratio, ratio);
        }

        window.onresize = resizeCanvas;
        resizeCanvas();

        var signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)'
        });

        document.getElementById('save-png').addEventListener('click', function() {
            if (signaturePad.isEmpty()) {
                return alert("Please provide a signature first.");
            }

            var data = signaturePad.toDataURL('image/png');

            let formData = new FormData();
            formData.append("signature", data);
            formData.append("csrf", data);

            $.ajax({
                    url: "{{ route('signature.create') }}",
                    type: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                })
                .done((e) => {
                    $('#url').val(e.image_url)
                    console.log('done', e);
                })
                .fail((e) => {
                    console.log('fail', e);
                    // Report that there is a problem!
                });

        });

        document.getElementById('clear').addEventListener('click', function() {
            signaturePad.clear();
        });
    </script>
</body>

</html>
