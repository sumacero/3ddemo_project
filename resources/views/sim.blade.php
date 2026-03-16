<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>逆投影シミュレーション</title>
  <style>
    body { font-family: sans-serif; padding: 16px; }
    .page-title { margin-bottom: 24px; }

    .layout-root {
      display: inline-block;
      border: 1px solid #ddd;
      padding: 16px 16px 12px;
      border-radius: 8px;
      background: #fafafa;
      max-width: 100%;
      overflow: visible;
    }

    .horizontal-label {
      text-align: center;
      margin-bottom: 8px;
      font-weight: bold;
    }

    .pixel-input {
      width: 40px;
      height: 40px;
      box-sizing: border-box;
      text-align: center;
    }

    table { border-collapse: collapse; }
    td {
      border: 1px solid #ccc;
      width: 40px;
      height: 40px;
      text-align: center;
      vertical-align: middle;
      transition: background 0.2s;
      font-size: 14px;
    }

    .grid-caption {
      margin-top: 12px;
      font-size: 12px;
      color: #666;
    }
  </style>
</head>
<body>
  <h1 class="page-title">逆投影シミュレーション（3×3）</h1>

  <div class="layout-root">
    <div class="horizontal-label">水平向きカメラ（H）</div>

    <table id="output-table">
      <tbody>
        <tr>
          <td></td>
          <td>
            <label>
              H0
              <input
                id="h0"
                class="pixel-input"
                type="number"
                min="0"
                max="255"
                value="0"
              />
            </label>
          </td>
          <td>
            <label>
              H1
              <input
                id="h1"
                class="pixel-input"
                type="number"
                min="0"
                max="255"
                value="0"
              />
            </label>
          </td>
          <td>
            <label>
              H2
              <input
                id="h2"
                class="pixel-input"
                type="number"
                min="0"
                max="255"
                value="0"
              />
            </label>
          </td>
        </tr>
        <tr>
          <td class="vertical-cell">
            <label>
              V0
              <input
                id="v0"
                class="pixel-input"
                type="number"
                min="0"
                max="255"
                value="0"
              />
            </label>
          </td>
          <td data-row="0" data-col="0"></td>
          <td data-row="0" data-col="1"></td>
          <td data-row="0" data-col="2"></td>
        </tr>
        <tr>
          <td class="vertical-cell">
            <label>
              V1
              <input
                id="v1"
                class="pixel-input"
                type="number"
                min="0"
                max="255"
                value="0"
              />
            </label>
          </td>
          <td data-row="1" data-col="0"></td>
          <td data-row="1" data-col="1"></td>
          <td data-row="1" data-col="2"></td>
        </tr>
        <tr>
          <td class="vertical-cell">
            <label>
              V2
              <input
                id="v2"
                class="pixel-input"
                type="number"
                min="0"
                max="255"
                value="0"
              />
            </label>
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
  </div>

  @vite('resources/ts/index.ts')
</body>
</html>