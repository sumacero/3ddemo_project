<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>レベル3 逆投影シミュレーション</title>
  <style>
    body { font-family: sans-serif; padding: 16px; }
    .page-title { margin-bottom: 24px; }
    .top-actions { margin-bottom: 12px; }
    .btn-home {
      display: inline-block;
      padding: 8px 12px;
      border-radius: 8px;
      border: 1px solid #111;
      background: #111;
      color: #fff;
      text-decoration: none;
      font-size: 14px;
    }
    .btn-home:hover { background: #000; }

    .layout-root {
      display: inline-block;
      max-width: 100%;
      box-sizing: border-box;
      border: 1px solid #ddd;
      padding: 16px 16px 12px;
      border-radius: 8px;
      background: #fafafa;
      overflow: visible;
      vertical-align: top;
    }

    /* 出力セル・H/Vスライダー（トラック長は共通） */
    .layout-root {
      --cell-size: 72px;
      --slider-track-length: 184px;
      --h-cell-height: calc(var(--slider-track-length) + 48px);
      --v-cell-width: calc(var(--slider-track-length) + 108px);
    }

    .pixel-input {
      width: 40px;
      height: 40px;
      box-sizing: border-box;
      text-align: center;
    }

    .input-cell {
      position: relative;
    }

    .input-cell::before {
      content: attr(data-label);
      position: absolute;
      top: 2px;
      left: 4px;
      font-size: 10px;
      color: #444;
      line-height: 1;
      pointer-events: none;
    }

    .sim-grid {
      display: grid;
      grid-template-columns: var(--cell-size) var(--cell-size) var(--cell-size) var(--cell-size);
      grid-template-rows: var(--cell-size) var(--cell-size) var(--cell-size) var(--cell-size);
      width: fit-content;
      gap: 0;
    }

    .sim-grid-cell {
      border: 1px solid #ccc;
      width: var(--cell-size);
      height: var(--cell-size);
      min-width: var(--cell-size);
      min-height: var(--cell-size);
      box-sizing: border-box;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      line-height: 1;
      transition: background 0.2s;
    }

    .sim-grid-cell.output-cell {
      background: #fff;
    }

    table { border-collapse: collapse; table-layout: fixed; }
    td {
      border: 1px solid #ccc;
      width: var(--cell-size);
      height: var(--cell-size);
      box-sizing: border-box;
      padding: 0;
      text-align: center;
      vertical-align: middle;
      transition: background 0.2s;
      font-size: 14px;
      line-height: 1;
    }

    .grid-caption {
      margin-top: 12px;
      width: 100%;
      box-sizing: border-box;
      font-size: 12px;
      color: #666;
    }

    .legend {
      margin-top: 12px;
      width: 100%;
      max-width: none;
      box-sizing: border-box;
    }

    .legend-title {
      font-size: 12px;
      color: #333;
      margin-bottom: 6px;
    }

    .colorbar {
      width: 100%;
      height: 12px;
      border-radius: 999px;
      border: 1px solid #bbb;
      box-sizing: border-box;
      background: linear-gradient(
        90deg,
        hsl(220, 90%, 92%) 0%,
        hsl(55, 90%, 70%) 50%,
        hsl(10, 90%, 47%) 100%
      );
    }

    .legend-scale {
      display: flex;
      justify-content: space-between;
      font-size: 12px;
      color: #555;
      margin-top: 6px;
    }

    .slider-group-title {
      font-size: 13px;
      font-weight: bold;
      color: #222;
      margin-bottom: 8px;
      text-align: center;
    }

    /* 出力エリアと同じ幅にキャプション・凡例を揃える */
    .sim-grid-wrap {
      display: inline-flex;
      flex-direction: column;
      align-items: stretch;
      gap: 0;
      width: fit-content;
      max-width: 100%;
    }

    .sim-grid-top {
      display: grid;
      grid-template-columns: var(--v-cell-width) var(--cell-size) var(--cell-size) var(--cell-size);
      grid-template-rows: var(--h-cell-height);
      width: fit-content;
    }

    .sim-grid-body {
      display: grid;
      grid-template-columns: var(--v-cell-width) var(--cell-size) var(--cell-size) var(--cell-size);
      grid-template-rows: var(--cell-size) var(--cell-size) var(--cell-size);
      width: fit-content;
    }

    /* 左上角（H行とV列の交点） */
    .grid-corner {
      width: var(--v-cell-width);
      height: var(--h-cell-height);
      min-width: var(--v-cell-width);
      min-height: var(--h-cell-height);
      background: #f5f5f5;
      border: 1px solid #ccc;
      box-sizing: border-box;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 6px;
      font-size: 11px;
      line-height: 1.3;
      color: #444;
      text-align: center;
    }

    /* Hスライダー: 各出力列の真上に配置、縦スライダー（縦長セル） */
    .h-slider-cell {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 0;
      border: 1px solid #ccc;
      box-sizing: border-box;
      width: var(--cell-size);
      height: var(--h-cell-height);
      min-width: var(--cell-size);
      min-height: var(--h-cell-height);
      background: #fff;
    }

    .h-slider-vertical-wrap {
      height: var(--slider-track-length);
      width: 24px;
      margin-top: 0;
      margin-bottom: 2px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .h-slider-vertical-wrap input[type="range"] {
      width: var(--slider-track-length);
      height: 8px;
      margin: 0;
      transform: rotate(-90deg);
      transform-origin: center center;
    }

    .h-slider-cell .slider-label-text {
      font-size: 11px;
      line-height: 1;
      margin: 0;
    }
    .h-slider-cell .slider-value { font-size: 11px; color: #555; }

    /* Hセル内の数値入力（見切れ防止） */
    .h-slider-cell .pixel-input {
      width: 52px;
      min-width: 52px;
      max-width: 100%;
      height: 28px;
      padding: 2px 6px;
      font-size: 14px;
      font-variant-numeric: tabular-nums;
      line-height: 1.2;
    }

    /* Vスライダー: 各出力行の左に配置、横スライダー（横長セル） */
    .v-slider-cell {
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: center;
      gap: 6px;
      border: 1px solid #ccc;
      box-sizing: border-box;
      width: var(--v-cell-width);
      height: var(--cell-size);
      min-width: var(--v-cell-width);
      min-height: var(--cell-size);
      padding: 6px;
      background: #fff;
      overflow: visible;
    }

    .v-slider-cell label {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 11px;
      color: #333;
      flex: 1;
      min-width: 0;
    }

    .v-slider-cell input[type="range"] {
      width: var(--slider-track-length);
      min-width: var(--slider-track-length);
      max-width: var(--slider-track-length);
      height: 8px;
      flex: 0 0 var(--slider-track-length);
    }

    /* Vセル内の数値入力（2桁の見切れ防止） */
    .v-slider-cell .pixel-input {
      width: 56px;
      min-width: 56px;
      max-width: none;
      height: 28px;
      padding: 2px 8px;
      font-size: 14px;
      font-variant-numeric: tabular-nums;
      line-height: 1.2;
      flex-shrink: 0;
      box-sizing: border-box;
    }

    .v-slider-cell .slider-value { font-size: 11px; color: #555; min-width: 14px; text-align: right; }

    /* 出力セル */
    .output-cell {
      border: 1px solid #ccc;
      box-sizing: border-box;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      width: var(--cell-size);
      height: var(--cell-size);
      min-width: var(--cell-size);
      min-height: var(--cell-size);
      background: #fff;
      transition: background 0.2s;
    }
  </style>
</head>
<body>
  <div class="top-actions">
    <a class="btn-home" href="{{ url('/') }}">HOMEへ戻る</a>
  </div>
  <h1 class="page-title">レベル3 逆投影シミュレーション（3×3）</h1>

  <div class="layout-root">
    <div class="sim-grid-wrap">
      <!-- 上段: Hスライダー（出力列の真上に配置） -->
      <div class="sim-grid-top">
        <div class="grid-corner">水平カメラ（H）／垂直カメラ（V）</div>
        <div class="h-slider-cell" aria-label="H0">
          <span class="slider-label-text">H0</span>
          <div class="h-slider-vertical-wrap">
            <input id="h0-slider" type="range" min="0" max="15" step="1" value="0" aria-label="H0 スライダー" />
          </div>
          <input id="h0" class="pixel-input" type="number" min="0" max="15" value="0" aria-label="H0" />
        </div>
        <div class="h-slider-cell" aria-label="H1">
          <span class="slider-label-text">H1</span>
          <div class="h-slider-vertical-wrap">
            <input id="h1-slider" type="range" min="0" max="15" step="1" value="0" aria-label="H1 スライダー" />
          </div>
          <input id="h1" class="pixel-input" type="number" min="0" max="15" value="0" aria-label="H1" />
        </div>
        <div class="h-slider-cell" aria-label="H2">
          <span class="slider-label-text">H2</span>
          <div class="h-slider-vertical-wrap">
            <input id="h2-slider" type="range" min="0" max="15" step="1" value="0" aria-label="H2 スライダー" />
          </div>
          <input id="h2" class="pixel-input" type="number" min="0" max="15" value="0" aria-label="H2" />
        </div>
      </div>

      <!-- 本体: Vスライダー + 出力セル（各出力行の左にVスライダーを配置） -->
      <div class="sim-grid-body" id="output-table">
        <div class="v-slider-cell" aria-label="V0">
          <label for="v0-slider">
            <span class="slider-label-text">V0</span>
            <input id="v0-slider" type="range" min="0" max="15" step="1" value="0" aria-label="V0 スライダー" />
          </label>
          <input id="v0" class="pixel-input" type="number" min="0" max="15" value="0" aria-label="V0" />
        </div>
        <div class="output-cell" data-row="0" data-col="0"></div>
        <div class="output-cell" data-row="0" data-col="1"></div>
        <div class="output-cell" data-row="0" data-col="2"></div>

        <div class="v-slider-cell" aria-label="V1">
          <label for="v1-slider">
            <span class="slider-label-text">V1</span>
            <input id="v1-slider" type="range" min="0" max="15" step="1" value="0" aria-label="V1 スライダー" />
          </label>
          <input id="v1" class="pixel-input" type="number" min="0" max="15" value="0" aria-label="V1" />
        </div>
        <div class="output-cell" data-row="1" data-col="0"></div>
        <div class="output-cell" data-row="1" data-col="1"></div>
        <div class="output-cell" data-row="1" data-col="2"></div>

        <div class="v-slider-cell" aria-label="V2">
          <label for="v2-slider">
            <span class="slider-label-text">V2</span>
            <input id="v2-slider" type="range" min="0" max="15" step="1" value="0" aria-label="V2 スライダー" />
          </label>
          <input id="v2" class="pixel-input" type="number" min="0" max="15" value="0" aria-label="V2" />
        </div>
        <div class="output-cell" data-row="2" data-col="0"></div>
        <div class="output-cell" data-row="2" data-col="1"></div>
        <div class="output-cell" data-row="2" data-col="2"></div>
      </div>

      <div class="grid-caption">
        各セルは H[col] + V[row] です。
      </div>

      <div class="legend" aria-label="値と色の対応">
        <div class="legend-title">値 ↔ 色（0〜30）</div>
        <div class="colorbar" role="img" aria-label="青から赤へのカラーバー"></div>
        <div class="legend-scale" aria-hidden="true">
          <span>0</span>
          <span>15</span>
          <span>30</span>
        </div>
      </div>
    </div>
  </div>

    @vite('resources/ts/sim3.ts')
</body>
</html>
