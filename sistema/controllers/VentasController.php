<?php

require_once __DIR__ . '/../models/Venta.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../config/helpers.php';

class VentasController {

    public function guardar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            jsonError('Método no permitido', 405);
        }

        $clienteId = (int) ($_POST['cliente_id'] ?? 0);
        if ($clienteId <= 0) {
            jsonError('Seleccione un cliente');
        }

        $cliente = new Cliente();
        $clienteData = $cliente->getById($clienteId);
        if (!$clienteData) {
            jsonError('Cliente no encontrado', 404);
        }

        $bidonesVendidos = (int) ($_POST['bidones_vendidos'] ?? 0);
        $precio = (float) ($_POST['precio'] ?? 0);
        $bidonesVacios = (int) ($_POST['bidones_vacios'] ?? 0);
        $total = $bidonesVendidos * $precio;

        $venta = new Venta();
        $id = $venta->create([
            'cliente_id' => $clienteId,
            'bidones_vendidos' => $bidonesVendidos,
            'precio' => $precio,
            'bidones_vacios' => $bidonesVacios,
            'total' => $total,
        ]);

        jsonResponse([
            'success' => true,
            'id' => $id,
            'total' => $total,
            'cliente' => [
                'nombre' => $clienteData['nombre'],
                'celular' => $clienteData['celular'],
            ],
            'detalle' => [
                'bidones_vendidos' => $bidonesVendidos,
                'precio' => $precio,
                'bidones_vacios' => $bidonesVacios,
            ],
        ]);
    }
}
