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

// Manejo del formulario para insertar una nueva venta
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener los datos del formulario
    $clienteNombre = $_POST["clienteNombre"];
    $clienteDireccion = $_POST["clienteDireccion"];
    $clienteTelefono = $_POST["clienteTelefono"];
    $medicamentoNombre = $_POST["medicamentoNombre"];
    $cantidad = $_POST["cantidad"];

    // Verificar si el cliente ya existe en la tabla clientes
    $sqlBuscarCliente = "SELECT id FROM clientes WHERE nombre = '$clienteNombre' LIMIT 1";
    $resultCliente = $conn->query($sqlBuscarCliente);

    if ($resultCliente->num_rows > 0) {
        // Si el cliente ya existe, obtener su ID
        $rowCliente = $resultCliente->fetch_assoc();
        $clienteId = $rowCliente["id"];
    } else {
        // Si el cliente no existe, insertar sus datos en la tabla clientes
        $sqlInsertarCliente = "INSERT INTO clientes (nombre, direccion, telefono) VALUES ('$clienteNombre', '$clienteDireccion', '$clienteTelefono')";

        if ($conn->query($sqlInsertarCliente) === TRUE) {
            $clienteId = $conn->insert_id; // Obtener el ID del cliente insertado
        } else {
            echo "Error al insertar cliente: " . $conn->error;
            $conn->close();
            exit();
        }
    }

    // Obtener el precio del medicamento de la tabla medicamentos
    $sqlBuscarMedicamento = "SELECT id, precio, stock FROM medicamentos WHERE nombre = '$medicamentoNombre' LIMIT 1";
    $resultMedicamento = $conn->query($sqlBuscarMedicamento);

    if ($resultMedicamento->num_rows > 0) {
        // Si el medicamento existe, obtener su ID, precio y stock actual
        $rowMedicamento = $resultMedicamento->fetch_assoc();
        $medicamentoId = $rowMedicamento["id"];
        $precio = $rowMedicamento["precio"];
        $stockActual = $rowMedicamento["stock"];

        // Verificar si hay suficiente stock para la venta
        if ($stockActual >= $cantidad) {
            $subtotal = $cantidad * $precio;

            // Insertar datos de la venta en la tabla ventas
            $fecha = date("Y-m-d"); // Fecha actual
            $sqlVenta = "INSERT INTO ventas (fecha, cliente_id, total) VALUES ('$fecha', '$clienteId', '$subtotal')";

            if ($conn->query($sqlVenta) === TRUE) {
                $ventaId = $conn->insert_id; // Obtener el ID de la venta insertada

                // Insertar detalles de la venta en la tabla detalles_venta
                $sqlDetallesVenta = "INSERT INTO detalles_venta (venta_id, medicamento_id, cantidad, subtotal) VALUES ('$ventaId', '$medicamentoId', '$cantidad', '$subtotal')";
                if ($conn->query($sqlDetallesVenta) === TRUE) {
                    // Actualizar el stock del medicamento
                    $nuevoStock = $stockActual - $cantidad;
                    $sqlActualizarStock = "UPDATE medicamentos SET stock = '$nuevoStock' WHERE id = '$medicamentoId'";
                    if ($conn->query($sqlActualizarStock) === TRUE) {
                        echo "Venta registrada con éxito.";
                    } else {
                        echo "Error al actualizar el stock: " . $conn->error;
                    }
                } else {
                    echo "Error al insertar detalles de la venta: " . $conn->error;
                }
            } else {
                echo "Error al insertar venta: " . $conn->error;
                $conn->close();
                exit();
            }
        } else {
            echo "No hay suficiente stock disponible para realizar la venta.";
        }
    } else {
        echo "El medicamento no existe en la base de datos.";
    }

    $conn->close();
}
?>
