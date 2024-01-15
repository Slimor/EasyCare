document.addEventListener("DOMContentLoaded", function () {
  const taskForm = document.getElementById("taskForm");
  const taskInput = document.getElementById("taskInput");
  const taskList = document.getElementById("taskList");

  // Function to add a new task
  function addTask(taskText) {
    const li = document.createElement("li");
    li.className =
      "list-group-item d-flex justify-content-between align-items-center";

    const label = document.createElement("label");
    label.className = "form-check-label";

    const checkbox = document.createElement("input");
    checkbox.type = "checkbox";
    checkbox.className = "form-check-input";

    label.appendChild(checkbox);
    label.appendChild(document.createTextNode(taskText));
    li.appendChild(label);

    const deleteButton = document.createElement("button");
    deleteButton.className = "btn btn-danger btn-sm";
    deleteButton.innerHTML = "Usuń";

    deleteButton.addEventListener("click", function () {
      li.remove();
      updateLocalStorage();
    });

    li.appendChild(deleteButton);
    taskList.appendChild(li);
  }

  // Function to update local storage with current tasks
  function updateLocalStorage() {
    const tasks = [];
    taskList.querySelectorAll("label.form-check-label").forEach((label) => {
      tasks.push(label.innerText);
    });
    localStorage.setItem("tasks", JSON.stringify(tasks));
  }

  // Load tasks from local storage on page load
  const storedTasks = localStorage.getItem("tasks");
  if (storedTasks) {
    const tasks = JSON.parse(storedTasks);
    tasks.forEach((task) => {
      addTask(task);
    });
  }

  // Event listener for form submission to add a task
  taskForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const taskText = taskInput.value.trim();
    if (taskText !== "") {
      addTask(taskText);
      updateLocalStorage();
      taskInput.value = "";
    }
  });

  // Event listener for checking tasks
  taskList.addEventListener("change", function (event) {
    if (event.target.matches("input[type='checkbox']")) {
      const label = event.target.parentElement;
      if (event.target.checked) {
        label.classList.add("text-decoration-line-through", "text-muted");
      } else {
        label.classList.remove("text-decoration-line-through", "text-muted");
      }
      updateLocalStorage();
    }
  });
});
