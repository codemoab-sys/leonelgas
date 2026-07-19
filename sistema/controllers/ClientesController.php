<?php

require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/ClienteUbicacion.php';
require_once __DIR__ . '/../models/Venta.php';
require_once __DIR__ . '/../config/helpers.php';

class ClientesController {

    public function index(): void {
        $cliente = new Cliente();
        $clientes = $cliente->getAll();

        $ubicacion = new ClienteUbicacion();
        $data = [];
        foreach ($clientes as $c) {
            $ultima = $ubicacion->getLatestByCliente($c['id']);
            $data[] = [
                'id' => $c['id'],
                'nombre' => $c['nombre'],
                'celular' => $c['celular'],
                'dni' => $c['dni'],
                'detalle' => $c['detalle'],
                'ultima_ubicacion' => $ultima,
            ];
        }

        require_once __DIR__ . '/../views/clientes/index.php';
    }

    public function buscar(): void {
        $q = $_GET['q'] ?? '';
        if (strlen($q) < 1) {
            jsonResponse([]);
        }
        $cliente = new Cliente();
        $results = $cliente->search($q);
        $items = array_map(fn($r) => [
            'id' => $r['id'],
            'text' => $r['nombre'] . ($r['celular'] ? " - {$r['celular']}" : ''),
        ], $results);
        jsonResponse($items);
    }

    public function guardar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            jsonError('Método no permitido', 405);
        }

        $nombre = trim($_POST['nombre'] ?? '');
        if ($nombre === '') {
            jsonError('El nombre es obligatorio');
        }

        $cliente = new Cliente();
        $id = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            $cliente->update($id, [
                'nombre' => $nombre,
                'dni' => trim($_POST['dni'] ?? ''),
                'celular' => trim($_POST['celular'] ?? ''),
                'detalle' => trim($_POST['detalle'] ?? ''),
            ]);
        } else {
            $id = $cliente->create([
                'nombre' => $nombre,
                'dni' => trim($_POST['dni'] ?? ''),
                'celular' => trim($_POST['celular'] ?? ''),
                'detalle' => trim($_POST['detalle'] ?? ''),
            ]);
        }

        jsonResponse([
            'success' => true,
            'id' => $id,
            'nombre' => $nombre,
        ]);
    }

    public function detalle(int $id): void {
        $cliente = new Cliente();
        $clienteData = $cliente->getById($id);
        if (!$clienteData) {
            http_response_code(404);
            die('Cliente no encontrado');
        }

        $ubicacion = new ClienteUbicacion();
        $ubicaciones = $ubicacion->getByClienteId($id);

        require_once __DIR__ . '/../views/ubicaciones/index.php';
    }

    public function ubicaciones(): void {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            jsonError('ID inválido');
        }
        $cliente = new Cliente();
        $clienteData = $cliente->getById($id);
        if (!$clienteData) {
            jsonError('No encontrado', 404);
        }

        $ubicacion = new ClienteUbicacion();
        $ubicaciones = $ubicacion->getByClienteId($id);

        foreach ($ubicaciones as &$u) {
            if ($u['foto']) {
                $u['foto_url'] = uploadUrl($u['foto']);
            }
        }

        $venta = new Venta();
        $ventas = $venta->getByClienteId($id);

        jsonResponse([
            'cliente' => $clienteData,
            'ubicaciones' => $ubicaciones,
            'ventas' => $ventas,
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
        $cliente = new Cliente();
        $cliente->delete($id);
        jsonResponse(['success' => true]);
    }

    public function obtener(): void {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            jsonError('ID inválido');
        }
        $cliente = new Cliente();
        $data = $cliente->getById($id);
        if (!$data) {
            jsonError('No encontrado', 404);
        }
        jsonResponse($data);
    }
}
