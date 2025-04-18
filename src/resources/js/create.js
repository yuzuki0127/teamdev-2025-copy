import Swal from "sweetalert2";

document.addEventListener("DOMContentLoaded", function () {
    // ボタンの機能実装
    const addQuestionBtns = document.querySelectorAll(
        "button.add-question-btn"
    );
    const sendSurveyForm = document.getElementById("sendSurveyForm");

    // 新しく追加された質問の数
    let addedQuestionsCount = 0;

    // 質問追加ボタン
    addQuestionBtns.forEach((btn) => {
        btn.addEventListener("click", function () {
            const categoryId = btn.getAttribute("data-category-id");
            const container = document.querySelector(`#category-${categoryId}`);

            // 現在の質問数 + 追加した質問数が30個を超えるかチェック
            if (currentQuestionCount + addedQuestionsCount >= maxQuestions) {
                // 30個以上の質問が追加されている場合
                Swal.fire({
                    title: "これ以上質問を追加できません",
                    html: "背景：これ以上質問を追加すると、回答予想時間が15分を超え、回答率の低下が予想されます",
                    icon: "warning",
                    confirmButtonText: "OK",
                });
                return; // 質問追加を中止
            }

            // 新しい質問を追加する処理
            const wrapper = document.createElement("div");
            wrapper.className = "mb-4 flex ml-20 gap-10";

            const questionLabel = document.createElement("div");
            questionLabel.className = "mb-2 font-bold";
            questionLabel.textContent = "質問";
            wrapper.appendChild(questionLabel);

            const questionWrapper = document.createElement("div");
            questionWrapper.className = "flex gap-4 items-center";

            const input = document.createElement("input");
            input.type = "textarea";
            input.rows = 2;
            input.className = "border rounded p-3 w-175 mb-3 border-gray-300";
            input.rows = "2";
            input.name = `question_texts[${categoryId}][]`;
            input.placeholder = "質問を入力してください";

            const deleteBtn = document.createElement("button");
            deleteBtn.className =
                "delete-question-btn text-orange-default font-semibold border-1.5 border-orange-default rounded-lg px-2 hover:bg-orange-default hover:text-white transition";
            deleteBtn.textContent = "− 削除";
            deleteBtn.type = "button";

            deleteBtn.addEventListener("click", function () {
                wrapper.remove();
                addedQuestionsCount--; // 削除時に追加した質問数を減らす
            });

            questionWrapper.appendChild(input);
            questionWrapper.appendChild(deleteBtn);
            wrapper.appendChild(questionWrapper);
            container.appendChild(wrapper);

            addedQuestionsCount++; // 新しく質問を追加したのでカウントを増やす
        });
    });

    // 質問削除ボタン
    const deleteBtns = document.querySelectorAll(".delete-question-btn");
    deleteBtns.forEach((btn) => {
        btn.addEventListener("click", function () {
            const questionWrapper = btn.closest(".mb-4"); // 親要素（質問フォーム）を削除
            questionWrapper.remove(); // 削除処理
            addedQuestionsCount--; // 削除時に追加した質問数を減らす
        });
    });

    // 送信ボタン
    if (!sendSurveyForm) {
        return;
    }
    sendSurveyForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const employeeList = teamEmployees
            .map((emp) => emp.employee_name)
            .join("<br>");
        Swal.fire({
            title: "本当にアンケートを送信しますか？",
            html: "以下の従業員に送信されます：<br><br>" + employeeList,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DC2626",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "送信する",
            cancelButtonText: "キャンセル",
        }).then((result) => {
            if (result.isConfirmed) {
                // ユーザーが確認したらフォーム送信
                sendSurveyForm.submit();
            }
        });
    });
});
