<?php
// Establece la configuración de la base de datos
require_once("config.php"); // Archivo requerido para obtener las credenciales

// Crea una conexión a la base de datos utilizando la configuración anterior
$conexion = new mysqli($host, $usuario, $contrasena, $base_de_datos);

// Verifica si la conexión a la base de datos es exitosa
if ($conexion->connect_error) {
    // Si no se puede establecer la conexión, muestra un mensaje de error y termina el script
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtiene los valores del formulario enviados a través del método POST
$nombre = $_POST["nombre"];
$correo = $_POST["correo"];
$ciudad = $_POST["ciudad"];
$telefono = $_POST["telefono"];
$comentarios = $_POST["comentarios"];

// Verifica si los datos ya existen en la base de datos
$consulta_existencia = "SELECT * FROM datos_formulario WHERE nombre_completo = ? AND correo_electronico = ?";
$sentencia_existencia = $conexion->prepare($consulta_existencia);
$sentencia_existencia->bind_param("ss", $nombre, $correo);
$sentencia_existencia->execute();
$sentencia_existencia->store_result();

if ($sentencia_existencia->num_rows > 0) {
    // Si se encuentran registros coincidentes, muestra un mensaje informando que los datos ya existen
    echo "Los datos ya existen en la base de datos. No se pueden insertar duplicados.";
} else {
    // Si no se encuentran registros coincidentes, procede a insertar los datos en la base de datos
    $consulta = "INSERT INTO datos_formulario (nombre_completo, correo_electronico, ciudad, telefono, comentarios) VALUES (?, ?, ?, ?, ?)";
    $sentencia = $conexion->prepare($consulta);

    if ($sentencia === false) {
        // Si hay un error en la preparación de la consulta, muestra un mensaje de error y termina el script
        die("Error en la preparación de la consulta: " . $conexion->error);
    }

    // Vincula los parámetros con los valores del formulario
    $sentencia->bind_param("sssss", $nombre, $correo, $ciudad, $telefono, $comentarios);

    if ($sentencia->execute()) {
        // Si la inserción de datos es exitosa, muestra un mensaje de éxito
        echo "Los datos se han almacenado correctamente. ¡Gracias!";
    } else {
        // Si hay un error en la inserción de datos, muestra un mensaje de error
        echo "Error al almacenar los datos. Intente nuevamente: " . $sentencia->error;
    }
}

// Cierra la conexión a la base de datos
$conexion->close();
?>
