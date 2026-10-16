<?php
header('Content-Type: application/json');
require_once 'config.php';

$conn = getDBConnection();
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch($action) {
    case 'get_tasks':
        getTasks($conn);
        break;
    case 'add_task':
        addTask($conn);
        break;
    case 'update_task':
        updateTask($conn);
        break;
    case 'delete_task':
        deleteTask($conn);
        break;
    case 'get_team_members':
        getTeamMembers($conn);
        break;
    case 'get_statistics':
        getStatistics($conn);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

$conn->close();

// Get all tasks
function getTasks($conn) {
    $date = $_GET['date'] ?? date('Y-m-d');
    
    $sql = "SELECT * FROM tasks WHERE task_date = ? ORDER BY 
            FIELD(priority, 'High', 'Medium', 'Low'),
            start_time ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $tasks = [];
    while($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
    
    echo json_encode(['success' => true, 'tasks' => $tasks]);
}

// Add new task
function addTask($conn) {
    $task_date = $_POST['task_date'];
    $team_member = $_POST['team_member'];
    $task_description = $_POST['task_description'];
    $priority = $_POST['priority'];
    $status = $_POST['status'] ?? 'Not Started';
    $start_time = $_POST['start_time'] ?? null;
    $end_time = $_POST['end_time'] ?? null;
    $notes = $_POST['notes'] ?? '';
    $follow_up = $_POST['follow_up'] ?? 'No';
    
    $sql = "INSERT INTO tasks (task_date, team_member, task_description, priority, status, 
            start_time, end_time, notes, follow_up) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $task_date, $team_member, $task_description, $priority, 
                      $status, $start_time, $end_time, $notes, $follow_up);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Task added successfully', 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding task: ' . $conn->error]);
    }
}

// Update task
function updateTask($conn) {
    $id = $_POST['id'];
    $task_date = $_POST['task_date'];
    $team_member = $_POST['team_member'];
    $task_description = $_POST['task_description'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $start_time = $_POST['start_time'] ?? null;
    $end_time = $_POST['end_time'] ?? null;
    $notes = $_POST['notes'] ?? '';
    $follow_up = $_POST['follow_up'];
    
    // If status is changed to Completed, set actual_completion
    $actual_completion = null;
    if($status === 'Completed') {
        $actual_completion = date('Y-m-d H:i:s');
    }
    
    $sql = "UPDATE tasks SET task_date=?, team_member=?, task_description=?, priority=?, 
            status=?, start_time=?, end_time=?, actual_completion=?, notes=?, follow_up=? 
            WHERE id=?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssi", $task_date, $team_member, $task_description, $priority, 
                      $status, $start_time, $end_time, $actual_completion, $notes, $follow_up, $id);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Task updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating task: ' . $conn->error]);
    }
}

// Delete task
function deleteTask($conn) {
    $id = $_POST['id'];
    
    $sql = "DELETE FROM tasks WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Task deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting task: ' . $conn->error]);
    }
}

// Get team members
function getTeamMembers($conn) {
    $sql = "SELECT * FROM team_members ORDER BY name ASC";
    $result = $conn->query($sql);
    
    $members = [];
    while($row = $result->fetch_assoc()) {
        $members[] = $row;
    }
    
    echo json_encode(['success' => true, 'members' => $members]);
}

// Get statistics
function getStatistics($conn) {
    $date = $_GET['date'] ?? date('Y-m-d');
    
    $stats = [];
    
    // Total tasks
    $sql = "SELECT COUNT(*) as total FROM tasks WHERE task_date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $stats['total'] = $stmt->get_result()->fetch_assoc()['total'];
    
    // By status
    $statuses = ['Not Started', 'In Progress', 'Completed', 'Blocked'];
    foreach($statuses as $status) {
        $sql = "SELECT COUNT(*) as count FROM tasks WHERE task_date = ? AND status = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $date, $status);
        $stmt->execute();
        $stats[strtolower(str_replace(' ', '_', $status))] = $stmt->get_result()->fetch_assoc()['count'];
    }
    
    // By priority
    $priorities = ['High', 'Medium', 'Low'];
    foreach($priorities as $priority) {
        $sql = "SELECT COUNT(*) as count FROM tasks WHERE task_date = ? AND priority = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $date, $priority);
        $stmt->execute();
        $stats[strtolower($priority) . '_priority'] = $stmt->get_result()->fetch_assoc()['count'];
    }
    
    echo json_encode(['success' => true, 'stats' => $stats]);
}
?>