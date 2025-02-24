<?php
require '../config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Șterge managerii asociați sălii
    $sqlDeleteManagers = "DELETE FROM managersali WHERE SalaID = $id";
    if ($conn->query($sqlDeleteManagers) === TRUE) {
        // Apoi șterge sala
        $sqlDeleteRoom = "DELETE FROM sali WHERE SalaID = $id";
        if ($conn->query($sqlDeleteRoom) === TRUE) {
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error deleting room: " . $conn->error;
        }
    } else {
        echo "Error deleting managers: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
