<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        @font-face {
            font-family: 'MicrosoftJhengHei';
            font-style: normal;
            font-weight: normal;
            src: url({{ public_path('/fonts/MicrosoftJhengHei.ttf') }}) format('truetype');
        }

        body {
            font-family: 'MicrosoftJhengHei';
        }

        .header img {
            height: 130px;
            width: auto;
        }

        .header .note {
            margin-top: 40px;
            font-size: 16px;
            line-height: 28px;
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center header">
        <img src="{{ public_path('/images/pdf_header.jpg') }}" height="130px" alt="">
        <div class="ml-3 note">
            <div>務必仔細確認訂單上每個細節</div>
            <div>出貨時會依據此份訂購單出貨</div>
        </div>
    </div>
</body>
</html>
