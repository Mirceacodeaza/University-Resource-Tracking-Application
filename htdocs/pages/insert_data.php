<?php
require '../config.php';

// Verifică tabelul selectat din URL
$table = isset($_GET['table']) ? $_GET['table'] : 'cladiri';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    switch ($table) {
        case 'cladiri':
            $nume = $_POST['Nume_Cladire'];
            $adresa = $_POST['Adresa'];
            $nr_etaje = $_POST['NrEtaje'];
            $sql = "INSERT INTO cladiri (Nume_Cladire, Adresa, NrEtaje) VALUES ('$nume', '$adresa', $nr_etaje)";
            break;

        case 'sali':
            $cladire_id = $_POST['CladireId'];
            $nume_sala = $_POST['Nume_Sala'];
            $locuri = $_POST['Locuri'];
            $tip_sala = $_POST['Tip_Sala'];
            $etaj = $_POST['Etaj'];
            $sql = "INSERT INTO sali (CladireId, Nume_Sala, Locuri, Tip_Sala, Etaj) VALUES ($cladire_id, '$nume_sala', $locuri, '$tip_sala', $etaj)";
            break;

        case 'dotari':
            $tip_dotare_id = $_POST['TipDotareId'];
            $sala_id = $_POST['SalaID'];
            $nume_dotare = $_POST['NumeDotare'];
            $cantitate = $_POST['Cantitate'];
            $stare_dotare = $_POST['Stare_Dotare'];
            $data_achizitie = $_POST['Data_Achizitie'];
            $supervizor_id = $_POST['SupervizorId'];
            $sql = "INSERT INTO dotari (TipDotareId, SalaID, NumeDotare, Cantitate, Stare_Dotare, Data_Achizitie, SupervizorId) 
                    VALUES ($tip_dotare_id, $sala_id, '$nume_dotare', $cantitate, '$stare_dotare', '$data_achizitie', $supervizor_id)";
            break;

        case 'managersali':
            $sala_id = $_POST['SalaID'];
            $nume_manager = $_POST['Nume_manager'];
            $prenume_manager = $_POST['Prenume_manager'];
            $email_manager = $_POST['Email_manager'];
            $telefon_manager = $_POST['Telefon_manager'];
            $sql = "INSERT INTO managersali (SalaID, Nume_manager, Prenume_manager, Email_manager, Telefon_manager) 
                    VALUES ($sala_id, '$nume_manager', '$prenume_manager', '$email_manager', '$telefon_manager')";
            break;

        case 'sali_dotari':
            $sala_id = $_POST['SalaID'];
            $dotare_id = $_POST['DotareID'];
            $sql = "INSERT INTO sali_dotari (SalaID, DotareID) VALUES ($sala_id, $dotare_id)";
            break;

        case 'tipdotari':
            $nume_tip_dotare = $_POST['NumeTipDotare'];
            $descriere = $_POST['Descriere'];
            $sql = "INSERT INTO tipdotari (NumeTipDotare, Descriere) VALUES ('$nume_tip_dotare', '$descriere')";
            break;

        case 'supervizordotari':
            $nume = $_POST['Nume_supervizor'];
            $prenume = $_POST['Prenume_supervizor'];
            $email = $_POST['Email'];
            $telefon = $_POST['Telefon'];
            $specializare = $_POST['Specializare'];
            $sql = "INSERT INTO supervizordotari (Nume_supervizor, Prenume_supervizor, Email, Telefon, Specializare) 
                    VALUES ('$nume', '$prenume', '$email', '$telefon', '$specializare')";
            break;
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <title>Inserare <?php echo ucfirst($table); ?></title>
</head>
<body>
    <h2>Inserare <?php echo ucfirst($table); ?></h2>
    <form method="post">
        <?php if ($table == 'cladiri'): ?>
            <label>Nume Cladire: <input type="text" name="Nume_Cladire" required></label><br>
            <label>Adresa: <input type="text" name="Adresa" required></label><br>
            <label>Numar Etaje: <input type="number" name="NrEtaje" required></label><br>
        <?php elseif ($table == 'sali'): ?>
            <label>Cladire ID: <input type="number" name="CladireId" required></label><br>
            <label>Nume Sala: <input type="text" name="Nume_Sala" required></label><br>
            <label>Locuri: <input type="number" name="Locuri" required></label><br>
            <label>Tip Sala: <input type="text" name="Tip_Sala" required></label><br>
            <label>Etaj: <input type="number" name="Etaj" required></label><br>
        <?php elseif ($table == 'dotari'): ?>
            <label>Tip Dotare ID: <input type="number" name="TipDotareId" required></label><br>
            <label>Sala ID: <input type="number" name="SalaID" required></label><br>
            <label>Nume Dotare: <input type="text" name="NumeDotare" required></label><br>
            <label>Cantitate: <input type="number" name="Cantitate" required></label><br>
            <label>Stare Dotare: <input type="text" name="Stare_Dotare" required></label><br>
            <label>Data Achizitie: <input type="date" name="Data_Achizitie" required></label><br>
            <label>Supervizor ID: <input type="number" name="SupervizorId" required></label><br>
        <?php elseif ($table == 'managersali'): ?>
            <label>Sala ID: <input type="number" name="SalaID" required></label><br>
            <label>Nume Manager: <input type="text" name="Nume_manager" required></label><br>
            <label>Prenume Manager: <input type="text" name="Prenume_manager" required></label><br>
            <label>Email Manager: <input type="email" name="Email_manager" required></label><br>
            <label>Telefon Manager: <input type="text" name="Telefon_manager" required></label><br>
        <?php elseif ($table == 'sali_dotari'): ?>
            <label>Sala ID: <input type="number" name="SalaID" required></label><br>
            <label>Dotare ID: <input type="number" name="DotareID" required></label><br>
        <?php elseif ($table == 'tipdotari'): ?>
            <label>Nume Tip Dotare: <input type="text" name="NumeTipDotare" required></label><br>
            <label>Descriere: <input type="text" name="Descriere" required></label><br>
        <?php elseif ($table == 'supervizordotari'): ?>
            <label>Nume: <input type="text" name="Nume_supervizor" required></label><br>
            <label>Prenume: <input type="text" name="Prenume_supervizor" required></label><br>
            <label>Email: <input type="email" name="Email" required></label><br>
            <label>Telefon: <input type="text" name="Telefon" required></label><br>
            <label>Specializare: <input type="text" name="Specializare" required></label><br>
        <?php endif; ?>
        <button type="submit">Insert</button>
    </form>
</body>
<p style="text-align: center;">
    <a href="dashboard.php" style="font-size: 18px; color: #blue; text-decoration: none;">Înapoi la Dashboard</a>
</p>
</html>
