<?php
require '../config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM cladiri WHERE CladireID = $id";
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
    $nume = $_POST['Nume_Cladire'];
    $adresa = $_POST['Adresa'];
    $etaje = $_POST['NrEtaje'];

    $sqlUpdate = "UPDATE cladiri SET Nume_Cladire = '$nume', Adresa = '$adresa', NrEtaje = $etaje WHERE CladireID = $id";

    if ($conn->query($sqlUpdate) === TRUE) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<form method="post">
    <label>Nume Cladire: <input type="text" name="Nume_Cladire" value="<?php echo htmlspecialchars($row['Nume_Cladire']); ?>"></label><br>
    <label>Adresa: <input type="text" name="Adresa" value="<?php echo htmlspecialchars($row['Adresa']); ?>"></label><br>
    <label>Numar Etaje: <input type="number" name="NrEtaje" value="<?php echo htmlspecialchars($row['NrEtaje']); ?>"></label><br>
    <button type="submit">Update</button>
</form>
