<?php
header('Content-Type: application/json');
require_once 'config.php';

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
    print_r($members);
    exit;
    echo json_encode(['success' => true, 'members' => $members]);
}

function addMember($conn) {
    $name = $_POST['name'];
    $email = $_POST['email'] ?? null;
    
    $sql = "INSERT INTO team_members (name, email) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $email);
    
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