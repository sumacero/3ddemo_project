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
            .btn-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 16px; }
            .app-desc {
                margin-top: 20px;
                padding-top: 18px;
                border-top: 1px solid #e0e0e0;
                max-width: 42rem;
                font-size: 14px;
                line-height: 1.55;
                color: #333;
            }
            .app-desc-lead {
                margin: 0 0 14px;
                font-size: 15px;
                font-weight: 600;
                color: #111;
            }
            .app-desc-level {
                margin: 0 0 10px;
            }
            .app-desc-level:last-child { margin-bottom: 0; }
            .app-desc-level strong {
                display: block;
                margin-bottom: 4px;
                color: #111;
            }
            .app-desc-level p {
                margin: 0;
                color: #444;
            }
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

            <section class="app-desc" aria-label="アプリの説明">
                <p class="app-desc-lead">逆投影法を用いた3次元化シミュレーター</p>

                <div class="app-desc-level">
                    <strong>Level 1: 白黒</strong>
                    <p>デモのベースとなるシンプルな表示。</p>
                </div>
                <div class="app-desc-level">
                    <strong>Level 2: カラー</strong>
                    <p>数値の大きさを色（青〜赤）のグラデーションで表現しました。</p>
                </div>
                <div class="app-desc-level">
                    <strong>Level 3: 入力スライダー</strong>
                    <p>水平・垂直のスライダーを追加し、操作性を向上しました。</p>
                </div>
            </section>
        </div>
    </body>
</html>