<?php
require '../config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Șterge sălile asociate
    $sqlDeleteRooms = "DELETE FROM sali WHERE CladireId = $id";
    if ($conn->query($sqlDeleteRooms) === TRUE) {
        // Apoi șterge clădirea
        $sqlDeleteBuilding = "DELETE FROM cladiri WHERE CladireID = $id";
        if ($conn->query($sqlDeleteBuilding) === TRUE) {
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error deleting building: " . $conn->error;
        }
    } else {
        echo "Error deleting rooms: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
