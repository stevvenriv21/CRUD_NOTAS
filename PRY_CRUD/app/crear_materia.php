<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Crear Materia - Modal</title>
  <link rel="stylesheet" href="../public/lib/bootstrap-5.3.5-dist/css/bootstrap.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
      min-height: 100vh;
    }
    .form-label {
      font-weight: 500;
      color: #2c3e50;
    }
    .btn-primary {
      background: linear-gradient(90deg, #007bff 0%, #00c6ff 100%);
      border: none;
    }
    .btn-primary:hover {
      background: linear-gradient(90deg, #0056b3 0%, #00aaff 100%);
    }
    .btn-secondary {
      background: #f5f6fa;
      color: #2c3e50;
      border: 1px solid #dfe4ea;
    }
    .btn-secondary:hover {
      background: #dfe4ea;
    }
  </style>
</head>
<body class="d-flex justify-content-center align-items-center">

  <!-- Botón para abrir el modal -->
  <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#crearMateriaModal">
    Crear Materia
  </button>

  <!-- Modal -->
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

  <script src="../public/lib/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
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
      fetch('./guardar_materia.php', {
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
</body>
</html>
