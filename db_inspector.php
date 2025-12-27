<?php
include("includes/conexion.php");

// Cambia esto SI tu base de datos tiene otro nombre
$database_name = "music_db";

echo "<h1>Inspector de Base de Datos: $database_name</h1>";

echo "<h2>📌 Tablas encontradas:</h2>";

// Obtener todas las tablas
$tables = mysqli_query($conexion, "SHOW TABLES");

while ($t = mysqli_fetch_array($tables)) {
    $table = $t[0];
    echo "<h3 style='margin-top:30px;'>🔹 Tabla: <strong>$table</strong></h3>";

    // Obtener columnas de esa tabla
    $columns = mysqli_query($conexion, "SHOW COLUMNS FROM $table");

    echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse;'>
            <tr style='background: #ddd;'>
                <th>Campo</th>
                <th>Tipo</th>
                <th>Nulo</th>
                <th>Clave</th>
                <th>Default</th>
                <th>Extra</th>
            </tr>";

    while ($col = mysqli_fetch_assoc($columns)) {
        echo "<tr>
                <td>{$col['Field']}</td>
                <td>{$col['Type']}</td>
                <td>{$col['Null']}</td>
                <td>{$col['Key']}</td>
                <td>{$col['Default']}</td>
                <td>{$col['Extra']}</td>
              </tr>";
    }

    echo "</table>";
}

echo "<br><br><p>✔ Inspección completada.</p>";
?>
