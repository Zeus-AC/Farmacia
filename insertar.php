<?php
// Datos de conexión a la base de datos
$servername = "127.0.0.1"; // Cambiar por el servidor de la base de datos
$username = "root"; // Cambiar por el nombre de usuario de la base de datos
$password = ""; // Cambiar por la contraseña de la base de datos
$dbname = "farmacia"; // Cambiar por el nombre de la base de datos que creaste

// Obtener los datos del formulario de inventario
$medicamento = $_POST['medicamento'];
$descripcion = $_POST['descripcion'];
$precio = floatval($_POST['precio']);
$stock = intval($_POST['stock']);

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Preparar la consulta SQL para insertar el medicamento en el inventario
$sql = "INSERT INTO medicamentos (nombre, descripcion, precio, stock) VALUES ('$medicamento', '$descripcion', $precio, $stock)";

if ($conn->query($sql) === TRUE) {
    echo "Medicamento agregado al inventario correctamente.";
} else {
    echo "Error al agregar el medicamento al inventario: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>












