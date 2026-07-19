<?php

require_once __DIR__ . '/../config/database.php';

class ClienteUbicacion {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByClienteId(int $clienteId): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM ubi_cliente_ubicaciones WHERE cliente_id = ? ORDER BY fecha_registro DESC"
        );
        $stmt->execute([$clienteId]);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT u.*, c.nombre AS cliente_nombre, c.celular AS cliente_celular
             FROM ubi_cliente_ubicaciones u
             JOIN ubi_cliente c ON c.id = u.cliente_id
             WHERE u.id = ?"
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getLatestByCliente(int $clienteId): ?array {
        $stmt = $this->db->prepare(
            "SELECT * FROM ubi_cliente_ubicaciones
             WHERE cliente_id = ?
             ORDER BY created_at DESC LIMIT 1"
        );
        $stmt->execute([$clienteId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getAllWithCliente(): array {
        $stmt = $this->db->query(
            "SELECT u.*, c.nombre AS cliente_nombre, c.celular AS cliente_celular
             FROM ubi_cliente_ubicaciones u
             JOIN ubi_cliente c ON c.id = u.cliente_id
             ORDER BY u.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO ubi_cliente_ubicaciones
             (cliente_id, detalle, latitud, longitud, precision_gps, foto, fecha_registro, usuario_id)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['cliente_id'],
            $data['detalle'] ?? null,
            $data['latitud'] ?? null,
            $data['longitud'] ?? null,
            $data['precision_gps'] ?? null,
            $data['foto'] ?? null,
            $data['fecha_registro'] ?? date('Y-m-d'),
            $data['usuario_id'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function updateFoto(int $id, string $foto): bool {
        $stmt = $this->db->prepare("UPDATE ubi_cliente_ubicaciones SET foto = ? WHERE id = ?");
        return $stmt->execute([$foto, $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM ubi_cliente_ubicaciones WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
