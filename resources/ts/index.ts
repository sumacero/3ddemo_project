type Vec3 = [number, number, number];

const hInputs = [
    document.getElementById("h0") as HTMLInputElement,
    document.getElementById("h1") as HTMLInputElement,
    document.getElementById("h2") as HTMLInputElement,
];

const vInputs = [
    document.getElementById("v0") as HTMLInputElement,
    document.getElementById("v1") as HTMLInputElement,
    document.getElementById("v2") as HTMLInputElement,
];

const cells = Array.from(
    document.querySelectorAll<HTMLTableCellElement>(
        "#output-table td[data-row][data-col]"
    )
);

function clamp255(n: number): number {
    if (Number.isNaN(n)) return 0;
    return Math.min(255, Math.max(0, Math.round(n)));
}

function getInputs(): { h: Vec3; v: Vec3 } {
    const h: Vec3 = [
        clamp255(Number(hInputs[0].value)),
        clamp255(Number(hInputs[1].value)),
        clamp255(Number(hInputs[2].value)),
    ];
    const v: Vec3 = [
        clamp255(Number(vInputs[0].value)),
        clamp255(Number(vInputs[1].value)),
        clamp255(Number(vInputs[2].value)),
    ];
    // 正規化された値を input に戻す（0〜255 以外を打った場合に補正）
    hInputs.forEach((inp, i) => (inp.value = String(h[i])));
    vInputs.forEach((inp, i) => (inp.value = String(v[i])));
    return { h, v };
}

function updateGrid(): void {
    const { h, v } = getInputs();

    cells.forEach((cell) => {
        const row = Number(cell.dataset.row); // 0..2
        const col = Number(cell.dataset.col); // 0..2
        const value = h[col] + v[row];        // 逆投影の単純な足し算モデル
        cell.textContent = String(value);

        // 視覚的に分かりやすいよう、値に応じて背景色を変える
        // 最大値は 255 + 255 = 510 を想定して 0〜1 に正規化
        const ratio = Math.min(1, value / 510);
        const lightness = 100 - ratio * 60; // 値が大きいほど濃く
        cell.style.backgroundColor = `hsl(210, 80%, ${lightness}%)`;
    });
}

// すべての入力にリアルタイム更新のイベントを付与
[...hInputs, ...vInputs].forEach((input) => {
    input.addEventListener("input", updateGrid);
});

// 初期描画
updateGrid();