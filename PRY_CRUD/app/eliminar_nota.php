<?php
require_once '../conexion/db.php';
header('Content-Type: application/json');

try {
    // Recibir datos JSON
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? '';
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'error' => 'ID es requerido']);
        exit;
    }
    
    $sql = "DELETE FROM notas WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Nota eliminada correctamente']);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se encontró la nota']);
    }
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>