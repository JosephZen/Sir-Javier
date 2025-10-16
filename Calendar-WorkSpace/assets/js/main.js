document.getElementById('mode-toggle').addEventListener('click', () => {
    document.documentElement.classList.toggle('dark');
    localStorage.setItem('dark-mode', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
});

document.getElementById('new-task-btn').addEventListener('click', () => {
    document.getElementById('new-task-modal').classList.remove('opacity-0', 'pointer-events-none');
});

document.getElementById('close-modal-btn').addEventListener('click', () => {
    document.getElementById('new-task-modal').classList.add('opacity-0', 'pointer-events-none');
});

document.getElementById('logout-btn').addEventListener('click', () => {
    window.location.href = 'logout.php';
});

const calendarContainer = document.getElementById('calendar-container');
document.getElementById('view-month').addEventListener('click', () => {
    calendarContainer.classList.remove('grid-cols-1', 'grid-cols-24');
    calendarContainer.classList.add('grid-cols-7');
    alert('Loading Month View');  // Replace with actual API call if needed
});

document.getElementById('view-week').addEventListener('click', () => {
    calendarContainer.classList.remove('grid-cols-1', 'grid-cols-24');
    calendarContainer.classList.add('grid-cols-7');
    alert('Loading Week View');
});

document.getElementById('view-day').addEventListener('click', () => {
    calendarContainer.classList.remove('grid-cols-7', 'grid-cols-24');
    calendarContainer.classList.add('grid-cols-1');
    alert('Loading Day View');
});

document.getElementById('view-hour').addEventListener('click', () => {
    calendarContainer.classList.remove('grid-cols-7', 'grid-cols-1');
    calendarContainer.classList.add('grid-cols-24');
    alert('Loading Hour View');
});

document.getElementById('new-task-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    console.log('Submitting form:', Object.fromEntries(formData));
    alert('Task created!');  // In a real app, handle via fetch to your API
});