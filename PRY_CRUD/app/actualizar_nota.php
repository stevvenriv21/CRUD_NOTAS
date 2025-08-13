<?php
require_once '../conexion/db.php';
header('Content-Type: application/json');

try {
    // Recibir datos JSON
    $input = json_decode(file_get_contents('php://input'), true);
    
    $id = $input['id'] ?? '';
    $n1 = isset($input['n1']) ? floatval($input['n1']) : null;
    $n2 = isset($input['n2']) ? floatval($input['n2']) : null;
    $n3 = isset($input['n3']) ? floatval($input['n3']) : null;
    
    if (empty($id) || $n1 === null || $n2 === null || $n3 === null) {
        echo json_encode(['success' => false, 'error' => 'Todos los campos son requeridos']);
        exit;
    }
    
    // Validar rango de notas
    if ($n1 < 0 || $n1 > 20 || $n2 < 0 || $n2 > 20 || $n3 < 0 || $n3 > 20) {
        echo json_encode(['success' => false, 'error' => 'Las notas deben estar entre 0 y 20']);
        exit;
    }
    
    // Calcular promedio
    $promedio = ($n1 + $n2 + $n3) / 3;
    
    $sql = "UPDATE notas SET n1 = ?, n2 = ?, n3 = ?, promedio = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$n1, $n2, $n3, $promedio, $id]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Nota actualizada correctamente']);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se encontró la nota o no se realizaron cambios']);
    }
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>