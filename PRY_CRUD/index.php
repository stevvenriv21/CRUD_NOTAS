<?php
require_once './conexion/db.php';
$sql = "SELECT * FROM usuarios";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sqlMaterias = "SELECT * FROM materias";
$stmtMaterias = $pdo->prepare($sqlMaterias);
$stmtMaterias->execute();
$materias = $stmtMaterias->fetchAll(PDO::FETCH_ASSOC);


$sql2 = "SELECT n.id, n.n1, n.n2, n.n3, n.promedio, 
               u.nombre as usuario_nombre, 
               m.nombre as materia_nombre,
               m.nrc as materia_nrc
        FROM notas n
        INNER JOIN usuarios u ON n.usuario_id = u.id
        INNER JOIN materias m ON n.materia_id = m.id
        ORDER BY u.nombre, m.nombre";

$stmt = $pdo->prepare($sql2);
$stmt->execute();
$notas = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>


<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CRUD DE USUARIOS</title>
    <link
      rel="stylesheet"
      href="./public/lib/bootstrap-5.3.5-dist/css/bootstrap.min.css"
    />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
      body {
        background: linear-gradient(120deg, #a5b4fc 0%, #f8fafc 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      .card-custom {
        background: rgba(255,255,255,0.95);
        border-radius: 22px;
        box-shadow: 0 8px 32px rgba(44, 62, 80, 0.14);
        padding: 2.5rem 3rem;
        margin-top: 0;
        border: 1px solid #e0eafc;
        position: relative;
        overflow: hidden;
      }
      .card-custom::before {
        content: "";
        position: absolute;
        top: -40px;
        right: -40px;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, #4f8cff33 60%, transparent 100%);
        z-index: 0;
      }
      .card-custom::after {
        content: "";
        position: absolute;
        bottom: -40px;
        left: -40px;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, #818cf833 60%, transparent 100%);
        z-index: 0;
      }
      .card-custom > * {
        position: relative;
        z-index: 1;
      }
      .btn-primary {
        background-color: #4f8cff;
        border-color: #4f8cff;
        color: #fff;
        box-shadow: 0 2px 8px #4f8cff33;
      }
      .btn-primary:hover {
        background-color: #2563eb;
        border-color: #2563eb;
        box-shadow: 0 4px 16px #2563eb33;
      }
      .btn-dark {
        background-color: #2d3a4b;
        border-color: #2d3a4b;
        color: #fff;
        box-shadow: 0 2px 8px #2d3a4b33;
      }
      .btn-dark:hover {
        background-color: #1e293b;
        border-color: #1e293b;
        box-shadow: 0 4px 16px #1e293b33;
      }
      .btn-secondary {
        background-color: #a5b4fc;
        border-color: #a5b4fc;
        color: #2d3a4b;
        box-shadow: 0 2px 8px #a5b4fc33;
      }
      .btn-secondary:hover {
        background-color: #818cf8;
        border-color: #818cf8;
        color: #fff;
        box-shadow: 0 4px 16px #818cf833;
      }
      .btn-primary, .btn-dark, .btn-secondary {
        min-width: 180px;
        font-weight: 600;
        letter-spacing: 0.7px;
        border-radius: 10px;
        transition: transform 0.12s, box-shadow 0.12s;
        font-size: 1.08rem;
        padding: 0.7rem 0;
      }
      .btn-primary:hover, .btn-dark:hover, .btn-secondary:hover {
        transform: scale(1.07);
      }
      h1 {
        font-family: 'Segoe UI', Arial, sans-serif;
        font-weight: 800;
        color: #2d3a4b;
        letter-spacing: 2px;
        font-size: 2.2rem;
        margin-bottom: 2rem;
        text-shadow: 0 2px 8px #a5b4fc33;
      }
      .btn-group-custom {
        gap: 20px;
        width: 100%;
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
      .btn-group-custom a {
        width: 100%;
        text-align: center;
      }
      @media (max-width: 600px) {
        .card-custom {
          padding: 1.2rem 0.7rem;
          max-width: 98vw;
        }
        h1 {
          font-size: 1.3rem;
        }
        .btn-primary, .btn-dark, .btn-secondary {
          min-width: 120px;
          font-size: 0.95rem;
        }
      }
    </style>
  </head>
  <body>
    <div class="container d-flex justify-content-center align-items-center">
      <div class="card-custom w-100" style="max-width: 480px;">
        <h1 class="mb-4 text-center">CRUD DE USUARIOS</h1>
        <div class="d-flex flex-column align-items-center btn-group-custom">
          <a href="#" 
            class="btn btn-primary mb-2" 
            data-bs-toggle="modal" 
            data-bs-target="#crearUsuarioModal">
            Crear Usuario
          </a>
          <a href="#" class="btn btn-dark mb-2" data-bs-toggle="modal" data-bs-target="#crearMateriaModal">Crear Materia</a>
          <a href="#" class="btn btn-dark mb-2" data-bs-toggle="modal" data-bs-target="#ingresarNotasModal">Ingresar Notas</a>
          <a href="#" class="btn btn-secondary mb-2" data-bs-toggle="modal" data-bs-target="#modalTablaUsuarios">Lista Usuarios</a>
          <a href="#" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#tablaNotasModal">Lista Notas</a>


        </div>
      </div>

      <div class="modal fade" id="crearUsuarioModal" tabindex="-1" aria-labelledby="crearUsuarioModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title text-primary" id="crearUsuarioModalLabel">Crear Usuario</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>

          <div class="modal-body">
            <form id="crearUsuarioForm">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="edad" class="form-label">Edad</label>
                    <input type="number" class="form-control" id="edad" name="edad" required>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
          </div>

        </div>
      </div>
    </div>

    <div class="modal fade" id="crearMateriaModal" tabindex="-1" aria-labelledby="crearMateriaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content p-4">
        <div class="modal-header border-0 pb-0">
          <h1 class="modal-title fs-4 text-primary" id="crearMateriaModalLabel">Crear Materia</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body pt-0">
          <form id="crearMateriaForm">
            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre</label>
              <input type="text" class="form-control" id="nombre" name="nombre" required />
            </div>
            <div class="mb-3">
              <label for="nrc" class="form-label">NRC</label>
              <input type="number" class="form-control" id="nrc" name="nrc" required max="99999" />
            </div>
            <div class="d-flex justify-content-between">
              <button type="submit" class="btn btn-primary">Crear Materia</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-arrow-left"></i> Volver al Menú
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

    <div class="modal fade" id="ingresarNotasModal" tabindex="-1" aria-labelledby="ingresarNotasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content p-4">
        <div class="modal-header">
          <h2 class="modal-title text-primary" id="ingresarNotasModalLabel">Ingresar Notas</h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="ingresarNotasForm">
            <div class="mb-3">
              <label for="usuario" class="form-label">Usuario</label>
              <select class="form-select" id="usuario" name="usuario_id" required>
                <option value="">Seleccione un usuario</option>
                <?php foreach ($usuarios as $user): ?>
                  <option value="<?= htmlspecialchars($user['id']) ?>"><?= htmlspecialchars($user['nombre']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="materia" class="form-label">Materia</label>
              <select class="form-select" id="materia" name="materia_id" required>
                <option value="">Seleccione una materia</option>
                <?php foreach ($materias as $materia): ?>
                  <option value="<?= htmlspecialchars($materia['id']) ?>"><?= htmlspecialchars($materia['nombre']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="row mb-3">
              <div class="col-md-4 mb-3 mb-md-0">
                <label for="nota1" class="form-label">Parcial 1</label>
                <input type="number" class="form-control" id="nota1" name="nota_1" step="0.01" min="0" max="10" required />
              </div>
              <div class="col-md-4 mb-3 mb-md-0">
                <label for="nota2" class="form-label">Parcial 2</label>
                <input type="number" class="form-control" id="nota2" name="nota_2" step="0.01" min="0" max="10" required />
              </div>
              <div class="col-md-4">
                <label for="nota3" class="form-label">Parcial 3</label>
                <input type="number" class="form-control" id="nota3" name="nota_3" step="0.01" min="0" max="10" required />
              </div>
            </div>
            <div class="d-flex justify-content-between">
              <button type="submit" class="btn btn-primary">Guardar Notas</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalTablaUsuarios" tabindex="-1" aria-hidden="true" aria-labelledby="modalTablaUsuariosLabel">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content shadow rounded">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="modalTablaUsuariosLabel">
                        <i class="bi bi-table"></i> Lista de Usuarios
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered align-middle" id="tabla_usuario">
                        <thead class="table-primary">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Edad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $user): ?>
                                <tr id="fila-<?php echo $user['id']; ?>">
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo htmlspecialchars($user['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo $user['edad']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-editar-usuario btn-sm" data-id="<?php echo $user['id']; ?>">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </button>
                                        <button type="button" class="btn btn-danger btn-eliminar-usuario btn-sm" data-id="<?php echo $user['id']; ?>" data-nombre="<?php echo htmlspecialchars($user['nombre']); ?>">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar usuario -->
    <div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content shadow rounded">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="modalEditarUsuarioLabel">Editar Usuario</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_usuario" />
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre_usuario" required />
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email_usuario" required />
                    </div>
                    <div class="mb-3">
                        <label for="edad" class="form-label">Edad</label>
                        <input type="number" class="form-control" id="edad_usuario" min="0" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="btn-actualizar-usuario" class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="tablaNotasModal" tabindex="-1" aria-labelledby="tablaNotasModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tablaNotasModalLabel"><i class="bi bi-clipboard-data"></i> Lista de Notas</h5>
        <span class="badge bg-primary fs-6 px-3 py-2">
                <i class="bi bi-list-check"></i>
                  Total: <?php echo count($notas); ?> registro<?php echo count($notas) != 1 ? 's' : ''; ?>
            </span>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        
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
    
  </div>


<script src="./public/lib/bootstrap-5.3.5-dist/js/bootstrap.min.js"></script>


    <script>
        const formCrearUsuario = document.getElementById('crearUsuarioForm');
        formCrearUsuario.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(formCrearUsuario);
            fetch('./app/guardar.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                Swal.fire('Genial', data, 'success');
                formCrearUsuario.reset();
                // Cierra el modal automáticamente
                const modal = bootstrap.Modal.getInstance(document.getElementById('crearUsuarioModal'));
                modal.hide();
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Ocurrió un problema al guardar', 'error');
            });
        });
    </script>

    <script>
    const formCrearMateria = document.getElementById('crearMateriaForm');
    formCrearMateria.addEventListener('submit', function(event) {
      event.preventDefault();
      const nrcValue = document.getElementById('nrc').value.trim();
      if (nrcValue.length > 5) {
        Swal.fire('Error', 'El NRC no puede tener más de 5 dígitos.', 'error');
        return;
      }
      const formData = new FormData(formCrearMateria);
      fetch('./app/guardar_materia.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.text())
      .then(data => {
        Swal.fire('Genial', data, 'success');
        formCrearMateria.reset();
        // Cerrar modal automáticamente al guardar
        const modal = bootstrap.Modal.getInstance(document.getElementById('crearMateriaModal'));
        modal.hide();
      })
      .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Ocurrió un problema al guardar', 'error');
      });
    });
  </script>

  <script>
  const formIngresarNotas = document.getElementById('ingresarNotasForm');
  formIngresarNotas.addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(formIngresarNotas);
    fetch('./app/guardar_notas.php', { // ajusta esta ruta a tu backend real
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(data => {
      Swal.fire('¡Genial!', data, 'success');
      formIngresarNotas.reset();
      const modal = bootstrap.Modal.getInstance(document.getElementById('ingresarNotasModal'));
      modal.hide();
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire('Error', 'Ocurrió un problema al guardar las notas.', 'error');
    });
  });
</script>


<script>
        document.addEventListener('DOMContentLoaded', () => {
    const modalEditarUsuario = new bootstrap.Modal(document.getElementById('modalEditarUsuario'), { keyboard: false });

    document.getElementById('tabla_usuario').addEventListener('click', e => {
        const btnEditar = e.target.closest('.btn-editar-usuario');
        if (btnEditar) {
            const id = btnEditar.dataset.id;
            console.log('Click en botón editar, id:', id);

            fetch('./app/buscar_usuario_id.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            })
            .then(res => {
                if (!res.ok) throw new Error('Error HTTP ' + res.status);
                return res.json();
            })
            .then(data => {
                console.log('Respuesta servidor:', data);
                if (data.error) {
                    Swal.fire('Error', data.error, 'error');
                    return;
                }

                // Precargar datos en el modal
                document.getElementById('id_usuario').value = data.id || '';
                document.getElementById('nombre_usuario').value = data.nombre || '';
                document.getElementById('email_usuario').value = data.email || '';
                document.getElementById('edad_usuario').value = data.edad || '';

                modalEditarUsuario.show();
            })
            .catch(err => {
                console.error('Fetch error:', err);
                Swal.fire('Error', 'No se pudo cargar el usuario', 'error');
            });
        }


                // Botón Eliminar
                if(e.target.closest('.btn-eliminar-usuario')) {
                    const btn = e.target.closest('.btn-eliminar-usuario');
                    const id = btn.dataset.id;
                    const nombre = btn.dataset.nombre;

                    Swal.fire({
                        title: '¿Estás seguro de eliminar al usuario?',
                        text: nombre,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then(result => {
                        if(result.isConfirmed) {
                            fetch('./app/eliminar.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ id: id })
                            })
                            .then(res => res.json())
                            .then(data => {
                                Swal.fire('¡Listo!', data.message, 'success');
                                // Remueve la fila de la tabla
                                const fila = document.getElementById('fila-' + id);
                                if(fila) fila.remove();
                            })
                            .catch(() => {
                                Swal.fire('Error', 'No se pudo eliminar el usuario', 'error');
                            });
                        }
                    });
                }
            });

            // Actualizar usuario
            document.getElementById('btn-actualizar-usuario').addEventListener('click', () => {
                const id = document.getElementById('id').value;
                const nombre = document.getElementById('nombre').value.trim();
                const email = document.getElementById('email').value.trim();
                const edad = document.getElementById('edad').value;

                if(!nombre || !email || !edad) {
                    Swal.fire('Atención', 'Todos los campos son obligatorios', 'warning');
                    return;
                }

                fetch('./app/actualizarUsuario.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, nombre, email, edad })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire('¡Actualizado!', data.message, 'success');
                        modalEditarUsuario.hide();
                        // Recarga la página para actualizar tabla
                        location.reload();
                    } else {
                        Swal.fire('Error', data.message || 'No se pudo actualizar', 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Error en la actualización', 'error');
                });
            });
        });
    </script>

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
        fetch('./app/buscar_nota.php', {
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
                fetch('./app/eliminar_nota.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Eliminado', data.message, 'success');
                        document.getElementById('fila-' + id).remove();
                        location.reload(); 
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
        fetch('./app/actualizar_nota.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, n1, n2, n3 })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Actualizado', 'Nota actualizada correctamente', 'success');
                modal.hide();
                 setTimeout(() => {
            location.reload(); // recarga después de 1.5 segundos
        }, 1500);
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
