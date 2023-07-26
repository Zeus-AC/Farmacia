<?php
$servername = "127.0.0.1"; // Cambiar por el servidor de la base de datos
$username = "root"; // Cambiar por el nombre de usuario de la base de datos
$password = ""; // Cambiar por la contraseña de la base de datos
$dbname = "farmacia"; // Cambiar por el nombre de la base de datos que creaste

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// Obtener el nombre de la tabla seleccionada desde el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["tabla"])) {
    $tablaSeleccionada = $_POST["tabla"];

    // Función para obtener los registros de una tabla específica
    function consultarTabla($conn, $tabla) {
        $sql = "SELECT * FROM $tabla";
        $result = $conn->query($sql);
        $registros = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $registros[] = $row;
            }
        }
        return $registros;
    }

    // Obtener los registros de la tabla seleccionada
    $registros = consultarTabla($conn, $tablaSeleccionada);
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Consulta de Tablas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Consulta de Tablas</h1>
    <form method="post" action="consultar_tablas.php">
        <label for="tabla">Selecciona una tabla:</label>
        <select name="tabla" id="tabla">
            <option value="medicamentos" <?php if (isset($tablaSeleccionada) && $tablaSeleccionada === "medicamentos") echo "selected"; ?>>medicamentos</option>
            <option value="clientes" <?php if (isset($tablaSeleccionada) && $tablaSeleccionada === "clientes") echo "selected"; ?>>clientes</option>
            <option value="ventas" <?php if (isset($tablaSeleccionada) && $tablaSeleccionada === "ventas") echo "selected"; ?>>ventas</option>
            <option value="detalles_venta" <?php if (isset($tablaSeleccionada) && $tablaSeleccionada === "detalles_venta") echo "selected"; ?>>detalles_venta</option>
        </select>
        <input type="submit" value="Consultar">
    </form>

    <?php
    // Mostrar los registros de la tabla seleccionada en una tabla HTML
    if (isset($registros)) {
        if (!empty($registros)) {
            echo "<div class='tabla-consultada'>";
            echo "<h2>$tablaSeleccionada</h2>";
            echo "<table>";
            echo "<tr>";
            foreach ($registros[0] as $columna => $valor) {
                echo "<th>$columna</th>";
            }
            echo "</tr>";
            foreach ($registros as $registro) {
                echo "<tr>";
                foreach ($registro as $valor) {
                    echo "<td>$valor</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        } else {
            echo "<p>No hay registros en la tabla seleccionada.</p>";
        }
    }
    ?>
</body>
</html>
