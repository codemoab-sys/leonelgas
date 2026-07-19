<?php

require_once __DIR__ . '/../models/ClienteUbicacion.php';
require_once __DIR__ . '/../config/helpers.php';

class UbicacionesController {

    public function guardar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            jsonError('Método no permitido', 405);
        }

        $clienteId = (int) ($_POST['cliente_id'] ?? 0);
        if ($clienteId <= 0) {
            jsonError('Seleccione un cliente');
        }

        $foto = null;
        if (!empty($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $foto = $this->subirFoto($_FILES['foto']);
            if ($foto === false) {
                jsonError('Error al subir la foto');
            }
        }

        $model = new ClienteUbicacion();
        $id = $model->create([
            'cliente_id' => $clienteId,
            'detalle' => trim($_POST['detalle'] ?? ''),
            'latitud' => ($_POST['latitud'] ?? '') !== '' ? $_POST['latitud'] : null,
            'longitud' => ($_POST['longitud'] ?? '') !== '' ? $_POST['longitud'] : null,
            'precision_gps' => ($_POST['precision_gps'] ?? '') !== '' ? $_POST['precision_gps'] : null,
            'foto' => $foto,
            'fecha_registro' => date('Y-m-d'),
            'usuario_id' => $_POST['usuario_id'] ?? null,
        ]);

        jsonResponse([
            'success' => true,
            'id' => $id,
            'foto' => $foto ? uploadUrl($foto) : null,
        ]);
    }

    public function eliminar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            jsonError('Método no permitido', 405);
        }
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            jsonError('ID inválido');
        }

        $model = new ClienteUbicacion();
        $ubicacion = $model->getById($id);
        if ($ubicacion && $ubicacion['foto']) {
            $path = uploadPath($ubicacion['foto']);
            if (file_exists($path)) {
                unlink($path);
            }
        }
        $model->delete($id);
        jsonResponse(['success' => true]);
    }

    public function obtener(): void {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            jsonError('ID inválido');
        }
        $model = new ClienteUbicacion();
        $data = $model->getById($id);
        if (!$data) {
            jsonError('No encontrado', 404);
        }
        if ($data['foto']) {
            $data['foto_url'] = uploadUrl($data['foto']);
        }
        jsonResponse($data);
    }

    private function subirFoto(array $file): string|false {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            jsonError('Tipo de imagen no permitido. Use JPG, PNG, WEBP o GIF.');
            return false;
        }
        if ($file['size'] > 5 * 1024 * 1024) {
            jsonError('La imagen no debe superar los 5MB');
            return false;
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('fachada_') . '.' . $ext;
        $dest = uploadPath($filename);

        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return $filename;
        }
        return false;
    }
}
