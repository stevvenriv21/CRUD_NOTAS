<?php
require_once '../conexion/db.php';
header('Content-Type: application/json');

try {
    // Recibir datos JSON
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? '';
    
    if (empty($id)) {
        echo json_encode(['error' => 'ID es requerido']);
        exit;
    }
    
    $sql = "SELECT id, n1, n2, n3, promedio FROM notas WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $nota = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($nota) {
        echo json_encode($nota);
    } else {
        echo json_encode(['error' => 'Nota no encontrada']);
    }
    
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>