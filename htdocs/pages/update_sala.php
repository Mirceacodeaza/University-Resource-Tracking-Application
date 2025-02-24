<?php
require '../config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM sali WHERE SalaID = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "No record found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cladireId = $_POST['CladireId'];
    $numeSala = $_POST['Nume_Sala'];
    $locuri = $_POST['Locuri'];
    $tipSala = $_POST['Tip_Sala'];
    $etaj = $_POST['Etaj'];

    $sqlUpdate = "UPDATE sali SET CladireId = $cladireId, Nume_Sala = '$numeSala', Locuri = $locuri, Tip_Sala = '$tipSala', Etaj = $etaj WHERE SalaID = $id";

    if ($conn->query($sqlUpdate) === TRUE) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<form method="post">
    <label>Cladire ID: <input type="number" name="CladireId" value="<?php echo htmlspecialchars($row['CladireId']); ?>"></label><br>
    <label>Nume Sala: <input type="text" name="Nume_Sala" value="<?php echo htmlspecialchars($row['Nume_Sala']); ?>"></label><br>
    <label>Locuri: <input type="number" name="Locuri" value="<?php echo htmlspecialchars($row['Locuri']); ?>"></label><br>
    <label>Tip Sala: <input type="text" name="Tip_Sala" value="<?php echo htmlspecialchars($row['Tip_Sala']); ?>"></label><br>
    <label>Etaj: <input type="number" name="Etaj" value="<?php echo htmlspecialchars($row['Etaj']); ?>"></label><br>
    <button type="submit">Update</button>
</form>
