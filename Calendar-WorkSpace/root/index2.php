<!DOCTYPE html> #reference code, don't review
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Task Calendar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6; /* A light gray background */
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 1px;
            background-color: #d1d5db; /* Grid lines color */
            border: 1px solid #d1d5db;
        }
        .calendar-day {
            min-height: 120px;
        }
        .hour-row {
            min-height: 80px;
        }
        .task-item {
            transition: all 0.2s ease-in-out;
        }
        .task-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10;
        }
        /* Custom scrollbar for better aesthetics */
        .day-tasks-wrapper::-webkit-scrollbar {
            width: 6px;
        }
        .day-tasks-wrapper::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .day-tasks-wrapper::-webkit-scrollbar-thumb {
            background: #a8a29e;
            border-radius: 10px;
        }
        .day-tasks-wrapper::-webkit-scrollbar-thumb:hover {
            background: #78716c;
        }
        .modal {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
    </style>
</head>
<body class="antialiased text-gray-800">

    <div id="app" class="p-4 md:p-8 min-h-screen">

        <!-- Header: Controls & Navigation -->
        <header class="mb-6 bg-white p-4 rounded-xl shadow-md">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <button id="prev-btn" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg shadow">&lt;</button>
                    <h2 id="current-period" class="text-xl md:text-2xl font-bold text-gray-700 w-48 text-center"></h2>
                    <button id="next-btn" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg shadow">&gt;</button>
                     <button id="today-btn" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">Today</button>
                </div>
                <div class="flex items-center bg-gray-100 rounded-lg p-1 space-x-1">
                    <button data-view="month" class="view-btn px-3 py-1 text-sm font-medium rounded-md">Month</button>
                    <button data-view="week" class="view-btn px-3 py-1 text-sm font-medium rounded-md">Week</button>
                    <button data-view="day" class="view-btn px-3 py-1 text-sm font-medium rounded-md">Day</button>
                    <button data-view="hour" class="view-btn px-3 py-1 text-sm font-medium rounded-md">Hour</button>
                </div>
            </div>
        </header>

        <!-- Calendar Container -->
        <main id="calendar-container" class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Calendar grid will be rendered here by JavaScript -->
        </main>
    </div>

    <!-- Task Detail Modal -->
    <div id="task-modal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 opacity-0 pointer-events-none transform scale-95">
        <div id="modal-content" class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative transform transition-all duration-300">
            <button id="close-modal-btn" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
            <div id="modal-body">
                <!-- Task details will be rendered here -->
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- STATE MANAGEMENT ---
            let currentDate = new Date();
            let currentView = 'month';
            let tasks = generateMockTasks();

            // --- DOM ELEMENTS ---
            const calendarContainer = document.getElementById('calendar-container');
            const currentPeriodEl = document.getElementById('current-period');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const todayBtn = document.getElementById('today-btn');
            const viewBtns = document.querySelectorAll('.view-btn');
            const modal = document.getElementById('task-modal');
            const modalContent = document.getElementById('modal-content');
            const closeModalBtn = document.getElementById('close-modal-btn');

            // --- MOCK DATA GENERATION ---
            function generateMockTasks() {
                const mockTasks = [];
                const today = new Date();
                const people = ['Alice', 'Bob', 'Charlie', 'Diana', 'Ethan'];
                const taskTitles = ['Design new UI Kit', 'Develop API endpoint', 'Fix login bug', 'Write documentation', 'Deploy to staging', 'Client meeting prep', 'Code review', 'Marketing campaign analysis'];
                const statuses = ['ongoing', 'almost-finished', 'help-requested', 'finished'];

                for (let i = 0; i < 50; i++) {
                    const start = new Date(today.getTime());
                    const dayOffset = Math.floor(Math.random() * 30) - 15;
                    start.setDate(today.getDate() + dayOffset);
                    start.setHours(Math.floor(Math.random() * 10) + 8, Math.random() > 0.5 ? 30 : 0, 0, 0);

                    const end = new Date(start.getTime());
                    end.setHours(start.getHours() + Math.floor(Math.random() * 3) + 1);
                    
                    const assignedCount = Math.floor(Math.random() * 2) + 1;
                    const assignedTo = [];
                    for(let j=0; j<assignedCount; j++){
                         assignedTo.push(people[Math.floor(Math.random() * people.length)]);
                    }

                    mockTasks.push({
                        id: i + 1,
                        title: taskTitles[Math.floor(Math.random() * taskTitles.length)],
                        startTime: start,
                        endTime: end,
                        assignedTo: [...new Set(assignedTo)], // Ensure unique people
                        progress: Math.floor(Math.random() * 101),
                        status: statuses[Math.floor(Math.random() * statuses.length)],
                        collaborators: [people[Math.floor(Math.random() * people.length)]].filter(p => !assignedTo.includes(p))
                    });
                }
                return mockTasks;
            }

            // --- RENDERING FUNCTIONS ---
            function render() {
                updateCurrentPeriodDisplay();
                updateActiveViewButton();
                calendarContainer.innerHTML = ''; // Clear previous view
                switch (currentView) {
                    case 'month':
                        renderMonthView();
                        break;
                    case 'week':
                        renderWeekView();
                        break;
                    case 'day':
                        renderDayView();
                        break;
                    case 'hour':
                        renderHourView();
                        break;
                }
            }
            
            function renderMonthView() {
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth();
                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                const daysInMonth = lastDay.getDate();
                const startDayOfWeek = firstDay.getDay(); // 0=Sun, 1=Mon, ...

                let html = '<div class="grid grid-cols-7 text-center font-semibold text-gray-600 border-b border-gray-200">';
                ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].forEach(day => {
                    html += `<div class="py-2">${day}</div>`;
                });
                html += '</div><div class="calendar-grid">';

                // Add empty cells for days before the 1st
                for (let i = 0; i < startDayOfWeek; i++) {
                    html += '<div class="bg-gray-50"></div>';
                }

                // Add day cells
                for (let day = 1; day <= daysInMonth; day++) {
                    const date = new Date(year, month, day);
                    const isToday = date.toDateString() === new Date().toDateString();
                    const dayTasks = tasks.filter(task => isSameDay(task.startTime, date));
                    
                    html += `<div class="calendar-day bg-white p-2 relative flex flex-col">
                                <div class="text-sm font-medium ${isToday ? 'bg-indigo-600 text-white rounded-full w-6 h-6 flex items-center justify-center' : 'text-gray-700'}">${day}</div>
                                <div class="day-tasks-wrapper flex-grow overflow-y-auto mt-1 space-y-1">`;
                    
                    dayTasks.slice(0, 3).forEach(task => { // Show max 3 tasks
                        html += createTaskElement(task);
                    });

                    if (dayTasks.length > 3) {
                        html += `<div class="text-xs text-indigo-500 font-semibold mt-1">+${dayTasks.length - 3} more</div>`;
                    }
                    html += `</div></div>`;
                }
                html += '</div>';
                calendarContainer.innerHTML = html;
            }

            function renderWeekView() {
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth();
                const day = currentDate.getDate();

                const startOfWeek = new Date(currentDate);
                startOfWeek.setDate(day - currentDate.getDay());

                let html = '<div class="grid grid-cols-7 text-center font-semibold text-gray-600 border-b border-gray-200">';
                for (let i = 0; i < 7; i++) {
                    const currentDay = new Date(startOfWeek);
                    currentDay.setDate(startOfWeek.getDate() + i);
                    const isToday = currentDay.toDateString() === new Date().toDateString();
                    html += `<div class="py-2 ${isToday ? 'text-indigo-600' : ''}">${currentDay.toLocaleString('default', { weekday: 'short' })} ${currentDay.getDate()}</div>`;
                }
                html += '</div><div class="calendar-grid">';

                for (let i = 0; i < 7; i++) {
                    const date = new Date(startOfWeek);
                    date.setDate(startOfWeek.getDate() + i);
                    const dayTasks = tasks.filter(task => isSameDay(task.startTime, date));

                    html += `<div class="calendar-day bg-white p-2 relative flex flex-col">
                                <div class="day-tasks-wrapper flex-grow overflow-y-auto mt-1 space-y-1">`;
                    
                    dayTasks.forEach(task => {
                        html += createTaskElement(task, true); // Show time in week view
                    });
                    
                    html += `</div></div>`;
                }
                html += '</div>';
                calendarContainer.innerHTML = html;
            }

            function renderDayView() {
                let html = '<div class="flex flex-col h-[75vh]">';
                const hours = Array.from({ length: 24 }, (_, i) => i);

                html += `<div class="flex-grow overflow-y-auto relative border border-gray-200">`;
                
                // Hour markers
                hours.forEach(hour => {
                    html += `<div class="hour-row border-b border-gray-200 flex items-start p-2">
                                <div class="w-16 text-right pr-2 text-sm text-gray-500">${hour.toString().padStart(2, '0')}:00</div>
                                <div class="flex-grow h-full border-l border-gray-200"></div>
                             </div>`;
                });
                
                // Tasks container
                html += `<div class="absolute top-0 left-16 right-0 bottom-0 ml-2">`;
                const dayTasks = tasks.filter(task => isSameDay(task.startTime, currentDate));
                dayTasks.forEach(task => {
                    const top = (task.startTime.getHours() + task.startTime.getMinutes() / 60) / 24 * 100;
                    const duration = (task.endTime.getTime() - task.startTime.getTime()) / (1000 * 60 * 60);
                    const height = (duration / 24) * 100;

                    html += `<div class="absolute w-full pr-2" style="top: ${top}%; height: ${height}%; z-index: 5;">
                                ${createTaskElement(task, true)}
                             </div>`;
                });
                html += `</div></div></div>`;

                calendarContainer.innerHTML = html;
            }

            function renderHourView() {
                 renderDayView(); // Hour view is essentially a more focused day view in this simulation
            }

            // --- TASK ELEMENT & MODAL ---
            function createTaskElement(task, showTime = false) {
                const { bgColor, textColor, borderColor } = getStatusColors(task.status);
                const timeString = showTime ? `<div class="text-xs font-medium">${formatTime(task.startTime)} - ${formatTime(task.endTime)}</div>` : '';

                return `
                    <div class="task-item text-xs p-2 rounded-lg cursor-pointer ${bgColor} ${textColor} border-l-4 ${borderColor}" data-task-id="${task.id}">
                        <p class="font-bold truncate">${task.title}</p>
                        ${timeString}
                        <div class="flex items-center space-x-2 mt-1">
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="bg-blue-500 h-1.5 rounded-full" style="width: ${task.progress}%"></div>
                            </div>
                            <span class="text-xs font-semibold">${task.progress}%</span>
                        </div>
                        <div class="flex items-center mt-2 -space-x-1">
                            ${task.assignedTo.map(p => createAvatar(p)).join('')}
                        </div>
                    </div>
                `;
            }
            
            function createAvatar(name) {
                const colors = ['bg-red-500', 'bg-green-500', 'bg-blue-500', 'bg-yellow-500', 'bg-purple-500', 'bg-pink-500'];
                const color = colors[name.charCodeAt(0) % colors.length];
                return `<div title="${name}" class="w-5 h-5 rounded-full ${color} text-white flex items-center justify-center text-xs font-bold ring-2 ring-white">${name.charAt(0)}</div>`;
            }

            function openTaskModal(taskId) {
                const task = tasks.find(t => t.id == taskId);
                if (!task) return;

                const { bgColor, textColor, borderColor, textLabel } = getStatusColors(task.status);

                const modalBody = document.getElementById('modal-body');
                modalBody.innerHTML = `
                    <div class="border-l-8 ${borderColor} pl-4 mb-4">
                        <h3 class="text-2xl font-bold text-gray-800">${task.title}</h3>
                        <p class="text-sm text-gray-500">${task.startTime.toLocaleString()} - ${task.endTime.toLocaleString()}</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Status</label>
                            <div class="mt-1 p-2 rounded-lg font-semibold ${bgColor} ${textColor} text-center">${textLabel}</div>
                        </div>

                        <div>
                             <label class="text-sm font-semibold text-gray-600">Progress: ${task.progress}%</label>
                             <input type="range" min="0" max="100" value="${task.progress}" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer mt-1" data-task-id="${task.id}" oninput="updateTaskProgress(this, ${task.id})">
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-600">Assigned To</label>
                            <div class="flex items-center space-x-2 mt-1">
                                ${task.assignedTo.map(p => `<div class="flex items-center space-x-1 bg-gray-100 rounded-full px-2 py-1">${createAvatar(p)}<span class="text-sm">${p}</span></div>`).join('')}
                            </div>
                        </div>
                        
                         <div>
                            <label class="text-sm font-semibold text-gray-600">Collaborators</label>
                            <div class="flex items-center space-x-2 mt-1">
                                 ${task.collaborators.length > 0 ? task.collaborators.map(p => `<div class="flex items-center space-x-1 bg-gray-100 rounded-full px-2 py-1">${createAvatar(p)}<span class="text-sm">${p}</span></div>`).join('') : '<span class="text-sm text-gray-400">None</span>'}
                                <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 w-6 h-6 rounded-full flex items-center justify-center">+</button>
                            </div>
                        </div>

                        <div class="border-t pt-4 mt-4">
                             <label class="text-sm font-semibold text-gray-600 mb-2 block">Actions</label>
                             <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                <button onclick="updateTaskStatus(${task.id}, 'ongoing')" class="px-3 py-2 text-sm bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200">On-going</button>
                                <button onclick="updateTaskStatus(${task.id}, 'almost-finished')" class="px-3 py-2 text-sm bg-yellow-100 text-yellow-800 rounded-lg hover:bg-yellow-200">Almost Done</button>
                                <button onclick="updateTaskStatus(${task.id}, 'help-requested')" class="px-3 py-2 text-sm bg-red-100 text-red-800 rounded-lg hover:bg-red-200">Request Help</button>
                                <button onclick="updateTaskStatus(${task.id}, 'finished')" class="px-3 py-2 text-sm bg-green-100 text-green-800 rounded-lg hover:bg-green-200">Mark as Done</button>
                             </div>
                        </div>
                    </div>
                `;

                modal.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
                modalContent.classList.remove('scale-95');
            }
            
            function closeModal() {
                modal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
                modalContent.classList.add('scale-95');
            }


            // --- HELPERS ---
            function updateCurrentPeriodDisplay() {
                switch (currentView) {
                    case 'month':
                        currentPeriodEl.textContent = currentDate.toLocaleString('default', { month: 'long', year: 'numeric' });
                        break;
                    case 'week':
                        const startOfWeek = new Date(currentDate);
                        startOfWeek.setDate(currentDate.getDate() - currentDate.getDay());
                        const endOfWeek = new Date(startOfWeek);
                        endOfWeek.setDate(startOfWeek.getDate() + 6);
                        currentPeriodEl.textContent = `${startOfWeek.toLocaleDateString()} - ${endOfWeek.toLocaleDateString()}`;
                        break;
                    case 'day':
                    case 'hour':
                        currentPeriodEl.textContent = currentDate.toLocaleDateString('default', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                        break;
                }
            }
            
            function updateActiveViewButton() {
                viewBtns.forEach(btn => {
                    if (btn.dataset.view === currentView) {
                        btn.classList.add('bg-indigo-600', 'text-white', 'shadow');
                        btn.classList.remove('text-gray-600');
                    } else {
                        btn.classList.remove('bg-indigo-600', 'text-white', 'shadow');
                        btn.classList.add('text-gray-600');
                    }
                });
            }

            function isSameDay(d1, d2) {
                return d1.getFullYear() === d2.getFullYear() &&
                    d1.getMonth() === d2.getMonth() &&
                    d1.getDate() === d2.getDate();
            }

            function formatTime(date) {
                return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
            }

            function getStatusColors(status) {
                switch (status) {
                    case 'ongoing': return { bgColor: 'bg-blue-50', textColor: 'text-blue-700', borderColor: 'border-blue-500', textLabel: 'On-going' };
                    case 'almost-finished': return { bgColor: 'bg-yellow-50', textColor: 'text-yellow-700', borderColor: 'border-yellow-500', textLabel: 'Almost Finished' };
                    case 'help-requested': return { bgColor: 'bg-red-50', textColor: 'text-red-700', borderColor: 'border-red-500', textLabel: 'Help Requested' };
                    case 'finished': return { bgColor: 'bg-green-50', textColor: 'text-green-700', borderColor: 'border-green-500', textLabel: 'Finished' };
                    default: return { bgColor: 'bg-gray-50', textColor: 'text-gray-700', borderColor: 'border-gray-500', textLabel: 'Unknown' };
                }
            }

            // --- EVENT LISTENERS ---
            prevBtn.addEventListener('click', () => {
                switch (currentView) {
                    case 'month': currentDate.setMonth(currentDate.getMonth() - 1); break;
                    case 'week': currentDate.setDate(currentDate.getDate() - 7); break;
                    case 'day': case 'hour': currentDate.setDate(currentDate.getDate() - 1); break;
                }
                render();
            });

            nextBtn.addEventListener('click', () => {
                 switch (currentView) {
                    case 'month': currentDate.setMonth(currentDate.getMonth() + 1); break;
                    case 'week': currentDate.setDate(currentDate.getDate() + 7); break;
                    case 'day': case 'hour': currentDate.setDate(currentDate.getDate() + 1); break;
                }
                render();
            });

            todayBtn.addEventListener('click', () => {
                currentDate = new Date();
                render();
            });

            viewBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    currentView = e.target.dataset.view;
                    render();
                });
            });

            calendarContainer.addEventListener('click', (e) => {
                const taskEl = e.target.closest('.task-item');
                if (taskEl) {
                    const taskId = taskEl.dataset.taskId;
                    openTaskModal(taskId);
                }
            });

            closeModalBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // --- GLOBAL FUNCTIONS FOR INLINE JS ---
            window.updateTaskStatus = (taskId, newStatus) => {
                const task = tasks.find(t => t.id === taskId);
                if (task) {
                    task.status = newStatus;
                    if (newStatus === 'finished') task.progress = 100;
                    render(); // Re-render calendar
                    openTaskModal(taskId); // Re-open modal to show updated state
                }
            };
            
            window.updateTaskProgress = (element, taskId) => {
                const task = tasks.find(t => t.id === taskId);
                if (task) {
                    task.progress = parseInt(element.value, 10);
                    if(task.progress === 100) task.status = 'finished';
                    else if (task.status === 'finished') task.status = 'ongoing'; // Revert if not 100%
                    
                    // Update label in modal without full re-render for smoothness
                    const progressLabel = element.previousElementSibling;
                    if(progressLabel) progressLabel.textContent = `Progress: ${task.progress}%`;
                    
                    // Debounce full re-render to avoid lag while dragging
                    clearTimeout(window.renderTimeout);
                    window.renderTimeout = setTimeout(render, 300);
                }
            };

            // --- INITIAL RENDER ---
            render();
        });
    </script>

</body>
</html>
