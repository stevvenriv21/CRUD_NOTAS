<?php
require_once '../conexion/db.php';

// recibir datos por JSON
$request = json_decode(file_get_contents("php://input"), true);

$id = $request['id'];
$nombre = $request['nombre'];
$email = $request['email'];
$edad = $request['edad'];


$consulta = "UPDATE usuarios SET nombre = :nombre, email = :email, edad = :edad WHERE id = :id";
$stmt = $pdo->prepare($consulta);
$stmt->bindParam(':id', $id);
$stmt->bindParam(':nombre', $nombre);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':edad', $edad);

if ($stmt->execute()) {
    echo json_encode(["message" => "Usuario actualizado correctamente"]);
}

?>