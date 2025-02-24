<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../templates/header.php'; 
?>

<h2 style="color: gray; text-align: center; margin: 20px 0;">
    Bine ai venit, <?php echo htmlspecialchars($_SESSION['username']); ?>!
</h2>

<h2>Inserare date</h2>
<ul>
    <li><a href="/pages/insert_data.php?table=cladiri">Adaugă Clădire</a></li>
    <li><a href="/pages/insert_data.php?table=sali">Adaugă Sală</a></li>
    <li><a href="/pages/insert_data.php?table=dotari">Adaugă Dotare</a></li>
    <li><a href="/pages/insert_data.php?table=managersali">Adaugă Manager Sală</a></li>
    <li><a href="/pages/insert_data.php?table=sali_dotari">Adaugă Sală-Dotare</a></li>
    <li><a href="/pages/insert_data.php?table=tipdotari">Adaugă Tip Dotare</a></li>
    <li><a href="/pages/insert_data.php?table=supervizordotari">Adaugă Supervizor Dotare</a></li>
</ul>




<h2>Informații suplimentare</h2>
<form method="get" action="dashboard.php">
    <label for="filter">Selectează un filtru:</label>
    <select name="filter" id="filter">
        <option value="1">Săli și Dotări pentru o Sală</option>
        <option value="2">Săli,clădirile în care se află și managerii lor</option>
        <option value="3">Dotări și supervizorii lor pentru o sală</option>
        <option value="4">Săli, Locuri și Dotări într-o Clădire</option>
        <option value="5">Dotări funcționale dintr-o sală</option>
        <option value="6">Săli și dotări pentru un supervizor</option>
    </select>
    <input type="text" name="search" placeholder="Introduceți valoarea de căutare" required>
    <button type="submit">Aplică Filtru</button>
</form>

<?php
if (isset($_GET['filter']) && isset($_GET['search'])) {
    $filter = intval($_GET['filter']);
    $search = $conn->real_escape_string($_GET['search']);

    switch ($filter) {
        case 1:
            $query = "SELECT c.Nume_Cladire, s.Nume_Sala, t.NumeTipDotare
          FROM cladiri c
          JOIN sali s ON c.CladireID = s.CladireId
          JOIN sali_dotari sd ON s.SalaID = sd.SalaID
          JOIN tipdotari t ON sd.DotareID = t.TipDotareId
          WHERE s.Nume_Sala = 'Sala EC101'";
            break;

        case 2:
            $query = "SELECT s.Nume_Sala, c.Nume_Cladire, m.Nume_manager, m.Prenume_manager
FROM sali s
JOIN cladiri c ON s.CladireId = c.CladireID
JOIN managersali m ON s.SalaID = m.SalaID
WHERE c.Nume_Cladire = '$search'";
            break;

        case 3:
            $query = "SELECT t.NumeTipDotare, d.NumeDotare, s.Nume_Sala, sp.Nume_supervizor, sp.Prenume_supervizor
                      FROM dotari d
                      JOIN sali s ON d.SalaID = s.SalaID
                      JOIN tipdotari t ON d.TipDotareId = t.TipDotareId
                      JOIN supervizordotari sp ON d.SupervizorId = sp.SupervizorId
                      WHERE s.Nume_Sala = '$search'";
            break;

        case 4:
            $query = "SELECT s.Nume_Sala, c.Nume_Cladire, t.NumeTipDotare, s.Locuri
FROM sali s
JOIN cladiri c ON s.CladireId = c.CladireID
JOIN sali_dotari sd ON s.SalaID = sd.SalaID
JOIN tipdotari t ON sd.DotareID = t.TipDotareId
WHERE c.Nume_Cladire = '$search'";
            break;

        case 5:
            $query = "SELECT t.NumeTipDotare, d.NumeDotare, d.Data_Achizitie
                      FROM dotari d
                      JOIN tipdotari t ON d.TipDotareId = t.TipDotareId
                      JOIN sali s ON d.SalaID = s.SalaID
                      WHERE s.Nume_Sala = '$search' AND d.Stare_Dotare = 'Functional'";
            break;

        case 6:
            $query = "SELECT s.Nume_Sala, t.NumeTipDotare, sp.Nume_supervizor, sp.Prenume_supervizor
                      FROM dotari d
                      JOIN sali s ON d.SalaID = s.SalaID
                      JOIN tipdotari t ON d.TipDotareId = t.TipDotareId
                      JOIN supervizordotari sp ON d.SupervizorId = sp.SupervizorId
                      WHERE sp.Nume_supervizor = '$search'";
            break;

        default:
            echo "<p>Filtru invalid!</p>";
            exit;
    }

    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr>";

        // Afișează antetele tabelului
        $columns = array_keys($result->fetch_assoc());
        foreach ($columns as $column) {
            echo "<th>$column</th>";
        }
        echo "</tr>";

        // Resetează pointerul și afișează rândurile
        $result->data_seek(0);
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Nicio înregistrare găsită.</p>";
    }
}
?>


<li><a href="dashboard_subqueries.php">Mai multe detalii</a></li>









<!-- Cladiri -->
<h2>Clădiri</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nume Cladire</th>
        <th>Adresa</th>
        <th>Numar etaje</th>
        <th>Acțiuni</th>
    </tr>
    <?php
    $sqlBuildings = "SELECT * FROM cladiri";
    $resultBuildings = $conn->query($sqlBuildings);

    if ($resultBuildings->num_rows > 0) {
        while ($row = $resultBuildings->fetch_assoc()) {
            echo "<tr>
                <td>{$row['CladireID']}</td>
                <td>{$row['Nume_Cladire']}</td>
                <td>{$row['Adresa']}</td>
                <td>{$row['NrEtaje']}</td>
                <td>
                    <a href='update_cladire.php?id={$row['CladireID']}'>Edit</a> | 
                    <a href='delete_cladire.php?id={$row['CladireID']}' onclick='return confirm(\"Ești sigur că vrei să ștergi această clădire?\")'>Delete</a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No data available</td></tr>";
    }
    ?>
</table>


<!-- Sali -->
<h2>Săli</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Cladire ID</th>
        <th>Denumirea Salii</th>
        <th>Locuri</th>
        <th>Tip Sala</th>
        <th>Etaj</th>
        <th>Acțiuni</th>
    </tr>
    <?php
    $sqlRooms = "SELECT * FROM sali";
    $resultRooms = $conn->query($sqlRooms);

    if ($resultRooms->num_rows > 0) {
        while ($row = $resultRooms->fetch_assoc()) {
            echo "<tr>
                <td>{$row['SalaID']}</td>
                <td>{$row['CladireId']}</td>
                <td>{$row['Nume_Sala']}</td>
                <td>{$row['Locuri']}</td>
                <td>{$row['Tip_Sala']}</td>
                <td>{$row['Etaj']}</td>
                <td>
                    <a href='update_sala.php?id={$row['SalaID']}'>Edit</a> | 
                    <a href='delete_sala.php?id={$row['SalaID']}' onclick='return confirm(\"Ești sigur că vrei să ștergi această sală?\")'>Delete</a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No data available</td></tr>";
    }
    ?>
</table>


<!-- Dotari -->
<h2>Dotări</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Tip Dotare ID</th>
        <th>Sala ID</th>
        <th>Nume Dotare</th>
        <th>Cantitate</th>
        <th>Stare Dotare</th>
        <th>Data Achizitie</th>
        <th>Supervizor ID</th>
    </tr>
    <?php
    $sqlEquipment = "SELECT * FROM dotari";
    $resultEquipment = $conn->query($sqlEquipment);

    if ($resultEquipment->num_rows > 0) {
        while ($row = $resultEquipment->fetch_assoc()) {
            echo "<tr>
                <td>{$row['DotareID']}</td>
                <td>{$row['TipDotareId']}</td>
                <td>{$row['SalaID']}</td>
                <td>{$row['NumeDotare']}</td>
                <td>{$row['Cantitate']}</td>
                <td>{$row['Stare_Dotare']}</td>
                <td>{$row['Data_Achizitie']}</td>
                <td>{$row['SupervizorId']}</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='8'>No data available</td></tr>";
    }
    ?>
</table>

<!-- Managersali -->
<h2>Manageri Săli</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Sala ID</th>
        <th>Nume Manager</th>
        <th>Prenume Manager</th>
        <th>Email Manager</th>
        <th>Telefon Manager</th>
    </tr>
    <?php
    $sqlManagers = "SELECT * FROM managersali";
    $resultManagers = $conn->query($sqlManagers);

    if ($resultManagers->num_rows > 0) {
        while ($row = $resultManagers->fetch_assoc()) {
            echo "<tr>
                <td>{$row['ManagerId']}</td>
                <td>{$row['SalaID']}</td>
                <td>{$row['Nume_manager']}</td>
                <td>{$row['Prenume_manager']}</td>
                <td>{$row['Email_manager']}</td>
                <td>{$row['Telefon_manager']}</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No data available</td></tr>";
    }
    ?>
</table>

<!-- Sali Dotari -->
<h2>Săli_Dotări</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Sala ID</th>
        <th>Dotare ID</th>
    </tr>
    <?php
    $sqlRoomEquipment = "SELECT * FROM sali_dotari";
    $resultRoomEquipment = $conn->query($sqlRoomEquipment);

    if ($resultRoomEquipment->num_rows > 0) {
        while ($row = $resultRoomEquipment->fetch_assoc()) {
            echo "<tr>
                <td>{$row['SalaDotariID']}</td>
                <td>{$row['SalaID']}</td>
                <td>{$row['DotareID']}</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No data available</td></tr>";
    }
    ?>
</table>

<!-- Tip Dotari -->
<h2>Tip Dotări</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Numele tipului de dotare</th>
        <th>Descriere</th>
    </tr>
    <?php
    $sqlEquipmentTypes = "SELECT * FROM tipdotari";
    $resultEquipmentTypes = $conn->query($sqlEquipmentTypes);

    if ($resultEquipmentTypes->num_rows > 0) {
        while ($row = $resultEquipmentTypes->fetch_assoc()) {
            echo "<tr>
                <td>{$row['TipDotareId']}</td>
                <td>{$row['NumeTipDotare']}</td>
                <td>{$row['Descriere']}</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No data available</td></tr>";
    }
    ?>
</table>

<!-- Supervizori Dotari -->
<h2>Supervizori Dotări</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nume</th>
        <th>Prenume</th>
        <th>Email</th>
        <th>Telefon</th>
        <th>Specializare</th>
    </tr>
    <?php
    $sqlSupervisors = "SELECT * FROM supervizordotari";
    $resultSupervisors = $conn->query($sqlSupervisors);

    if ($resultSupervisors->num_rows > 0) {
        while ($row = $resultSupervisors->fetch_assoc()) {
            echo "<tr>
                <td>{$row['SupervizorId']}</td>
                <td>{$row['Nume_supervizor']}</td>
                <td>{$row['Prenume_supervizor']}</td>
                <td>{$row['Email']}</td>
                <td>{$row['Telefon']}</td>
                <td>{$row['Specializare']}</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No data available</td></tr>";
    }
    ?>
</table>


<?php include '../templates/footer.php'; ?>
