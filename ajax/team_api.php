<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config.php';

$conn = getDBConnection();
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch($action) {
    case 'get_members':
        getMembers($conn);
        break;
    case 'add_member':
        addMember($conn);
        break;
    case 'delete_member':
        deleteMember($conn);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

$conn->close();

function getMembers($conn) {
    $sql = "SELECT * FROM team_members ORDER BY name ASC";
    $result = $conn->query($sql);

    $members = [];
    while($row = $result->fetch_assoc()) {
        $members[] = $row;
    }
    echo json_encode(['success' => true, 'members' => $members]);
}

function addMember($conn) {
    $name = $_POST['name'];
    $email = $_POST['email'] ?? null;
    $password = '';
    $role = '';
    $status = 0;

    $sql = "INSERT INTO team_members (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $email, $password, $role, $status);

    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Member added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding member']);
    }
}

function deleteMember($conn) {
    $id = $_POST['id'];

    $sql = "DELETE FROM team_members WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Member deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting member']);
    }
}
?>
