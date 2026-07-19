<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= htmlspecialchars($clienteData['nombre']) ?> - Detalle</title>
    <link rel="stylesheet" href="<?= assetUrl('css/styles.css') ?>">
</head>
<body>
    <div class="container">
        <header class="header">
            <a href="<?= baseUrl() ?>" class="btn-back">&larr; Volver</a>
            <h1><?= htmlspecialchars($clienteData['nombre']) ?></h1>
        </header>

        <section class="detail-card">
            <?php if ($clienteData['dni']): ?>
                <div class="detail-row">
                    <span class="detail-label">DNI</span>
                    <span class="detail-value"><?= htmlspecialchars($clienteData['dni']) ?></span>
                </div>
            <?php endif; ?>
            <?php if ($clienteData['celular']): ?>
                <div class="detail-row">
                    <span class="detail-label">Celular</span>
                    <span class="detail-value"><?= htmlspecialchars($clienteData['celular']) ?></span>
                </div>
            <?php endif; ?>
            <?php if ($clienteData['detalle']): ?>
                <div class="detail-row">
                    <span class="detail-label">Detalle</span>
                    <span class="detail-value"><?= nl2br(htmlspecialchars($clienteData['detalle'])) ?></span>
                </div>
            <?php endif; ?>
        </section>

        <section class="actions-top">
            <button type="button" class="btn btn-success btn-lg"
                    onclick="abrirModalRegistrar(<?= $clienteData['id'] ?>)">
                + Nueva ubicación
            </button>
        </section>

        <section class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Detalle</th>
                        <th>Coordenadas</th>
                        <th>Precisión</th>
                        <th>Foto</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ubicaciones)): ?>
                        <tr><td colspan="6" class="text-center text-muted">Sin ubicaciones registradas</td></tr>
                    <?php else: ?>
                        <?php foreach ($ubicaciones as $u): ?>
                        <tr>
                            <td data-label="Detalle"><?= htmlspecialchars($u['detalle'] ?? '') ?></td>
                            <td data-label="Coordenadas">
                                <?php if ($u['latitud'] && $u['longitud']): ?>
                                    <span class="coords"><?= $u['latitud'] ?>, <?= $u['longitud'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Precisión"><?= $u['precision_gps'] ? $u['precision_gps'] . 'm' : '' ?></td>
                            <td data-label="Foto" class="text-center">
                                <?php if ($u['foto']): ?>
                                    <img src="<?= uploadUrl($u['foto']) ?>" alt="Foto" class="thumb"
                                         onclick="verFoto('<?= uploadUrl($u['foto']) ?>')">
                                <?php else: ?>
                                    <span class="no-photo">-</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Fecha"><?= date('d/m/Y', strtotime($u['fecha_registro'])) ?></td>
                            <td data-label="Acciones" class="actions-cell">
                                <button type="button" class="btn btn-sm btn-outline"
                                        onclick="irAlCliente(<?= $u['latitud'] ?? 'null' ?>, <?= $u['longitud'] ?? 'null' ?>)"
                                        title="Ir al cliente">Ir</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>

    <footer class="footer">
        <div class="footer-inner">
            <span class="footer-brand">
                <span>soporte@moabcode.com</span>
                <span class="footer-sep">|</span>
                <span>916 377 263</span>
                <span class="footer-version">v1</span>
            </span>
            <span class="footer-message">Sistema de Clientes y Ubicaciones</span>
        </div>
    </footer>

    <?php require_once __DIR__ . '/modal_registro.php'; ?>

    <div id="modalFoto" class="modal-overlay" style="display:none" onclick="cerrarModalFoto(event)">
        <div class="modal-body modal-foto">
            <button type="button" class="modal-close" onclick="cerrarModalFoto()">&times;</button>
            <img id="fotoGrande" src="" alt="Foto fachada">
        </div>
    </div>

    <script>
        var BASE_URL = '<?= baseUrl() ?>';
        var CURRENT_CLIENTE = <?= json_encode(['id' => $clienteData['id'], 'nombre' => $clienteData['nombre']], JSON_UNESCAPED_UNICODE) ?>;
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="<?= assetUrl('js/app.js') ?>"></script>
</body>
</html>
