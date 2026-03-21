<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Home</title>
        <style>
            body { font-family: sans-serif; padding: 24px; }
            .card {
                display: inline-block;
                border: 1px solid #ddd;
                border-radius: 10px;
                padding: 18px 20px;
                background: #fafafa;
            }
            .title { margin: 0 0 12px; font-size: 18px; }
            .btn {
                display: inline-block;
                padding: 10px 14px;
                border-radius: 8px;
                border: 1px solid #111;
                background: #111;
                color: #fff;
                text-decoration: none;
            }
            .btn:hover { background: #000; }
            .btn-row { display: flex; gap: 10px; flex-wrap: wrap; }
        </style>
    </head>
    <body>
        <div class="card">
            <h1 class="title">逆投影シミュレーション</h1>
            <div class="btn-row">
                <a class="btn" href="{{ url('/sim') }}">レベル1を開く</a>
                <a class="btn" href="{{ url('/sim2') }}">レベル2を開く</a>
                <a class="btn" href="{{ url('/sim3') }}">レベル3を開く</a>
            </div>
        </div>
    </body>
</html>