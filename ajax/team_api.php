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




function updateMemberStatus($conn) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);


    if (!$id || !in_array($status, [0, 1], true)) {
        echo json_encode(['success' => false, 'message' => 'Invalid member or status']);
        return;
    }


    $stmt = $conn->prepare("UPDATE team_members SET status = ? WHERE id = ?");
    $stmt->bind_param("ii", $status, $id);


    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Member status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Unable to update member status']);
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
    case 'update_status':
        updateMemberStatus($conn);
        break;
    case 'delete_member':
        deleteMember($conn);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
