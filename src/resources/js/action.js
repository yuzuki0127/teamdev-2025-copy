import Swal from "sweetalert2";

document.addEventListener("DOMContentLoaded", () => {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    const toggles = document.querySelectorAll(".period-toggle");
    toggles.forEach((checkbox) => {
        checkbox.addEventListener("change", () => {
            const periodId = checkbox.dataset.periodId;

            fetch("/action/period/toggle", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({ period_id: periodId }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        // 成功したらページ全体をリロード
                        window.location.reload();
                    }
                })
                .catch((error) => {
                    console.error("更新エラー:", error);
                    // エラー時はチェック状態を元に戻す
                    checkbox.checked = !checkbox.checked;
                });
        });
    });

    const stopActionButton = document.getElementById("stop-action-btn");

    if (stopActionButton) {
        stopActionButton.addEventListener("click", function (event) {
            event.preventDefault();

            Swal.fire({
                title: "本当にタスクを中止しますか？",
                text: "このアクションは取り消せません。",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DC2626",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "中止する",
                cancelButtonText: "いいえ",
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("stop-action-form").submit();
                }
            });
        });
    }

    const completeActionButton = document.getElementById("complete-action-btn");

    if (completeActionButton) {
        completeActionButton.addEventListener("click", function (event) {
            // 100%でない場合は無効にする処理
            if (completeActionButton.disabled) {
                return;
            }
            event.preventDefault(); // フォーム送信を防止

            Swal.fire({
                title: "施策を完了にしますか？",
                text: "このアクションは完了後に変更できません。",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "完了する",
                cancelButtonText: "キャンセル",
            }).then((result) => {
                if (result.isConfirmed) {
                    // モーダルで「完了する」をクリックした場合にのみフォームを送信
                    document.getElementById("complete-action-form").submit();
                }
            });
        });
    }
    const header = document.querySelector(".action-nav");  // navタグを選択
    if (!header) return;
    const initialPosition = header.offsetTop;  // 初期位置を取得
    window.addEventListener("scroll", function () {
        if (window.scrollY >= initialPosition) {
            header.classList.add("fixed", "top-0", "w-full", "z-10"); // スクロール時に固定
        } else {
            header.classList.remove("fixed", "top-0", "w-full", "z-10"); // 元の位置に戻す
        }
});
});
