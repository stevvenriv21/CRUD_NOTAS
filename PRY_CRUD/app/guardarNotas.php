<?php

require_once '../conexion/db.php';

//guardar datos en la base de datoss


$usuario = $_POST['usuario_id'];
$materia = $_POST['materia_id'];
$nota_1 = $_POST['nota_1'];
$nota_2 = $_POST['nota_2'];
$nota_3 = $_POST['nota_3'];

$promedio = ($nota_1 + $nota_2 + $nota_3) / 3;


//guardar los datos en la base de datos en la tabla notas
$sql = $pdo->prepare("INSERT INTO notas (usuario_id, materia_id, n1, n2, n3, promedio) VALUES (:usuario_id, :materia_id, :nota_1, :nota_2, :nota_3, :promedio)");

$sql->bindParam(':usuario_id', $usuario);
$sql->bindParam(':materia_id', $materia);
$sql->bindParam(':nota_1', $nota_1);
$sql->bindParam(':nota_2', $nota_2);
$sql->bindParam(':nota_3', $nota_3);
$sql->bindParam(':promedio', $promedio);
$sql->execute();

echo "Notas guardadas correctamente";
?>