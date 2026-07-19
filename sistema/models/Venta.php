<?php

require_once __DIR__ . '/../config/database.php';

class Venta {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByClienteId(int $clienteId): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM ubi_venta WHERE cliente_id = ? ORDER BY fecha DESC, created_at DESC"
        );
        $stmt->execute([$clienteId]);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM ubi_venta WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO ubi_venta (cliente_id, bidones_vendidos, precio, bidones_vacios, total, fecha)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['cliente_id'],
            $data['bidones_vendidos'],
            $data['precio'],
            $data['bidones_vacios'],
            $data['total'],
            $data['fecha'] ?? date('Y-m-d'),
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM ubi_venta WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
