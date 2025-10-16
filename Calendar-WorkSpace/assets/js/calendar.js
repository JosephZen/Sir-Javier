// Inside modalBody.innerHTML, after collaborators div
if (task.clickup_id) {
    modalBody.innerHTML += `
        <div>
            <label class="text-sm font-semibold text-gray-600">ClickUp Task</label>
            <a href="https://app.clickup.com/t/${task.clickup_id}" target="_blank" class="text-blue-600 hover:underline mt-1 block">View in ClickUp (ID: ${task.clickup_id})</a>
        </div>
    `;
} else {
    modalBody.innerHTML += `
        <div>
            <label class="text-sm font-semibold text-gray-600">ClickUp Task</label>
            <span class="text-sm text-gray-400 mt-1 block">Not synced to ClickUp</span>
        </div>
    `;
}

// New Task Modal
const newTaskModal = document.getElementById('new-task-modal');
const newTaskContent = document.getElementById('new-task-content');
const closeNewTaskBtn = document.getElementById('close-new-task-btn');
const newTaskBtn = document.getElementById('new-task-btn');
const newTaskForm = document.getElementById('new-task-form');

newTaskBtn.addEventListener('click', () => {
    newTaskModal.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
    newTaskContent.classList.remove('scale-95');
});

closeNewTaskBtn.addEventListener('click', () => {
    newTaskModal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
    newTaskContent.classList.add('scale-95');
});

newTaskForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(newTaskForm);
    const data = Object.fromEntries(formData);

    try {
        const response = await fetch('api/tasks.php', {
            method: 'POST',
            body: new URLSearchParams(data)
        });
        const result = await response.json();
        if (result.success) {
            // Refresh tasks and close modal
            await loadTasks();  // Assume you have a loadTasks() function that fetches from API and re-renders
            closeNewTaskModal();
            alert(result.clickup_id ? 'Task created and synced to ClickUp!' : (result.clickup_error ? result.clickup_error : 'Task created!'));
        } else {
            alert('Error: ' + (result.error || 'Failed to create task'));
        }
    } catch (err) {
        alert('Network error: ' + err.message);
    }
});

function closeNewTaskModal() {
    newTaskModal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
    newTaskContent.classList.add('scale-95');
}

// Update existing loadTasks() or render() to fetch from API
async function loadTasks(start, end) {
    const params = new URLSearchParams({ action: 'get', start, end });
    const response = await fetch(`api/tasks.php?${params}`);
    tasks = await response.json();  // Update global tasks array
    render();  // Re-render calendar
}

// Call loadTasks() in initial render instead of generateMockTasks()

const modeToggle = document.getElementById('mode-toggle');
modeToggle.addEventListener('click', () => {
    document.documentElement.classList.toggle('dark');
    localStorage.setItem('dark-mode', document.documentElement.classList.contains('dark') ? 'enabled' : 'disabled');
});

document.getElementById('connect-clickup-btn').addEventListener('click', () => {
    window.location.href = 'oauth/init.php';  // Points to your init file
});