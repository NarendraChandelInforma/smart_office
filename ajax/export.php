<?php
require_once __DIR__ . '/../config.php';

$conn = getDBConnection();
$date = $_GET['date'] ?? date('Y-m-d');

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="tasks_' . $date . '.xls"');
header('Pragma: no-cache');
header('Expires: 0');

// Get tasks
$sql = "SELECT * FROM tasks WHERE task_date = ? ORDER BY 
        FIELD(priority, 'High', 'Medium', 'Low'), start_time ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

// Output Excel content
echo '<table border="1">';
echo '<thead>';
echo '<tr>';
echo '<th>Date</th>';
echo '<th>Team Member</th>';
echo '<th>Task Description</th>';
echo '<th>Priority</th>';
echo '<th>Status</th>';
echo '<th>Start Time</th>';
echo '<th>End Time</th>';
echo '<th>Actual Completion</th>';
echo '<th>Notes</th>';
echo '<th>Follow-up Required</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

while($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . $row['task_date'] . '</td>';
    echo '<td>' . htmlspecialchars($row['team_member']) . '</td>';
    echo '<td>' . htmlspecialchars($row['task_description']) . '</td>';
    echo '<td>' . $row['priority'] . '</td>';
    echo '<td>' . $row['status'] . '</td>';
    echo '<td>' . ($row['start_time'] ?? '') . '</td>';
    echo '<td>' . ($row['end_time'] ?? '') . '</td>';
    echo '<td>' . ($row['actual_completion'] ?? '') . '</td>';
    echo '<td>' . htmlspecialchars($row['notes'] ?? '') . '</td>';
    echo '<td>' . $row['follow_up'] . '</td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

$conn->close();
?>