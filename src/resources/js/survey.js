import Swal from "sweetalert2";

document.addEventListener("DOMContentLoaded", function () {
    const progressBar = document.getElementById("progress-bar");
    const progressBarContainer = document.querySelector(".progress-bar-container");
    const progressText = document.getElementById("progress-text");
    const remainingTimeText = document.querySelector("#remaining-time");
    const surveyMeta = document.getElementById("survey-meta");
    if (!surveyMeta) {
        return;
    }
    const totalQuestions = parseInt(
        surveyMeta.dataset.totalQuestions,
        10
    );
    const secondsPerQuestion = 30; // 質問1つにつき30秒
    const submitButton = document.getElementById("submit-button"); // 送信ボタン
    const form = document.querySelector("form"); // フォーム

    // 初期状態ではプログレスバーが青色を持たないように設定
    progressBar.classList.remove("bg-blue-default");

    // トグルボタンの動作
    const toggleButtons = document.querySelectorAll(".toggle-description");
    toggleButtons.forEach((button) => {
        button.addEventListener("click", function (e) {
            const targetId = button.getAttribute("data-target");
            const targetElement = document.getElementById(targetId);
            const svgIcon = button.querySelector("svg");
            const hiddenForm = button
                .closest(".survey-question")
                .querySelector(".hidden-form");

            if (targetElement) {
                targetElement.classList.toggle("hidden");
                if (targetElement.classList.contains("hidden")) {
                    svgIcon.style.transform = "rotate(0deg)";
                    hiddenForm.classList.remove("show");
                } else {
                    svgIcon.style.transform = "rotate(180deg)";
                    hiddenForm.classList.add("show");
                }
            }
        });
    });

    // プログレスバー上部固定
    const initialPosition = progressBarContainer.offsetTop;
    window.addEventListener("scroll", function () {
        if (window.scrollY >= initialPosition) {
            progressBarContainer.classList.add(
                "fixed",
                "top-0",
                "w-175",
                "z-10",
                "progress-stacked"
            );
            progressBarContainer.classList.remove("rounded-lg");
        } else {
            progressBarContainer.classList.remove(
                "fixed",
                "top-0",
                "w-175",
                "z-10",
                "progress-stacked"
            );
            progressBarContainer.classList.add("rounded-lg");
        }
    });

    // ラジオボタン選択
    document.addEventListener("click", function (e) {
        const label = e.target.closest(".radio-label");
        if (!label) return;
        const radio = document.getElementById(label.getAttribute("for"));
        if (!radio) return;
        const name = radio.getAttribute("name");
        const activeLabel = document.querySelector(
            `input[name="${name}"] + .bg-blue-default`
        );
        if (activeLabel) {
            activeLabel.classList.remove("bg-blue-default");
        }
        label.classList.add("bg-blue-default");
        radio.checked = true;
        updateProgress();
        validateForm(); // ラジオボタン選択後にフォームのバリデーションを実行
    });

    // プログレスバーの進捗状況更新
    function updateProgress() {
        const answeredQuestions = document.querySelectorAll(
            'input[type="radio"]:checked'
        ).length;
        const progress = Math.round((answeredQuestions / totalQuestions) * 100);
        progressBar.style.width = `${progress}%`;
        progressText.textContent = `${progress}%`;

        // 進捗が0%の場合は背景色をリセット
        if (progress === 0) {
            progressBar.classList.remove("bg-blue-default");
        } else {
            progressBar.classList.add("bg-blue-default");
        }

        // 残り時間の計算
        const remainingQuestions = totalQuestions - answeredQuestions;
        const remainingTimeInSeconds = remainingQuestions * secondsPerQuestion;
        const minutes = Math.floor(remainingTimeInSeconds / 60);
        const seconds = remainingTimeInSeconds % 60;

        // 残り時間を表示
        remainingTimeText.textContent = `残り ${minutes}分 ${seconds}秒`;
    }

    // フォームのバリデーション
    function validateForm() {
        let allAnswered = true;

        // 各質問に対して、少なくとも1つの選択肢が選ばれているか確認
        document.querySelectorAll(".survey-question").forEach((question) => {
            const radioGroup = question.querySelectorAll('input[type="radio"]');
            const isChecked = Array.from(radioGroup).some(
                (radio) => radio.checked
            );

            if (!isChecked) {
                allAnswered = false;
            }
        });

        // すべての質問が回答されていれば送信ボタンを有効化
        if (allAnswered) {
            submitButton.disabled = false;
            submitButton.classList.remove("bg-gray-600");
            submitButton.classList.add("bg-blue-default");
        } else {
            submitButton.disabled = true;
            submitButton.classList.add("bg-gray-600");
            submitButton.classList.remove("bg-blue-default");
        }
    }

    // SweetAlert2 で確認ダイアログを表示
    submitButton.addEventListener("click", function (e) {
        e.preventDefault(); // フォームが送信されないようにする

        if (!submitButton.disabled) {
            Swal.fire({
                title: "本当に送信しますか？",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "はい、送信します",
                cancelButtonText: "キャンセル",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });

    // 初期呼び出し
    updateProgress();
    validateForm();
});
