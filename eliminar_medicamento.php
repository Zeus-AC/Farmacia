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

// Verificar si se ha enviado una solicitud para eliminar un medicamento
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["medicamentoId"])) {
    $medicamentoId = $_POST["medicamentoId"];

    // Eliminar el medicamento de la tabla medicamentos
    $sqlEliminarMedicamento = "DELETE FROM medicamentos WHERE id = '$medicamentoId'";
    if ($conn->query($sqlEliminarMedicamento) === TRUE) {
        echo "Medicamento eliminado con éxito.";
    } else {
        echo "Error al eliminar el medicamento: " . $conn->error;
    }
}

$conn->close();
?>
