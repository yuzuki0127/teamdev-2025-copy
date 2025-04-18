import Swal from "sweetalert2";

window.showProcessOfReasoning = function (reasoning, costDetail, priorityDetail) {
    const content = `
        <h3 class="text-xl font-bold mt-4">推論の過程</h3>
        <p class="mb-6 text-base text-start">${reasoning}</p>
        <hr class="border-t-2 border-dashed border-navy-default">
        <h3 class="text-xl font-bold mt-4">コストの背景</h3>
        <p class="mb-6 text-base text-start">${costDetail}</p>
        <hr class="border-t-2 border-dashed border-navy-default">
        <h3 class="text-xl font-bold mt-4">優先度の背景</h3>
        <p class="mb-6 text-base text-start">${priorityDetail}</p>
    `;

    Swal.fire({
        title: "推論の背景",
        html: content,
    });
};

window.toggleSchedule = function (index) {
    const section = document.getElementById("scheduleSection" + index);
    const arrow = document.getElementById("scheduleArrow" + index);

    // もし閉じている状態なら開く
    if (!section.style.maxHeight || section.style.maxHeight === "0px") {
        section.style.maxHeight = section.scrollHeight + "px";
        arrow.textContent = "▲";
    } else {
        // 開いている場合は閉じる
        section.style.maxHeight = "0px";
        arrow.textContent = "▼";
    }
};

// レーダーチャートの初期化
document.addEventListener("DOMContentLoaded", () => {
    const radarChart = document.getElementById("radarChart");
    if (radarChart) {
        const radarCtx = radarChart.getContext("2d");
        new Chart(radarCtx, {
            type: "radar",
            data: {
                labels: window.chartData.labels,
                datasets: [
                    {
                        label: "項目別平均スコア",
                        data: window.chartData.data,
                        backgroundColor: "rgba(255, 165, 0, 0.2)",
                        borderColor: "rgb(255, 165, 0)",
                        borderWidth: 2,
                        pointBackgroundColor: "rgb(255, 165, 0)",
                        pointBorderColor: "#fff",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgb(255, 165, 0)",
                    },
                ],
            },
            options: {
                scales: {
                    r: {
                        beginAtZero: false,
                        min: -2,
                        max: 2,
                        ticks: {
                            stepSize: 1,
                        },
                        pointLabels: {
                            font: { size: 12 },
                        },
                        grid: {
                            lineWidth: (context) => {
                                if (context.tick && context.tick.value === 0) {
                                    return 3;
                                }
                                return 1;
                            },
                        },
                    },
                },
                plugins: {
                    legend: { position: "top" },
                },
            },
        });
    }
});
