<?php 
require_once '../conexion/db.php';
$sql = "SELECT * FROM usuarios";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="../public/lib/bootstrap-5.3.5-dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <style>
        body {
            background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
            font-family: 'Segoe UI', Arial, sans-serif;
            min-height: 100vh;
            padding: 2rem;
        }
        .modal-header.bg-primary {
            background: linear-gradient(90deg, #2563eb 60%, #60a5fa 100%);
            color: white;
        }
        .btn-editar-usuario, .btn-eliminar-usuario {
            font-size: 0.9rem;
            padding: 4px 10px;
            border-radius: 6px;
            box-shadow: 0 1px 4px rgba(44,62,80,0.08);
            transition: transform 0.1s;
        }
        .btn-editar-usuario:hover {
            background-color: #fbbf24;
            color: #fff;
            transform: scale(1.05);
        }
        .btn-eliminar-usuario:hover {
            background-color: #ef4444;
            color: #fff;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

    <!-- Botón para abrir la tabla modal -->
    <div class="text-center mb-4">
        <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalTablaUsuarios">
            <i class="bi bi-people-fill"></i> Ver Lista de Usuarios
        </button>
    </div>

    <!-- Modal con la tabla -->
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
                    <a href="../index.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Menú
                    </a>
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
                    <input type="hidden" id="id" />
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" required />
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" required />
                    </div>
                    <div class="mb-3">
                        <label for="edad" class="form-label">Edad</label>
                        <input type="number" class="form-control" id="edad" min="0" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="btn-actualizar-usuario" class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Librerías JS -->
    <script src="../public/lib/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const modalEditarUsuario = new bootstrap.Modal(document.getElementById('modalEditarUsuario'), { keyboard: false });

        // Delegación de eventos para botones de la tabla
        document.getElementById('tabla_usuario').addEventListener('click', e => {
            // Botón Editar
            if(e.target.closest('.btn-editar-usuario')) {
                const btn = e.target.closest('.btn-editar-usuario');
                const id = btn.dataset.id;

                fetch('buscar_usuario_id.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                })
                .then(res => res.json())
                .then(data => {
                    modalEditarUsuario.show();
                    document.getElementById('id').value = data.id;
                    document.getElementById('nombre').value = data.nombre;
                    document.getElementById('email').value = data.email;
                    document.getElementById('edad').value = data.edad;
                })
                .catch(() => {
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
                        fetch('eliminar.php', {
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

            fetch('actualizarUsuario.php', {
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
</body>
</html>
