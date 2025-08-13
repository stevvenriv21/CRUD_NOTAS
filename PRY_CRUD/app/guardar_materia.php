<?php
// conecar a base de datos con conexion/db.php
require_once '../conexion/db.php';

// recibir los datos del formulario

    $nombre = strtoupper($_POST['nombre']);
    $nrc = $_POST['nrc'];

    //  ingresar los datos en la base de datos
    $sql = "INSERT INTO materias (nombre, nrc) VALUES (:nombre, :nrc)";
    // enviar varias con binparam para evitar inyeccion sql
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':nrc', $nrc);
    if ($stmt->execute()) {
    echo "La materia $nombre ha sido creada";
} else {
    echo "Error al crear la materia";
}
?>