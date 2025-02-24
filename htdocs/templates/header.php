<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .menu {
            background-color: grey;
            color: white;
            padding: 10px;
            width: 100%;
            text-align: center;
        }
        .logout-button {
            background-color: none;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }
        .logout-button:hover {
            opacity: 0.8;
        }
        table {
            margin: 20px 0;
            border-collapse: collapse;
            width: 80%;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
        h2 {
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="menu">
        <h1>Meniu</h1>
    
        <a href="../logout.php" class="logout-button">Logout</a>
    </div>
</body>
</html>
