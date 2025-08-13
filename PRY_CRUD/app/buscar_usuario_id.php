<?php
require_once '../conexion/db.php';

// Forzar la cabecera JSON
header('Content-Type: application/json');

// recibir datos por JSON
$request = json_decode(file_get_contents("php://input"), true);

$id = $request['id'] ?? null;

if (!$id) {
    echo json_encode(['error' => 'ID no proporcionado']);
    exit;
}

// preparar la consulta
$consulta = "SELECT * FROM usuarios WHERE id = :id";
$stmt = $pdo->prepare($consulta);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario) {
    echo json_encode($usuario);
} else {
    echo json_encode(['error' => 'Usuario no encontrado']);
}
?>
