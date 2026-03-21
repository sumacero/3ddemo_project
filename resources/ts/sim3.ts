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

const hSliders = [
    document.getElementById("h0-slider") as HTMLInputElement,
    document.getElementById("h1-slider") as HTMLInputElement,
    document.getElementById("h2-slider") as HTMLInputElement,
];

const vSliders = [
    document.getElementById("v0-slider") as HTMLInputElement,
    document.getElementById("v1-slider") as HTMLInputElement,
    document.getElementById("v2-slider") as HTMLInputElement,
];

const cells = Array.from(
    document.querySelectorAll<HTMLElement>(
        "#output-table [data-row][data-col]"
    )
);

function clamp15(n: number): number {
    if (Number.isNaN(n)) return 0;
    return Math.min(15, Math.max(0, Math.round(n)));
}

function syncSlidersAndLabels(h: Vec3, v: Vec3): void {
    hSliders.forEach((s, i) => {
        s.value = String(h[i]);
    });
    vSliders.forEach((s, i) => {
        s.value = String(v[i]);
    });
}

function getInputs(): { h: Vec3; v: Vec3 } {
    const h: Vec3 = [
        clamp15(Number(hInputs[0].value)),
        clamp15(Number(hInputs[1].value)),
        clamp15(Number(hInputs[2].value)),
    ];
    const v: Vec3 = [
        clamp15(Number(vInputs[0].value)),
        clamp15(Number(vInputs[1].value)),
        clamp15(Number(vInputs[2].value)),
    ];
    // 正規化された値を input / スライダー / 表示に反映（0〜15 以外を打った場合に補正）
    hInputs.forEach((inp, i) => (inp.value = String(h[i])));
    vInputs.forEach((inp, i) => (inp.value = String(v[i])));
    syncSlidersAndLabels(h, v);
    return { h, v };
}

function updateGrid(): void {
    const { h, v } = getInputs();

    cells.forEach((cell) => {
        const row = Number(cell.dataset.row); // 0..2
        const col = Number(cell.dataset.col); // 0..2
        const value = h[col] + v[row];        // 逆投影の単純な足し算モデル
        cell.textContent = String(value);

        // 値に応じて背景色をリアルタイムに変える（ヒートマップ風）
        // 最大値は 15 + 15 = 30 を想定して 0〜1 に正規化
        const ratio = Math.min(1, Math.max(0, value / 30));

        // 220(青) -> 55(黄) -> 10(赤) の2段補間
        const hue = ratio < 0.5
            ? 220 + (55 - 220) * (ratio / 0.5)
            : 55 + (10 - 55) * ((ratio - 0.5) / 0.5);
        const lightness = 92 - ratio * 45;
        cell.style.backgroundColor = `hsl(${hue}, 90%, ${lightness}%)`;
        cell.style.color = ratio > 0.75 ? "#fff" : "#111";
    });
}

// 数値入力
[...hInputs, ...vInputs].forEach((input) => {
    input.addEventListener("input", updateGrid);
});

// スライダー → 数値入力と同期してグリッド更新
hSliders.forEach((slider, i) => {
    slider.addEventListener("input", () => {
        hInputs[i].value = slider.value;
        updateGrid();
    });
});

vSliders.forEach((slider, i) => {
    slider.addEventListener("input", () => {
        vInputs[i].value = slider.value;
        updateGrid();
    });
});

// 初期描画
updateGrid();

export {};
