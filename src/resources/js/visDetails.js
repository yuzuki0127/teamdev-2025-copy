// 折れ線グラフ（全体平均推移）の初期化
document.addEventListener("DOMContentLoaded", () => {
    if (document.getElementById("lineChart")) {
        const lineCtx = document.getElementById("lineChart").getContext("2d");
        new Chart(lineCtx, {
            type: "line",
            data: {
                labels: window.lineLabels,
                datasets: [
                    {
                        label: "全体平均スコア",
                        data: window.lineData,
                        fill: false,
                        borderColor: "#0062B1",
                        tension: 0.1,
                    },
                ],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: false,
                        min: -2,
                        max: 2,
                        ticks: { stepSize: 1 },
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

// カテゴリごとのグラフ初期化
if (window.chartData && typeof window.chartData === "object") {
    Object.keys(window.chartData).forEach((categoryId) => {
        const dataObj = window.chartData[categoryId];
        const canvasId = "lineChart" + dataObj.categoryId;
        const canvasElem = document.getElementById(canvasId);
        if (!canvasElem) return;
        const ctx = canvasElem.getContext("2d");

        new Chart(ctx, {
            type: "line",
            data: {
                labels: dataObj.lineLabels,
                datasets: [
                    {
                        label: "個別項目平均",
                        data: dataObj.itemAverages,
                        borderColor: "#4CAF50",
                        fill: false,
                        tension: 0,
                    },
                    {
                        label: "全体平均",
                        data: dataObj.lineData,
                        borderColor: "gray",
                        fill: false,
                        tension: 0,
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: "top",
                    },
                    tooltip: {
                        mode: "index",
                        intersect: false,
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: "日付",
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: "平均値",
                        },
                        min: -2,
                        max: 2,
                        ticks: {
                            stepSize: 0.5,
                        },
                        grid: {
                            lineWidth: (context) =>
                                context.tick && context.tick.value === 0
                                    ? 3
                                    : 1,
                        },
                    },
                },
            },
        });
    });
}

// 「詳細を見る」ボタンの表示/非表示切替処理
document.querySelectorAll(".toggle-details").forEach((button) => {
    button.addEventListener("click", () => {
        const categoryId = button.getAttribute("data-category-id");
        const detailContent = document.getElementById(
            "detail-content-" + categoryId
        );
        if (detailContent.classList.contains("hidden")) {
            detailContent.classList.remove("hidden");
            button.textContent = "詳細を閉じる";
        } else {
            detailContent.classList.add("hidden");
            button.textContent = "詳細を見る";
        }
    });
});

// 質問ごとのグラフ初期化
if (window.chartDataQuestions) {
    Object.keys(window.chartDataQuestions).forEach((questionId) => {
        const qData = window.chartDataQuestions[questionId];
        const canvasId = "questionChart" + qData.questionId;
        const canvasElem = document.getElementById(canvasId);
        if (!canvasElem) return;
        const ctx = canvasElem.getContext("2d");
        new Chart(ctx, {
            type: "line",
            data: {
                labels: qData.lineLabels,
                datasets: [
                    {
                        label: "質問平均",
                        data: qData.lineData,
                        borderColor: "#3F51B5",
                        fill: false,
                        tension: 0,
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: "top",
                    },
                    tooltip: {
                        mode: "index",
                        intersect: false,
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: "日付",
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: "平均値",
                        },
                        min: -2,
                        max: 2,
                        ticks: {
                            stepSize: 0.5,
                        },
                        grid: {
                            lineWidth: (context) =>
                                context.tick && context.tick.value === 0
                                    ? 3
                                    : 1,
                        },
                    },
                },
            },
        });
    });
}
