document.addEventListener("DOMContentLoaded", function() {
  const employeeList = document.getElementById("employee-list");
  const addEmployeeBtn = document.getElementById("add-employee");

  if (!addEmployeeBtn) {
    return;
  }

  addEmployeeBtn.addEventListener("click", function() {
      const currentRows = document.querySelectorAll(".employee-row");
      const newIndex = currentRows.length; // 新しいインデックス

      const firstRow = document.querySelector(".employee-row");
      const newRow = firstRow.cloneNode(true);

      newRow.querySelectorAll("input, select").forEach(function(input) {
        if (input.type !== "radio") {
            input.value = "";
        } else {
            input.checked = false;
        }
    
        if (input.id) {
            input.id = input.id.replace(/\d+$/, newIndex);
        }
        // nits このif文確認
        if (input.name && input.name.includes("employee_sex")) {
            input.name = `employees[${newIndex}][employee_sex]`;
        } else if (input.name && input.name.includes("employee_name")) {
            input.name = `employees[${newIndex}][employee_name]`;
            input.setAttribute("required", "required");
        } else if (input.name && input.name.includes("employee_email")) {
            input.name = `employees[${newIndex}][employee_email]`;
            input.setAttribute("required", "required");
        } else if (input.name && input.name.includes("employee_birth_year")) {
            input.name = `employees[${newIndex}][employee_birth_year]`;
            input.setAttribute("required", "required");
        }
    });

      newRow.querySelector(".remove-employee").classList.remove("hidden");

      employeeList.appendChild(newRow);
  });

  employeeList.addEventListener("click", function(event) {
      if (event.target.closest(".remove-employee")) {
          const rows = document.querySelectorAll(".employee-row");
          if (rows.length > 1) {
              event.target.closest(".employee-row").remove();
          } else {
              alert("少なくとも1人の従業員情報を残してください。");
          }
      }
  });

  document.querySelector(".employee-row .remove-employee").classList.remove("hidden");
});
