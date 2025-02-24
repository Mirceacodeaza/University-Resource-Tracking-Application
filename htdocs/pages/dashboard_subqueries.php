<?php
@include 'login.php';
session_start();
include '../config.php'; // Include fișierul de configurare pentru conexiunea la baza de date

// Interogarea 1: Clădiri cu săli ce se afla la etaj>2
$query1 = "SELECT c.Nume_Cladire, 
                  (SELECT COUNT(*) 
                   FROM sali s 
                   WHERE s.CladireId = c.CladireID AND s.Etaj > 2) AS Total_Sali
           FROM cladiri c
           WHERE c.CladireID IN (
               SELECT c.CladireID
               FROM cladiri c
               JOIN sali s ON c.CladireID = s.CladireID
               WHERE s.Etaj > 2
           )";

$result1 = $conn->query($query1);

// Interogarea 2: Manageri ce administrează săli cu dotări după 2023
$query2 = "SELECT m.Nume_manager, m.Prenume_manager, m.Email_manager, m.Telefon_manager
           FROM managersali m
           WHERE m.SalaID IN (
               SELECT SalaID
               FROM dotari
               WHERE Data_Achizitie > '2023-01-01'
           )";

$result2 = $conn->query($query2);

// Interogarea 3: Tipuri de dotări în săli de la etajul 1
$query3 = "SELECT s.Nume_Sala, t.NumeTipDotare
FROM sali s
JOIN sali_dotari sd ON s.SalaID = sd.SalaID
JOIN tipdotari t ON sd.DotareID = t.TipDotareId
WHERE s.SalaID IN (
    SELECT SalaID
    FROM sali
    WHERE Etaj = 1
) 
ORDER BY s.Nume_Sala;
";

$result3 = $conn->query($query3);

// Interogarea 4: Clădiri cu săli dotate cu calculatoare
$query4 = "SELECT c.Nume_Cladire, s.Nume_Sala
FROM cladiri c
JOIN sali s ON c.CladireID = s.CladireID
WHERE c.CladireID IN (
    SELECT c.CladireID
    FROM cladiri c
    JOIN sali s ON c.CladireID = s.CladireID
    WHERE s.SalaID IN (
        SELECT SalaID
        FROM dotari
        WHERE TipDotareId = (
            SELECT TipDotareId
            FROM tipdotari
            WHERE NumeTipDotare = 'Calculator'
        )
    )
)
ORDER BY c.Nume_Cladire, s.Nume_Sala;
";

$result4 = $conn->query($query4);

// Verifică erori pentru fiecare interogare
if (!$result1 || !$result2 || !$result3 || !$result4) {
    die("Query Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Interogari cu Subcereri</title>
</head>
<body>

<h2> Clădiri cu săli ce se afla la etaj>2 </h2>
<table border="1">
    <thead>
        <tr>
            <th>Nume Clădire</th>
            <th>Total Săli </th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result1->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['Nume_Cladire']); ?></td>
            <td><?php echo htmlspecialchars($row['Total_Sali']); ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<h2>Manageri ce administrează săli cu dotări după 2023</h2>
<table border="1">
    <thead>
        <tr>
            <th>Nume Manager</th>
            <th>Prenume Manager</th>
            <th>Email Manager</th>
            <th>Telefon Manager</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result2->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['Nume_manager']); ?></td>
            <td><?php echo htmlspecialchars($row['Prenume_manager']); ?></td>
            <td><?php echo htmlspecialchars($row['Email_manager']); ?></td>
            <td><?php echo htmlspecialchars($row['Telefon_manager']); ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<h2>Tipuri de dotări în săli de la etajul 1</h2>
<table border="1">
    <thead>
        <tr>
            <th>Nume_Sala</th>
            <th>Tip Dotare</th>
            
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result3->fetch_assoc()): ?>
        <tr>
        <td><?php echo htmlspecialchars($row['Nume_Sala']); ?></td>
            <td><?php echo htmlspecialchars($row['NumeTipDotare']); ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<h2> Clădiri cu săli dotate cu calculatoare</h2>
<table border="1">
    <thead>
        <tr>
            <th>Nume Clădire</th>
            <th>Nume_Sala</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result4->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['Nume_Cladire']); ?></td>
            <td><?php echo htmlspecialchars($row['Nume_Sala']); ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<p style="text-align: center;">
    <a href="dashboard.php" style="font-size: 18px; color: #blue; text-decoration: none;">Înapoi la Dashboard</a>
</p>
</body>
</html>
