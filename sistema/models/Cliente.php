<?php

require_once __DIR__ . '/../config/database.php';

class Cliente {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM ubi_cliente WHERE estado = 1 ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM ubi_cliente WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function search(string $q): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM ubi_cliente
             WHERE estado = 1 AND (nombre LIKE ? OR celular LIKE ? OR dni LIKE ?)
             ORDER BY nombre ASC LIMIT 20"
        );
        $like = "%$q%";
        $stmt->execute([$like, $like, $like]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO ubi_cliente (nombre, dni, celular, detalle) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['nombre'],
            $data['dni'] ?? null,
            $data['celular'] ?? null,
            $data['detalle'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $fields = [];
        $values = [];
        foreach (['nombre', 'dni', 'celular', 'detalle'] as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "$field = ?";
                $values[] = $data[$field];
            }
        }
        if (empty($fields)) return false;
        $values[] = $id;
        $stmt = $this->db->prepare("UPDATE ubi_cliente SET " . implode(', ', $fields) . " WHERE id = ?");
        return $stmt->execute($values);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("UPDATE ubi_cliente SET estado = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
