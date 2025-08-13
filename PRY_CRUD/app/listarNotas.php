<?php 

require_once '../conexion/db.php';

$sql = "SELECT n.id, n.n1, n.n2, n.n3, n.promedio, 
               u.nombre as usuario_nombre, 
               m.nombre as materia_nombre,
               m.nrc as materia_nrc
        FROM notas n
        INNER JOIN usuarios u ON n.usuario_id = u.id
        INNER JOIN materias m ON n.materia_id = m.id
        ORDER BY u.nombre, m.nombre";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$notas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lista de Notas</title>
    <link rel="stylesheet" href="../public/lib/bootstrap-5.3.5-dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <style>
        body {
            background: linear-gradient(120deg, #f0f4f8 0%, #e0e7ff 100%);
            min-height: 100vh;
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            color: #222;
        }
        .modal-content {
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(60,72,88,0.12);
            border: none;
        }
        .modal-header {
            background: linear-gradient(90deg, #6366f1 0%, #38bdf8 100%);
            color: #fff;
            border-radius: 18px 18px 0 0;
            border-bottom: none;
        }
        .promedio-excelente { color: #22c55e; font-weight: 600; }
        .promedio-malo { color: #ef4444; font-weight: 600; }
        .badge.bg-primary {
            background: linear-gradient(90deg, #6366f1 0%, #38bdf8 100%) !important;
            color: #fff;
            font-size: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(99,102,241,0.08);
        }
        .badge.bg-secondary {
            background: #64748b !important;
            color: #fff;
            border-radius: 8px;
        }
        .btn-editar-nota {
            background: linear-gradient(90deg, #6366f1 0%, #38bdf8 100%);
            border: none;
            color: #fff;
            border-radius: 8px;
            transition: background 0.2s;
        }
        .btn-editar-nota:hover {
            background: linear-gradient(90deg, #4338ca 0%, #0ea5e9 100%);
            color: #fff;
        }
        .btn-eliminar-nota {
            background: linear-gradient(90deg, #ef4444 0%, #f59e42 100%);
            border: none;
            color: #fff;
            border-radius: 8px;
            transition: background 0.2s;
        }
        .btn-eliminar-nota:hover {
            background: linear-gradient(90deg, #b91c1c 0%, #f59e42 100%);
            color: #fff;
        }
    </style>
</head>
<body>

<div class="container py-5 text-center">
    <!-- Botón para abrir modal tabla -->
    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#tablaNotasModal">
        <i class="bi bi-clipboard-data"></i> Ver Lista de Notas
    </button>
</div>

<!-- Modal que contiene la tabla -->
<div class="modal fade" id="tablaNotasModal" tabindex="-1" aria-labelledby="tablaNotasModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tablaNotasModalLabel"><i class="bi bi-clipboard-data"></i> Lista de Notas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3 text-start">
            <span class="badge bg-primary fs-6 px-3 py-2">
                <i class="bi bi-list-check"></i>
                Total: <?php echo count($notas); ?> registro<?php echo count($notas) != 1 ? 's' : ''; ?>
            </span>
        </div>
        <div class="table-responsive rounded-3">
          <table class="table table-hover align-middle" id="tabla-notas">
              <thead class="table-light">
                  <tr>
                      <th>ID</th>
                      <th>Usuario</th>
                      <th>Materia</th>
                      <th>NRC</th>
                      <th>Parcial 1</th>
                      <th>Parcial 2</th>
                      <th>Parcial 3</th>
                      <th>Promedio</th>
                      <th>Estado</th>
                      <th>Acciones</th>
                  </tr>
              </thead>
              <tbody>
                  <?php if (count($notas) > 0): ?>
                      <?php foreach ($notas as $nota): ?>
                          <?php 
                              $promedio = floatval($nota['promedio']);
                              $clase_promedio = '';
                              $estado = '';
                              if ($promedio >= 14) {
                                  $clase_promedio = 'promedio-excelente';
                                  $estado = '<span class="badge bg-success">Aprobado</span>';
                              } else {
                                  $clase_promedio = 'promedio-malo';
                                  $estado = '<span class="badge bg-danger">Reprobado</span>';
                              }
                          ?>
                          <tr id="fila-<?php echo htmlspecialchars($nota['id']); ?>">
                              <td><?php echo htmlspecialchars($nota['id']); ?></td>
                              <td>
                                  <i class="bi bi-person-circle text-primary me-1"></i>
                                  <?php echo htmlspecialchars($nota['usuario_nombre']); ?>
                              </td>
                              <td>
                                  <i class="bi bi-book text-success me-1"></i>
                                  <?php echo htmlspecialchars($nota['materia_nombre']); ?>
                              </td>
                              <td>
                                  <span class="badge bg-secondary"><?php echo htmlspecialchars($nota['materia_nrc']); ?></span>
                              </td>
                              <td><?php echo number_format($nota['n1'], 2); ?></td>
                              <td><?php echo number_format($nota['n2'], 2); ?></td>
                              <td><?php echo number_format($nota['n3'], 2); ?></td>
                              <td class="<?php echo $clase_promedio; ?>">
                                  <?php echo number_format($promedio, 2); ?>
                              </td>
                              <td><?php echo $estado; ?></td>
                              <td>
                                  <button type="button" class="btn btn-editar-nota btn-sm me-1"
                                      data-id="<?php echo htmlspecialchars($nota['id']); ?>"
                                      title="Editar nota">
                                      <i class="bi bi-pencil"></i>
                                  </button>
                                  <button type="button" class="btn btn-eliminar-nota btn-sm"
                                      data-id="<?php echo htmlspecialchars($nota['id']); ?>"
                                      data-usuario="<?php echo htmlspecialchars($nota['usuario_nombre']); ?>"
                                      data-materia="<?php echo htmlspecialchars($nota['materia_nombre']); ?>"
                                      title="Eliminar nota">
                                      <i class="bi bi-trash"></i>
                                  </button>
                              </td>
                          </tr>
                      <?php endforeach; ?>
                  <?php else: ?>
                      <tr>
                          <td colspan="10" class="text-center py-5">
                              <i class="bi bi-clipboard-x display-4 text-muted"></i>
                              <p class="mt-3 text-muted">No hay notas registradas</p>
                              <a href="ingresar_notas.php" class="btn btn-primary">
                                  <i class="bi bi-plus-circle"></i> Ingresar primera nota
                              </a>
                          </td>
                      </tr>
                  <?php endif; ?>
              </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <a href="../index.php" class="btn btn-secondary me-auto">
          <i class="bi bi-arrow-left"></i> Volver al Menú
        </a>
        <a href="ingresar_notas.php" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Nueva Nota
        </a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para editar nota (igual que antes) -->
<div class="modal fade" id="editarNota" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil"></i> Editar Nota
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="nota_id" />
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="edit_n1" class="form-label">Parcial 1</label>
                        <input type="number" class="form-control" id="edit_n1" 
                               step="0.01" min="0" max="20" 
                               pattern="[0-9]+([.,][0-9]{1,2})?" title="Máximo 2 decimales"
                               oninput="validarDecimalesModal(this)" required>
                    </div>
                    <div class="col-md-4">
                        <label for="edit_n2" class="form-label">Parcial 2</label>
                        <input type="number" class="form-control" id="edit_n2" 
                               step="0.01" min="0" max="20"
                               pattern="[0-9]+([.,][0-9]{1,2})?" title="Máximo 2 decimales"
                               oninput="validarDecimalesModal(this)" required>
                    </div>
                    <div class="col-md-4">
                        <label for="edit_n3" class="form-label">Parcial 3</label>
                        <input type="number" class="form-control" id="edit_n3" 
                               step="0.01" min="0" max="20"
                               pattern="[0-9]+([.,][0-9]{1,2})?" title="Máximo 2 decimales"
                               oninput="validarDecimalesModal(this)" required>
                    </div>
                </div>
                <div class="alert alert-info mt-3">
                    <i class="bi bi-info-circle"></i>
                    El promedio se calculará automáticamente.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-actualizar-nota">
                    <i class="bi bi-save"></i> Actualizar Nota
                </button>
            </div>
        </div>
    </div>
</div>

<script src="../public/lib/bootstrap-5.3.5-dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@magicbruno/swalstrap5@1.0.8/dist/js/swalstrap5_all.min.js"></script>

<script>
    function validarDecimalesModal(input) {
        let valor = input.value;
        if (parseFloat(valor) > 20) {
            input.value = '20.00';
            Swal.fire('Atención', 'La nota máxima permitida es 20.00', 'warning');
            return;
        }
        if (parseFloat(valor) < 0) {
            input.value = '0.00';
            Swal.fire('Atención', 'La nota mínima permitida es 0.00', 'warning');
            return;
        }
        if (valor.includes('.')) {
            const partes = valor.split('.');
            if (partes[1] && partes[1].length > 2) {
                input.value = parseFloat(valor).toFixed(2);
            }
        }
    }

    const modal = new bootstrap.Modal(document.getElementById('editarNota'));
    
    document.getElementById('tabla-notas').addEventListener('click', function(e) {
        const editBtn = e.target.closest('.btn-editar-nota');
        if (editBtn) {
            const id = editBtn.dataset.id;
            cargarNota(id);
        }
        const deleteBtn = e.target.closest('.btn-eliminar-nota');
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            const usuario = deleteBtn.dataset.usuario;
            const materia = deleteBtn.dataset.materia;
            eliminarNota(id, usuario, materia);
        }
    });

    document.getElementById('btn-actualizar-nota').addEventListener('click', function() {
        actualizarNota();
    });

    ['edit_n1', 'edit_n2', 'edit_n3'].forEach(id => {
        document.getElementById(id).addEventListener('blur', function() {
            if (this.value && !isNaN(this.value)) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    });

    function cargarNota(id) {
        fetch('buscarid_nota.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(nota => {
            document.getElementById('nota_id').value = nota.id;
            document.getElementById('edit_n1').value = parseFloat(nota.n1).toFixed(2);
            document.getElementById('edit_n2').value = parseFloat(nota.n2).toFixed(2);
            document.getElementById('edit_n3').value = parseFloat(nota.n3).toFixed(2);
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error al cargar los datos de la nota', 'error');
        });
    }

    function eliminarNota(id, usuario, materia) {
        Swal.fire({
            title: '¿Eliminar nota?',
            html: `<strong>Usuario:</strong> ${usuario}<br><strong>Materia:</strong> ${materia}`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('eliminar_nota.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Eliminado', data.message, 'success');
                        document.getElementById('fila-' + id).remove();
                    } else {
                        Swal.fire('Error', data.error, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Error al eliminar la nota', 'error');
                });
            }
        });
    }

    function actualizarNota() {
        const id = document.getElementById('nota_id').value;
        const n1 = parseFloat(document.getElementById('edit_n1').value);
        const n2 = parseFloat(document.getElementById('edit_n2').value);
        const n3 = parseFloat(document.getElementById('edit_n3').value);
        if (isNaN(n1) || isNaN(n2) || isNaN(n3)) {
            Swal.fire('Error', 'Todas las notas deben ser números válidos', 'error');
            return;
        }
        if (n1 > 20 || n2 > 20 || n3 > 20 || n1 < 0 || n2 < 0 || n3 < 0) {
            Swal.fire('Error', 'Las notas deben estar entre 0 y 20', 'error');
            return;
        }
        const validarDecimales = (num) => {
            const str = num.toString();
            return !str.includes('.') || str.split('.')[1].length <= 2;
        };
        if (!validarDecimales(n1) || !validarDecimales(n2) || !validarDecimales(n3)) {
            Swal.fire('Error', 'Las notas pueden tener máximo 2 decimales', 'error');
            return;
        }
        fetch('actualizar_nota.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, n1, n2, n3 })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Actualizado', 'Nota actualizada correctamente', 'success');
                modal.hide();
                location.reload();
            } else {
                Swal.fire('Error', data.error, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error al actualizar la nota', 'error');
        });
    }
</script>

</body>
</html>
