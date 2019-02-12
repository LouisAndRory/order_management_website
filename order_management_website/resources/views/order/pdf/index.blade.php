<!doctype html>

<html lang="en">
<head>
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
            font-size: 22px;
            height: 100vh;
            padding-right: 30px;
            padding-left: 30px;
            background-image: url({{ public_path('/images/watermark.jpg') }});
            background-repeat: no-repeat;
            background-position: center 85%;
            background-size: 300px;
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

        .table td {
            border: 1px solid black;
        }

        .table td.label {
            text-align: center;
            vertical-align: middle;
            font-weight: bolder;
            white-space: nowrap;
        }

        .table td.label.top {
            vertical-align: top;
        }

        @media print {
            body {
                background-image: none;
            }
        }
    </style>
</head>

<body>

    <table class="table">
        <tbody>
            <tr>
                <td class="label">姓名</td>
                <td>{{ $order->name }}</td>
                <td class="label">電話</td>
                <td colspan="3">{{ $order->phone }}</td>
            </tr>
            <tr>
                <td class="label">婚期</td>
                <td>{{ $order->married_date ? $order->married_date->format('Y-m-d') : '' }}</td>
                <td class="label">電子信箱</td>
                <td colspan="3">{{ $order->email }}</td>
            </tr>
            <tr>
                <td class="label">訂金</td>
                <td>{{ number_format($order->deposit, 0).' 元' }}</td>
                <td class="label">總金額</td>
                <td>{{ number_format($order->total_fee + $order->deposit, 0).' 元' }}</td>
                <td class="label">尾款</td>
                <td>{{ number_format($order->total_fee ?: '', 0).' 元' }}</td>
            </tr>
            <tr>
                <td class="label">
                    <div>到貨</div>
                    <div>日期</div>
                    <div>地址</div>
                </td>
                <td colspan="5">
                    @foreach($packages as $package)
                        <div>
                            <span class="text-danger">{{ $package['arrived_at'] }}</span>&ensp;<span>{{ $package['case_type'] }}</span>
                        </div>
                        <div>
                            地址：{{ $package['address'] }}
                        </div>
                        <div>
                            備註：{{ $package['remark'] }}
                        </div>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td class="label">備註</td>
                <td colspan="5">
                    {{ $order->remark ?: '' }}
                </td>
            </tr>
            @foreach($cases as $case)
                <tr>
                    <td class="label">盒型</td>
                    <td>{{ $case['case_type_name']}}</td>
                    <td class="label">盒數</td>
                    <td>{{ $case['amount'] }}</td>
                    <td class="label">單價</td>
                    <td>{{ $case['price'] }}</td>
                </tr>
                @if(count($case['content']) > 0)
                <tr>
                    <td class="label">內容物</td>
                    <td colspan="5">
                        @foreach($case['content'] as $content)
                            <div>{{ $content }}</div>
                        @endforeach
                        @if($loop->last)
                            <div class="mb-5"></div>
                        @endif
                    </td>
                </tr>
                @endif
            @endforeach
            <tr>
                <td class="label">
                    <div>注意</div>
                    <div>事項</div>
                </td>
                <td colspan="5" style="font-size: 15px;">
                    <div>【 盒數確認 】務必在到<span class="text-danger">貨前 15 天<span>確認到貨盒數與日期 確認後不可再做任何更改</div>
                    <div>【 訂單修改 】如欲修改訂單請將更改內容傳到<span class="text-success"> gameboy0711@hotmail.com</span> 如三天未收到回覆請來電確認</div>
                    <div>【 到貨日期 】<span class="text-danger">務必要抓用餅的前一天到貨</span> 因為貨運公司無法指定到貨時間</div>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>