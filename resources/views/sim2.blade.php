<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>レベル2 逆投影シミュレーション</title>
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
      border: 1px solid #ddd;
      padding: 16px 16px 12px;
      border-radius: 8px;
      background: #fafafa;
      max-width: 100%;
      overflow: visible;
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

    table { border-collapse: collapse; table-layout: fixed; }
    td {
      border: 1px solid #ccc;
      width: 40px;
      height: 40px;
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
      font-size: 12px;
      color: #666;
    }

    .legend {
      margin-top: 12px;
      width: 100%;
      max-width: 220px;
    }

    .legend-title {
      font-size: 12px;
      color: #333;
      margin-bottom: 6px;
    }

    .colorbar {
      height: 12px;
      border-radius: 999px;
      border: 1px solid #bbb;
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
  </style>
</head>
<body>
  <div class="top-actions">
    <a class="btn-home" href="{{ url('/') }}">HOMEへ戻る</a>
  </div>
  <h1 class="page-title">レベル2 逆投影シミュレーション（3×3）</h1>

  <div class="layout-root">
    <table id="output-table">
      <tbody>
        <tr>
          <td></td>
          <td class="input-cell" data-label="H0">
            <input
              id="h0"
              class="pixel-input"
              type="number"
              min="0"
              max="15"
              value="0"
              aria-label="H0"
            />
          </td>
          <td class="input-cell" data-label="H1">
            <input
              id="h1"
              class="pixel-input"
              type="number"
              min="0"
              max="15"
              value="0"
              aria-label="H1"
            />
          </td>
          <td class="input-cell" data-label="H2">
            <input
              id="h2"
              class="pixel-input"
              type="number"
              min="0"
              max="15"
              value="0"
              aria-label="H2"
            />
          </td>
        </tr>
        <tr>
          <td class="vertical-cell input-cell" data-label="V0">
            <input
              id="v0"
              class="pixel-input"
              type="number"
              min="0"
              max="15"
              value="0"
              aria-label="V0"
            />
          </td>
          <td data-row="0" data-col="0"></td>
          <td data-row="0" data-col="1"></td>
          <td data-row="0" data-col="2"></td>
        </tr>
        <tr>
          <td class="vertical-cell input-cell" data-label="V1">
            <input
              id="v1"
              class="pixel-input"
              type="number"
              min="0"
              max="15"
              value="0"
              aria-label="V1"
            />
          </td>
          <td data-row="1" data-col="0"></td>
          <td data-row="1" data-col="1"></td>
          <td data-row="1" data-col="2"></td>
        </tr>
        <tr>
          <td class="vertical-cell input-cell" data-label="V2">
            <input
              id="v2"
              class="pixel-input"
              type="number"
              min="0"
              max="15"
              value="0"
              aria-label="V2"
            />
          </td>
          <td data-row="2" data-col="0"></td>
          <td data-row="2" data-col="1"></td>
          <td data-row="2" data-col="2"></td>
        </tr>
      </tbody>
    </table>

    <div class="grid-caption">
      各セルは対応する H と V の交点で、値は H[col] + V[row] です。
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

  @vite('resources/ts/sim2.ts')
</body>
</html>

