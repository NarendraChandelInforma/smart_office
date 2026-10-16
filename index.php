<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Task Planner - Team Management</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->

    <!-- <link rel="stylesheet" src="/assets/style.css"/> -->
    <link rel="stylesheet" type="text/css" href="assets/style.css">
    <style> 
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
      
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📋 Daily Task Planner</h1>
            <p>Team Task Management System</p>
            <div class="date-selector">
                <button onclick="changeDate(-1)">← Previous Day</button>
                <input type="date" id="taskDate" onchange="loadTasks()">
                <button onclick="changeDate(1)">Next Day →</button>
                <button onclick="setToday()">Today</button>
            </div>
        </div>

        <!-- Statistics Dashboard -->
        <div class="statistics" id="statistics">
            <div class="stat-card total">
                <div class="number" id="stat-total">0</div>
                <div class="label">Total Tasks</div>
            </div>
            <div class="stat-card completed">
                <div class="number" id="stat-completed">0</div>
                <div class="label">Completed</div>
            </div>
            <div class="stat-card in-progress">
                <div class="number" id="stat-in-progress">0</div>
                <div class="label">In Progress</div>
            </div>
            <div class="stat-card not-started">
                <div class="number" id="stat-not-started">0</div>
                <div class="label">Not Started</div>
            </div>
            <div class="stat-card blocked">
                <div class="number" id="stat-blocked">0</div>
                <div class="label">Blocked</div>
            </div>
            <div class="stat-card high">
                <div class="number" id="stat-high">0</div>
                <div class="label">High Priority</div>
            </div>
            <div class="stat-card medium">
                <div class="number" id="stat-medium">0</div>
                <div class="label">Medium Priority</div>
            </div>
            <div class="stat-card low">
                <div class="number" id="stat-low">0</div>
                <div class="label">Low Priority</div>
            </div>
        </div>

        <!-- Controls -->
        <div class="controls">
            <button class="btn btn-primary" onclick="openModal()">+ Add New Task</button>
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search tasks..." onkeyup="filterTasks()">
                <select id="filterStatus" onchange="filterTasks()">
                    <option value="">All Status</option>
                    <option value="Not Started">Not Started</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                    <option value="Blocked">Blocked</option>
                </select>
                <select id="filterPriority" onchange="filterTasks()">
                    <option value="">All Priority</option>
                    <option value="High">High</option>
                    <option value="Medium">Medium</option>
                    <option value="Low">Low</option>
                </select>
            </div>
        </div>

        <!-- Task Table -->
        <div class="task-table-container">
            <div id="loadingIndicator" class="loading" style="display: none;">
                <div class="spinner"></div>
                <p>Loading tasks...</p>
            </div>
            <button class="btn" onclick="window.location.href='manage_team.php'" style="background: #17a2b8; color: white;">👥 Manage Team</button>
            <button class="btn btn-success" onclick="exportToExcel()">📥 Export to Excel</button>
            <table id="taskTable" style="display: none;">
                <thead>
                    <tr>
                        <th>Team Member</th>
                        <th>Task Description</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Notes</th>
                        <th>Follow-up</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="taskTableBody">
                </tbody>
            </table>
            <div id="emptyState" class="empty-state" style="display: none;">
                <div style="font-size: 4em;">📝</div>
                <h3>No tasks for this date</h3>
                <p>Click "Add New Task" to create your first task</p>
            </div>
        </div>
    </div>

    <!-- Task Modal -->
    <div id="taskModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Task</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="taskForm">
                    <input type="hidden" id="taskId">
                    
                    <div class="form-group">
                        <label for="teamMember">Team Member *</label>
                        <select id="teamMember" required>
                            <option value="">Select team member...</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="taskDescription">Task Description *</label>
                        <textarea id="taskDescription" required placeholder="Enter task details..."></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="priority">Priority *</label>
                            <select id="priority" required>
                                <option value="">Select priority...</option>
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select id="status" required>
                                <option value="Not Started">Not Started</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                                <option value="Blocked">Blocked</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="startTime">Start Time</label>
                            <input type="time" id="startTime">
                        </div>

                        <div class="form-group">
                            <label for="endTime">End Time</label>
                            <input type="time" id="endTime">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes / Comments</label>
                        <textarea id="notes" placeholder="Additional information..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="followUp">Follow-up Required</label>
                        <select id="followUp">
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="saveTask()">Save Task</button>
                <button class="btn" onclick="closeModal()" style="background: #6c757d; color: white;">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        let allTasks = [];
        let teamMembers = [];

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setToday();
            loadTeamMembers();
            loadTasks();
        });

        // Set today's date
        function setToday() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('taskDate').value = today;
            loadTasks();
        }

        // Change date
        function changeDate(days) {
            const dateInput = document.getElementById('taskDate');
            const currentDate = new Date(dateInput.value);
            currentDate.setDate(currentDate.getDate() + days);
            dateInput.value = currentDate.toISOString().split('T')[0];
            loadTasks();
        }

        // Load team members
        async function loadTeamMembers() {
            try {
                const response = await fetch('ajax/api.php?action=get_team_members');
                const data = await response.json();
                
                if(data.success) {
                    teamMembers = data.members;
                    const select = document.getElementById('teamMember');
                    select.innerHTML = '<option value="">Select team member...</option>';
                    
                    data.members.forEach(member => {
                        const option = document.createElement('option');
                        option.value = member.name;
                        option.textContent = member.name;
                        select.appendChild(option);
                    });
                }
            } catch(error) {
                console.error('Error loading team members:', error);
            }
        }

        // Load tasks
        async function loadTasks() {
            const date = document.getElementById('taskDate').value;
            
            document.getElementById('loadingIndicator').style.display = 'block';
            document.getElementById('taskTable').style.display = 'none';
            document.getElementById('emptyState').style.display = 'none';
            
            try {
                const response = await fetch(`ajax/api.php?action=get_tasks&date=${date}`);
                const data = await response.json();
                
                if(data.success) {
                    allTasks = data.tasks;
                    displayTasks(allTasks);
                    loadStatistics();
                }
            } catch(error) {
                console.error('Error loading tasks:', error);
                document.getElementById('loadingIndicator').style.display = 'none';
                document.getElementById('emptyState').style.display = 'block';
            }
        }

        // Display tasks in table
        function displayTasks(tasks) {
            const tbody = document.getElementById('taskTableBody');
            tbody.innerHTML = '';
            
            document.getElementById('loadingIndicator').style.display = 'none';
            
            if(tasks.length === 0) {
                document.getElementById('taskTable').style.display = 'none';
                document.getElementById('emptyState').style.display = 'block';
                return;
            }
            
            document.getElementById('taskTable').style.display = 'table';
            document.getElementById('emptyState').style.display = 'none';
            
            tasks.forEach(task => {
                const row = document.createElement('tr');
                
                const priorityClass = `priority-${task.priority.toLowerCase()}`;
                const statusClass = `status-${task.status.toLowerCase().replace(' ', '-')}`;
                
                row.innerHTML = `
                    <td><strong>${task.team_member}</strong></td>
                    <td>${task.task_description}</td>
                    <td><span class="priority-badge ${priorityClass}">${task.priority}</span></td>
                    <td><span class="status-badge ${statusClass}">${task.status}</span></td>
                    <td>${task.start_time || '-'}</td>
                    <td>${task.end_time || '-'}</td>
                    <td>${task.notes || '-'}</td>
                    <td>${task.follow_up}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-warning btn-sm" onclick="editTask(${task.id})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteTask(${task.id})">Delete</button>
                        </div>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        // Load statistics
        async function loadStatistics() {
            const date = document.getElementById('taskDate').value;
            
            try {
                const response = await fetch(`ajax/api.php?action=get_statistics&date=${date}`);
                const data = await response.json();
                
                if(data.success) {
                    const stats = data.stats;
                    document.getElementById('stat-total').textContent = stats.total || 0;
                    document.getElementById('stat-completed').textContent = stats.completed || 0;
                    document.getElementById('stat-in-progress').textContent = stats.in_progress || 0;
                    document.getElementById('stat-not-started').textContent = stats.not_started || 0;
                    document.getElementById('stat-blocked').textContent = stats.blocked || 0;
                    document.getElementById('stat-high').textContent = stats.high_priority || 0;
                    document.getElementById('stat-medium').textContent = stats.medium_priority || 0;
                    document.getElementById('stat-low').textContent = stats.low_priority || 0;
                }
            } catch(error) {
                console.error('Error loading statistics:', error);
            }
        }

        // Filter tasks
        function filterTasks() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value;
            const priorityFilter = document.getElementById('filterPriority').value;
            
            let filteredTasks = allTasks.filter(task => {
                const matchesSearch = task.task_description.toLowerCase().includes(searchTerm) ||
                                    task.team_member.toLowerCase().includes(searchTerm) ||
                                    (task.notes && task.notes.toLowerCase().includes(searchTerm));
                
                const matchesStatus = !statusFilter || task.status === statusFilter;
                const matchesPriority = !priorityFilter || task.priority === priorityFilter;
                
                return matchesSearch && matchesStatus && matchesPriority;
            });
            
            displayTasks(filteredTasks);
        }

        // Open modal for new task
        function openModal() {
            document.getElementById('modalTitle').textContent = 'Add New Task';
            document.getElementById('taskForm').reset();
            document.getElementById('taskId').value = '';
            document.getElementById('taskModal').style.display = 'block';
        }

        // Close modal
        function closeModal() {
            document.getElementById('taskModal').style.display = 'none';
        }

        // Edit task
        function editTask(id) {
            const task = allTasks.find(t => t.id == id);
            if(!task) return;
            
            document.getElementById('modalTitle').textContent = 'Edit Task';
            document.getElementById('taskId').value = task.id;
            document.getElementById('teamMember').value = task.team_member;
            document.getElementById('taskDescription').value = task.task_description;
            document.getElementById('priority').value = task.priority;
            document.getElementById('status').value = task.status;
            document.getElementById('startTime').value = task.start_time || '';
            document.getElementById('endTime').value = task.end_time || '';
            document.getElementById('notes').value = task.notes || '';
            document.getElementById('followUp').value = task.follow_up;
            
            document.getElementById('taskModal').style.display = 'block';
        }

        // Save task (add or update)
        async function saveTask() {
            const taskId = document.getElementById('taskId').value;
            const date = document.getElementById('taskDate').value;
            
            const formData = new FormData();
            formData.append('action', taskId ? 'update_task' : 'add_task');
            if(taskId) formData.append('id', taskId);
            formData.append('task_date', date);
            formData.append('team_member', document.getElementById('teamMember').value);
            formData.append('task_description', document.getElementById('taskDescription').value);
            formData.append('priority', document.getElementById('priority').value);
            formData.append('status', document.getElementById('status').value);
            formData.append('start_time', document.getElementById('startTime').value);
            formData.append('end_time', document.getElementById('endTime').value);
            formData.append('notes', document.getElementById('notes').value);
            formData.append('follow_up', document.getElementById('followUp').value);
            
            try {
                const response = await fetch('ajax/api.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if(data.success) {
                    alert(data.message);
                    closeModal();
                    loadTasks();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch(error) {
                console.error('Error saving task:', error);
                alert('Error saving task. Please try again.');
            }
        }

        // Delete task
        async function deleteTask(id) {
            if(!confirm('Are you sure you want to delete this task?')) {
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'delete_task');
            formData.append('id', id);
            
            try {
                const response = await fetch('ajax/api.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if(data.success) {
                    alert(data.message);
                    loadTasks();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch(error) {
                console.error('Error deleting task:', error);
                alert('Error deleting task. Please try again.');
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('taskModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
    <script>
        // Export to Excel
        function exportToExcel() {
            const date = document.getElementById('taskDate').value;
            window.location.href = `ajax/export.php?date=${date}`;
        }
    </script>
</body>
</html>