<?php
require_once '../conexion/db.php';
$sql = "SELECT * FROM usuarios";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sqlMaterias = "SELECT * FROM materias";
$stmtMaterias = $pdo->prepare($sqlMaterias);
$stmtMaterias->execute();
$materias = $stmtMaterias->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar Notas</title>
    <link rel="stylesheet" href="../public/lib/bootstrap-5.3.5-dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            min-height: 100vh;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }
        .form-label {
            font-weight: 500;
        }
        .btn-primary {
            background: linear-gradient(90deg, #007bff 60%, #00c6ff 100%);
            border: none;
        }
        .btn-secondary {
            background: #f8f9fa;
            color: #007bff;
            border: 1px solid #007bff;
        }
        .btn-secondary:hover {
            background: #e2e6ea;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card p-4">
                    <h2 class="mb-4 text-center text-primary">Ingresar Notas</h2>
                    <form id="guardarNotas">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <select class="form-select" id="usuario" name="usuario_id" required>
                                <option value="">Seleccione un usuario</option>
                                <?php foreach ($usuarios as $user): ?>
                                    <option value="<?php echo $user['id']; ?>"><?php echo $user['nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="materia" class="form-label">Materia</label>
                            <select class="form-select" id="materia" name="materia_id" required>
                                <option value="">Seleccione una materia</option>
                                <?php foreach ($materias as $materia): ?>
                                    <option value="<?php echo $materia['id']; ?>"><?php echo $materia['nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label for="nota1" class="form-label">Parcial 1</label>
                                <input type="number" class="form-control" id="nota1" name="nota_1" step="0.01" min="0" max="10" required>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label for="nota2" class="form-label">Parcial 2</label>
                                <input type="number" class="form-control" id="nota2" name="nota_2" step="0.01" min="0" max="10" required>
                            </div>
                            <div class="col-md-4">
                                <label for="nota3" class="form-label">Parcial 3</label>
                                <input type="number" class="form-control" id="nota3" name="nota_3" step="0.01" min="0" max="10" required>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Guardar Notas</button>
                            <a href="../index.html" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Volver al Menú
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> 

<script>
    const formGuardarNotas = document.getElementById('guardarNotas');
    formGuardarNotas.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(formGuardarNotas);
        fetch('guardarNotas.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
</script>
<script src="../public/lib/bootstrap-5.3.5-dist/js/bootstrap.min.js"></script>
</body>
</html>