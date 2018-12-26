<!doctype html>

<html lang="en">
<head>
    <title>{{ config('app.name') }}</title>
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
    </style>
</head>

<body>
<table class="table table-bordered">
    <tbody>
    <tr>
        <td>姓名</td>
        <td>{{ $order->name }}</td>
        <td>電話</td>
        <td colspan="3">{{ $order->phone }}</td>
    </tr>
    <tr>
        <td>婚期</td>
        <td>{{ $order->married_date ? $order->married_date->format('Y-m-d') : '' }}</td>
        <td>電子信箱</td>
        <td colspan="3">{{ $order->email }}</td>
    </tr>
    <tr>
        <td>訂金</td>
        <td>{{ $order->deposit ?: '' }}</td>
        <td>總金額</td>
        <td>{{ $order->total_fee + $order->deposit }}</td>
        <td>尾款</td>
        <td>{{ $order->total_fee ?: '' }}</td>
    </tr>
    <tr>
        <td>
            <div>到貨</div>
            <div>日期</div>
            <div>地址</div>
        </td>
        <td colspan="5">
            @foreach($packages as $package)
                <div>
                    <span style="color: red">{{ $package['arrived_at'] }}</span>&ensp;<span>{{ $package['case_type'] }}</span>
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
        <td>備註</td>
        <td colspan="5">
            {{ $order->remark ?: '' }}
        </td>
    </tr>
    @foreach($cases as $case)
        <tr>
            <td>盒型</td>
            <td>{{ $case['case_type_name']}}</td>
            <td>盒數</td>
            <td>{{ $case['amount'] }}</td>
            <td>單價</td>
            <td>{{ $case['price'] }}</td>
        </tr>
        <tr>
            <td>內容物</td>
            <td colspan="5">
                @foreach($case['content'] as $content)
                    <div>{{ $content }}</div>
                @endforeach
            </td>
        </tr>
    @endforeach
    <tr>
        <td>
            <div>注意</div>
            <div>事項</div>
        </td>
        <td colspan="5">
            <div>【盒數確認】務必在到貨前 15 天確認到貨盒數與日期 確認後不可再做任何更改</div>
            <div>【訂單修改】如欲修改訂單請將更改內容傳到 gameboy0711@hotmail.com 如三天未收到回覆請來電確認</div>
            <div>【到貨日期】務必要抓用餅的前一天到貨 因為貨運公司無法指定到貨時間</div>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>