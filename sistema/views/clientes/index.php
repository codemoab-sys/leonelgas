<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>LEONEL GAS - Clientes y Ubicaciones</title>
    <link rel="icon" type="image/png" href="<?= assetUrl('img/favicon.png') ?>">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= assetUrl('css/styles.css') ?>">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="header-top">
                <img src="<?= assetUrl('img/logo.png') ?>" alt="LEONEL GAS" class="header-logo">
                <button type="button" class="btn-theme" onclick="abrirInfo()" title="Acerca del sistema">i</button>
                <button type="button" class="btn-theme" id="btnTheme" onclick="toggleTheme()" title="Cambiar modo"></button>
                <button type="button" class="btn-theme" onclick="salir()" title="Cerrar sesi\u00f3n">x</button>
            </div>
            <h1 class="header-title">Clientes y Ubicaciones</h1>
        </header>

        <section class="search-bar">
            <select id="selectCliente" class="search-select" style="width:100%"></select>
            <button type="button" class="btn btn-primary" onclick="buscarCliente()">Buscar</button>
        </section>

        <section class="actions-top" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
            <button type="button" class="btn btn-success btn-lg" onclick="abrirModalRegistrar()" style="flex:1">
                + Registrar nueva ubicación
            </button>
            <button type="button" class="btn-theme" onclick="abrirInfoUbicacion()" title="C&oacute;mo registrar ubicaci&oacute;n" style="flex-shrink:0">?</button>
        </section>

        <section class="cliente-list" id="clienteList">
            <?php foreach ($data as $item): ?>
            <div class="cliente-card">
                <div class="cliente-card-top">
                    <div class="cliente-card-info" onclick="verCliente(<?= $item['id'] ?>)">
                        <div class="cliente-nombre"><?= htmlspecialchars($item['nombre']) ?></div>
                        <?php if ($item['celular']): ?>
                            <div class="cliente-detalle"><?= htmlspecialchars($item['celular']) ?></div>
                        <?php endif; ?>
                        <?php if ($item['ultima_ubicacion']['detalle'] ?? $item['detalle'] ?? false): ?>
                            <div class="cliente-detalle">
                                <?= htmlspecialchars($item['ultima_ubicacion']['detalle'] ?? $item['detalle'] ?? '') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($item['ultima_ubicacion']['foto'] ?? false): ?>
                        <img src="<?= uploadUrl($item['ultima_ubicacion']['foto']) ?>"
                             alt="Foto" class="cliente-foto-thumb"
                             onclick="event.stopPropagation(); verFoto('<?= uploadUrl($item['ultima_ubicacion']['foto']) ?>')">
                    <?php else: ?>
                        <div class="cliente-no-foto">-</div>
                    <?php endif; ?>
                </div>
                <div class="cliente-card-actions">
                    <button type="button" class="btn btn-primary"
                            onclick="verCliente(<?= $item['id'] ?>)">Ver</button>
                    <button type="button" class="btn btn-outline"
                            onclick="editarCliente(<?= $item['id'] ?>)">Editar</button>
                    <button type="button" class="btn btn-warning"
                            onclick="abrirModalVenta(<?= $item['id'] ?>, '<?= htmlspecialchars($item['nombre'], ENT_QUOTES) ?>')">Venta</button>
                    <button type="button" class="btn btn-success"
                            onclick="irAlCliente(<?= $item['ultima_ubicacion']['latitud'] ?? 'null' ?>, <?= $item['ultima_ubicacion']['longitud'] ?? 'null' ?>)">Ir</button>
                </div>
            </div>
            <?php endforeach; ?>
        </section>

        <section class="table-responsive" id="tablaPantallaGrande">
            <table class="table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Celular</th>
                        <th>Dirección</th>
                        <th>Foto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $item): ?>
                    <tr>
                        <td data-label="Cliente">
                            <strong><?= htmlspecialchars($item['nombre']) ?></strong>
                            <?php if ($item['dni']): ?>
                                <small class="d-block text-muted">DNI: <?= htmlspecialchars($item['dni']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td data-label="Celular"><?= htmlspecialchars($item['celular'] ?? '') ?></td>
                        <td data-label="Dirección">
                            <?= htmlspecialchars($item['ultima_ubicacion']['detalle'] ?? ($item['detalle'] ?? '')) ?>
                        </td>
                        <td data-label="Foto" class="text-center">
                            <?php if ($item['ultima_ubicacion']['foto'] ?? false): ?>
                                <img src="<?= uploadUrl($item['ultima_ubicacion']['foto']) ?>"
                                     alt="Foto" class="thumb"
                                     onclick="verFoto('<?= uploadUrl($item['ultima_ubicacion']['foto']) ?>')">
                            <?php else: ?>
                                <span class="no-photo">-</span>
                            <?php endif; ?>
                        </td>
                        <td data-label="Acciones" class="actions-cell">
                            <button type="button" class="btn btn-sm btn-outline"
                                    onclick="verCliente(<?= $item['id'] ?>)">Ver</button>
                            <button type="button" class="btn btn-sm btn-outline"
                                    onclick="editarCliente(<?= $item['id'] ?>)">Editar</button>
                            <button type="button" class="btn btn-sm btn-warning"
                                    onclick="abrirModalVenta(<?= $item['id'] ?>, '<?= htmlspecialchars($item['nombre'], ENT_QUOTES) ?>')">Venta</button>
                            <button type="button" class="btn btn-sm btn-outline"
                                    onclick="irAlCliente(<?= $item['ultima_ubicacion']['latitud'] ?? 'null' ?>, <?= $item['ultima_ubicacion']['longitud'] ?? 'null' ?>)">Ir</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>

    <footer class="footer">
        <div class="footer-inner">
            <span class="footer-brand">
                <a href="https://moabcode.com" target="_blank" rel="noopener">moabcode.com</a>
                <span class="footer-version">v1</span>
            </span>
            <span class="footer-message">Sistema de Clientes y Ubicaciones</span>
        </div>
    </footer>

    <?php require_once __DIR__ . '/modal_cliente.php'; ?>
    <?php require_once __DIR__ . '/../ubicaciones/modal_registro.php'; ?>
    <?php require_once __DIR__ . '/modal_venta.php'; ?>

    <div id="modalDetalle" class="modal-overlay" style="display:none">
        <div class="modal-container modal-lg">
            <div class="modal-header">
                <h2 id="detalleNombre"></h2>
                <button type="button" class="modal-close" onclick="cerrarModal('modalDetalle')">&times;</button>
            </div>
            <div class="modal-body" id="detalleBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalDetalle')">Cerrar</button>
            </div>
        </div>
    </div>

    <div id="modalInfoUbicacion" class="modal-overlay" style="display:none">
        <div class="modal-container">
            <div class="modal-header">
                <h2>C&oacute;mo registrar una ubicaci&oacute;n</h2>
                <button type="button" class="modal-close" onclick="cerrarModal('modalInfoUbicacion')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="about-features">
                    <div class="about-feat"><strong>1.</strong> Debes estar frente a la casa o ubicaci&oacute;n del cliente.</div>
                    <div class="about-feat"><strong>2.</strong> Al abrir el modal, el GPS capturar&aacute; autom&aacute;ticamente las coordenadas en tiempo real.</div>
                    <div class="about-feat"><strong>3.</strong> Toma una foto de la fachada para identificar la casa.</div>
                    <div class="about-feat"><strong>4.</strong> Si no est&aacute;s en la ubicaci&oacute;n del cliente, no podr&aacute;s registrar la direcci&oacute;n exacta.</div>
                    <div class="about-feat" style="background:#fff3cd;color:#664d03;border:1px solid #ffc107">
                        <strong>Importante:</strong> Esto es para tener la direcci&oacute;n exacta.
                        La foto + coordenadas = ubicaci&oacute;n precisa.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="cerrarModal('modalInfoUbicacion')">Entendido</button>
            </div>
        </div>
    </div>

    <div id="modalInfo" class="modal-overlay" style="display:none">
        <div class="modal-container">
            <div class="modal-header">
                <h2>LEONEL GAS</h2>
                <button type="button" class="modal-close" onclick="cerrarModal('modalInfo')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="about-logo">
                    <img src="<?= assetUrl('img/logo.png') ?>" alt="LEONEL GAS">
                </div>
                <p><strong>Sistema de Clientes y Ubicaciones</strong></p>
                <p style="margin-top:8px;color:var(--text-muted);font-size:.9rem">
                    Sistema para registrar y gestionar las ubicaciones de los clientes.
                    Ya no dependemos de un chofer o vendedor que "sepa" la direcci&oacute;n:
                    con las coordenadas GPS y la foto de la fachada, cualquier repartidor
                    puede llegar al domicilio sin problemas.
                </p>
                <hr style="border:none;border-top:1px solid var(--border);margin:16px 0">
                <div class="about-features">
                    <div class="about-feat"> Registrar ubicaci&oacute;n con GPS</div>
                    <div class="about-feat"> Tomar foto de fachada</div>
                    <div class="about-feat"> Venta de balones de gas</div>
                    <div class="about-feat"> Enviar comprobante por WhatsApp</div>
                    <div class="about-feat"> Navegar al cliente con Google Maps</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="cerrarModal('modalInfo')">Cerrar</button>
            </div>
        </div>
    </div>

    <div id="modalFoto" class="modal-overlay" style="display:none" onclick="cerrarModalFoto(event)">
        <div class="modal-body modal-foto">
            <button type="button" class="modal-close" onclick="cerrarModalFoto()">&times;</button>
            <img id="fotoGrande" src="" alt="Foto fachada">
        </div>
    </div>

    <script>
        var BASE_URL = '<?= baseUrl() ?>/index.php';
        <?php if (isset($clienteData)): ?>
        var CURRENT_CLIENTE = <?= json_encode(['id' => $clienteData['id'], 'nombre' => $clienteData['nombre']], JSON_UNESCAPED_UNICODE) ?>;
        <?php endif; ?>
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="<?= assetUrl('js/app.js') ?>"></script>
</body>
</html>
