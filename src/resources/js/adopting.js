import Splide from '@splidejs/splide';

// 主要工程追加用の行を追加する関数（従業員項目を統合）
window.addMajorRow = function() {
  const tableBody = document.getElementById("schedule-table-body");
  // 現在の主要工程行の数を index として利用（行は "bg-gray-100" クラスで識別）
  const majorIndex = tableBody.querySelectorAll('tr.bg-gray-100').length;
  const newRow = document.createElement("tr");
  newRow.className = "bg-gray-100";

  newRow.innerHTML = `
      <td class="border p-2">
          <input type="text" placeholder="主要工程名" name="planning_detail[${majorIndex}][title]" class="w-full h-8 border rounded" />
      </td>
      <td class="border p-2"></td>
      <td class="border p-2"></td>
      <td class="border p-2"></td>
      <td class="border p-1 text-center">
          <button type="button"
              class="remove-row bg-orange-default text-white rounded-full font-bold w-6 h-6 hover:bg-white hover:text-orange-default hover:border hover:border-orange-default transition"
              onclick="removeRow(this)">－</button>
      </td>
  `;
  tableBody.appendChild(newRow);

  // 新規追加行の flatpickr を初期化
  flatpickr(newRow.querySelectorAll(".flatpickr"), {
      dateFormat: "Y-m-d",
      allowInput: true
  });
};

// 具体タスク追加用の行を追加する関数
window.addTaskRow = function() {
  const tableBody = document.getElementById("schedule-table-body");
  // 最新の主要工程行の index を取得（存在しなければ 0 を利用）
  const majorRows = tableBody.querySelectorAll('tr.bg-gray-100');
  let majorIndex = 0;
  let lastMajorRow = null;
  if (majorRows.length > 0) {
    lastMajorRow = majorRows[majorRows.length - 1];
    const input = lastMajorRow.querySelector('input[name^="planning_detail["]');
    const match = input.name.match(/planning_detail\[(\d+)\]/);
    if (match) {
      majorIndex = match[1];
    }
  }
  
  // 最新の主要工程行に紐づくタスク行の数をカウントして taskIndex とする
  let taskIndex = 0;
  if (lastMajorRow) {
    let sibling = lastMajorRow.nextElementSibling;
    while (sibling && !sibling.classList.contains('bg-gray-100')) {
      taskIndex++;
      sibling = sibling.nextElementSibling;
    }
  }
  
  // 従業員オプションは <template id="employeeOptions"> から取得
  const employeeOptions = document.getElementById("employeeOptions").innerHTML;
  
  const newRow = document.createElement("tr");
  newRow.innerHTML = `
      <td class="border pl-8 pr-2 py-2">
          <input type="text" placeholder="具体タスク名" name="planning_detail[${majorIndex}][planning_detail_period][${taskIndex}][task_title]" class="w-full h-8 border rounded" />
      </td>
      <td class="border p-2">
          <select name="planning_detail[${majorIndex}][planning_detail_period][${taskIndex}][task_employee_id]" class="appearance-none w-full h-8 pl-2 pr-8 py-0 border rounded bg-white text-sm">
              ${employeeOptions}
          </select>
      </td>
      <td class="border p-2">
          <input name="planning_detail[${majorIndex}][planning_detail_period][${taskIndex}][task_start_date]" class="text-sm w-full h-8 flatpickr border rounded px-1" type="text" placeholder="開始日">
      </td>
      <td class="border p-2">
          <input name="planning_detail[${majorIndex}][planning_detail_period][${taskIndex}][task_end_date]" class="text-sm w-full h-8 flatpickr border rounded px-1" type="text" placeholder="終了日">
      </td>
      <td class="border p-1 text-center">
          <button type="button"
              class="remove-row bg-orange-500 text-white rounded-full font-bold w-6 h-6 hover:bg-white hover:text-orange-default hover:border hover:border-orange-default transition"
              onclick="removeRow(this)">－</button>
      </td>
  `;
  tableBody.appendChild(newRow);

  flatpickr(newRow.querySelectorAll(".flatpickr"), {
      locale: "ja",
      dateFormat: "Y-m-d",
      allowInput: true
  });
};


// 行を削除する関数
window.removeRow = function(button) {
  const tableBody = document.getElementById("schedule-table-body");
  const row = button.closest('tr');
  if (tableBody.rows.length > 1) {
      row.remove();
  } else {
      alert("最初の行は削除できません。");
  }
};

// 初期読み込み時の flatpickr 適用（日本語設定）
document.addEventListener("DOMContentLoaded", () => {
  flatpickr(".flatpickr", {
      dateFormat: "Y-m-d",
      allowInput: true
  });
  
  // Splide 要素が存在するか確認する
  const splideElem = document.querySelector('.splide');
  if (splideElem) {
    new Splide('.splide', {
      type   : 'loop',
      perPage: 2,
      gap    : '1rem',
      padding: '2rem',
      autoplay: true,
    }).mount();
  }
});

window.fillCreateForm = function(button) {
  const title = button.getAttribute('data-title');
  const description = button.getAttribute('data-description');
  const background = button.getAttribute('data-background');
  const purpose = button.getAttribute('data-purpose');

  const titleInput = document.querySelector('input[name="planning_title"]');
  const descInput = document.querySelector('input[name="description"]');
  const backgroundInput = document.querySelector('input[name="background"]');
  const purposeInput = document.querySelector('input[name="purpose"]');

  if (titleInput) titleInput.value = title;
  if (descInput) descInput.value = description;
  if (backgroundInput) backgroundInput.value = background;
  if (purposeInput) purposeInput.value = purpose;
  
  // スムーズスクロールでフォーム部分へ移動する
  const formSection = document.getElementById('actionForm');
  if (formSection) {
    formSection.scrollIntoView({ behavior: 'smooth' });
  }
};
