$(function () {
    initSelect2('#selectCliente', BASE_URL + '/?action=clientes.buscar');
    initSelect2('#selectClienteModal', BASE_URL + '/?action=clientes.buscar');

    if (typeof CURRENT_CLIENTE !== 'undefined') {
        preseleccionarCliente(CURRENT_CLIENTE.id, CURRENT_CLIENTE.nombre);
    }

    var theme = localStorage.getItem('theme');
    if (theme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
        $('#btnTheme').text('\u2600');
    } else {
        $('#btnTheme').text('\uD83C\uDF19');
    }
});

function salir() {
    window.location.href = BASE_URL + '/?action=auth.salir';
}

function abrirInfo() {
    $('#modalInfo').fadeIn(150);
}

function toggleTheme() {
    var html = document.documentElement;
    var current = html.getAttribute('data-theme');
    if (current === 'dark') {
        html.removeAttribute('data-theme');
        localStorage.setItem('theme', 'light');
        $('#btnTheme').text('\uD83C\uDF19');
    } else {
        html.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
        $('#btnTheme').text('\u2600');
    }
}

function initSelect2(selector, url) {
    $(selector).select2({
        placeholder: 'Buscar cliente...',
        minimumInputLength: 1,
        allowClear: true,
        ajax: {
            url: url,
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return { results: data };
            },
            cache: true
        }
    });
}

function buscarCliente() {
    var id = $('#selectCliente').val();
    if (!id) return;
    verCliente(id);
}

function abrirModalRegistrar(clienteId) {
    $('#modalUbicacion').fadeIn(150);
    if (clienteId) {
        preseleccionarCliente(clienteId, '');
    }
}

function abrirModalNuevoCliente() {
    $('#modalUbicacion').hide();
    $('#modalCliente').fadeIn(150);
    $('#formCliente')[0].reset();
    $('#clienteId').val('');
    $('#tituloModalCliente').text('Nuevo Cliente');
}

function preseleccionarCliente(id, nombre) {
    var $select = $('#selectClienteModal');
    if (nombre) {
        var option = new Option(nombre, id, true, true);
        $select.append(option).trigger('change');
    } else {
        $.get(BASE_URL + '/?action=clientes.obtener&id=' + id, function (data) {
            var option = new Option(data.nombre, data.id, true, true);
            $select.append(option).trigger('change');
        });
    }
}

function obtenerUbicacion() {
    var status = $('#ubicacionStatus');
    status.removeClass('success error info').show().html('Obteniendo ubicaci\u00f3n...').addClass('info');

    if (!navigator.geolocation) {
        status.removeClass('info').addClass('error').html('Geolocalizaci\u00f3n no soportada en este navegador.');
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function (pos) {
            $('#ubicacionLatitud').val(pos.coords.latitude);
            $('#ubicacionLongitud').val(pos.coords.longitude);
            $('#ubicacionPrecision').val(pos.coords.accuracy);
            status.removeClass('info').addClass('success')
                .html('Ubicaci\u00f3n obtenida. Precisi\u00f3n: ' + pos.coords.accuracy.toFixed(1) + ' metros');
        },
        function (err) {
            var msg = 'Error al obtener ubicaci\u00f3n: ';
            switch (err.code) {
                case err.PERMISSION_DENIED: msg += 'Permiso denegado'; break;
                case err.POSITION_UNAVAILABLE: msg += 'Se\u00f1al no disponible'; break;
                case err.TIMEOUT: msg += 'Tiempo de espera agotado'; break;
                default: msg += 'Error desconocido (' + err.code + ')';
            }
            status.removeClass('info').addClass('error').html(msg);
        },
        {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 0
        }
    );
}

function previsualizarFoto(event) {
    var file = event.target.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function (e) {
        $('#fotoPreviewImg').attr('src', e.target.result);
        $('#fotoPreview').show();
    };
    reader.readAsDataURL(file);
}

function quitarFoto() {
    $('#ubicacionFoto').val('');
    $('#fotoPreview').hide();
    $('#fotoPreviewImg').attr('src', '');
}

function guardarCliente(event) {
    event.preventDefault();
    var form = $('#formCliente');
    var data = new FormData(form[0]);
    var editando = $('#clienteId').val() > 0;

    $.ajax({
        url: BASE_URL + '/?action=clientes.guardar',
        method: 'POST',
        data: data,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                if (editando) {
                    cerrarModal('modalCliente');
                    location.reload();
                } else {
                    var option = new Option(res.nombre, res.id, true, true);
                    $('#selectClienteModal').append(option).trigger('change');
                    cerrarModal('modalCliente');
                    $('#modalUbicacion').fadeIn(150);
                }
            } else {
                alert(res.message || 'Error al guardar cliente');
            }
        },
        error: function () {
            alert('Error de conexi\u00f3n');
        }
    });
}

function guardarUbicacion(event) {
    event.preventDefault();
    var form = $('#formUbicacion');
    var data = new FormData(form[0]);
    data.set('cliente_id', $('#selectClienteModal').val());

    if (!data.get('cliente_id')) {
        alert('Seleccione un cliente');
        return;
    }

    $.ajax({
        url: BASE_URL + '/?action=ubicaciones.guardar',
        method: 'POST',
        data: data,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                cerrarModal('modalUbicacion');
                location.reload();
            } else {
                alert(res.message || 'Error al guardar ubicaci\u00f3n');
            }
        },
        error: function (jqXHR) {
            var msg = 'Error de conexi\u00f3n';
            try {
                var r = JSON.parse(jqXHR.responseText);
                if (r.message) msg = r.message;
            } catch(e) {}
            alert(msg);
        }
    });
}

function abrirModalVenta(clienteId, clienteNombre) {
    $('#ventaClienteId').val(clienteId);
    $('#ventaClienteNombre').text(clienteNombre);
    $('#ventaBidonesVendidos').val(0);
    $('#ventaPrecio').val(0);
    $('#ventaBidonesVacios').val(0);
    $('#ventaTotal').text('0.00');
    $('#modalVenta').fadeIn(150);
    setTimeout(function () { $('#ventaBidonesVendidos').focus(); }, 200);
}

function calcularTotalVenta() {
    var qty = parseFloat($('#ventaBidonesVendidos').val()) || 0;
    var price = parseFloat($('#ventaPrecio').val()) || 0;
    var total = qty * price;
    $('#ventaTotal').text(total.toFixed(2));
}

var ultimaVenta = null;
var ventasPaginadas = [];
var ventaActualIdx = 0;

function guardarVenta(event) {
    event.preventDefault();
    var data = new FormData($('#formVenta')[0]);

    $.ajax({
        url: BASE_URL + '/?action=ventas.guardar',
        method: 'POST',
        data: data,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                ultimaVenta = res;
                mostrarTicket(res);
            } else {
                alert(res.message || 'Error al registrar venta');
            }
        },
        error: function () {
            alert('Error de conexi\u00f3n');
        }
    });
}

function mostrarTicket(res) {
    var d = res.detalle;
    var c = res.cliente;
    var now = new Date();
    var fecha = now.toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' });
    var hora = now.toLocaleTimeString('es-PE', { hour: '2-digit', minute: '2-digit' });

    $('#ventaFormBody').hide();
    $('#ventaFooter').hide();

    var html = '';
    html += '<div class="ticket-row"><span>Cliente:</span><strong>' + escHtml(c.nombre) + '</strong></div>';
    html += '<div class="ticket-row"><span>Fecha:</span><span>' + fecha + ' ' + hora + '</span></div>';
    html += '<hr>';
    html += '<div class="ticket-row"><span>Balones vendidos:</span><strong>' + d.bidones_vendidos + '</strong></div>';
    html += '<div class="ticket-row"><span>Precio x balón:</span><span>S/ ' + parseFloat(d.precio).toFixed(2) + '</span></div>';
    html += '<div class="ticket-row"><span>Balones vacíos:</span><span>' + d.bidones_vacios + '</span></div>';
    html += '<hr>';
    html += '<div class="ticket-row ticket-total"><span>TOTAL:</span><strong>S/ ' + res.total.toFixed(2) + '</strong></div>';

    ultimaVenta.cliente = c;
    ultimaVenta.fecha = fecha;
    ultimaVenta.hora = hora;

    $('#ventaTicketBody').html(html);
    $('#ventaTicket').show();
}

function enviarWhatsApp() {
    if (!ultimaVenta) return;
    var c = ultimaVenta.cliente;
    var d = ultimaVenta.detalle;
    var msg = 'Hola ' + c.nombre + ', este es tu comprobante de venta:%0A%0A' +
              'Balones vendidos: ' + d.bidones_vendidos + '%0A' +
              'Precio x balón: S/ ' + parseFloat(d.precio).toFixed(2) + '%0A' +
              'Balones vacíos devueltos: ' + d.bidones_vacios + '%0A' +
              'TOTAL: S/ ' + ultimaVenta.total.toFixed(2) + '%0A%0A' +
              'Gracias por tu preferencia.';

    var phone = c.celular ? c.celular.replace(/[^0-9]/g, '') : '';
    if (!phone || phone.length < 9) {
        alert('El cliente no tiene un número de celular registrado. Configure su número en Editar cliente.');
        return;
    }
    window.open('https://wa.me/51' + phone + '?text=' + msg, '_blank');
}

function imprimirTicket() {
    if (!ultimaVenta) return;
    var c = ultimaVenta.cliente;
    var d = ultimaVenta.detalle;
    var ventana = window.open('', '_blank', 'width=320,height=500');
    ventana.document.write(
        '<html><head><title>Ticket</title>' +
        '<style>body{font-family:monospace;font-size:14px;padding:16px;text-align:center}' +
        'hr{border-top:1px dashed #000}.total{font-size:18px;font-weight:bold;margin-top:8px}</style>' +
        '</head><body>' +
        '<h3>COMPROBANTE</h3>' +
        '<p>' + c.nombre + '<br>' + ultimaVenta.fecha + ' ' + ultimaVenta.hora + '</p><hr>' +
        '<p>Balones: ' + d.bidones_vendidos + ' x S/ ' + parseFloat(d.precio).toFixed(2) + '</p>' +
        '<p>Vacíos: ' + d.bidones_vacios + '</p><hr>' +
        '<p class="total">TOTAL: S/ ' + ultimaVenta.total.toFixed(2) + '</p><hr>' +
        '<p style="font-size:12px;color:#666">Gracias por su preferencia</p>' +
        '</body></html>'
    );
    ventana.document.close();
    ventana.focus();
    ventana.print();
}

function verCliente(id) {
    $.get(BASE_URL + '/?action=clientes.ubicaciones&id=' + id, function (res) {
        var c = res.cliente;
        var ubicaciones = res.ubicaciones;
        var ventas = res.ventas;

        $('#detalleNombre').text(c.nombre);

        var html = '<div class="detail-card">';
        if (c.dni) html += '<div class="detail-row"><span class="detail-label">DNI</span><span class="detail-value">' + escHtml(c.dni) + '</span></div>';
        if (c.celular) html += '<div class="detail-row"><span class="detail-label">Celular</span><span class="detail-value">' + escHtml(c.celular) + '</span></div>';
        if (c.detalle) html += '<div class="detail-row"><span class="detail-label">Detalle</span><span class="detail-value">' + escHtml(c.detalle).replace(/\n/g, '<br>') + '</span></div>';
        html += '</div>';

        html += '<div class="actions-top" style="display:flex;gap:8px;flex-wrap:wrap">';
        html += '<button type="button" class="btn btn-success btn-lg" style="flex:1" onclick="cerrarModal(\'modalDetalle\'); abrirModalRegistrar(' + c.id + ')">+ Ubicaci\u00f3n</button>';
        html += '<button type="button" class="btn btn-warning btn-lg" style="flex:1" onclick="cerrarModal(\'modalDetalle\'); abrirModalVenta(' + c.id + ', \'' + escHtml(c.nombre) + '\')">+ Venta</button>';
        html += '</div>';

        if (ventas.length > 0) {
            ventasPaginadas = ventas;
            ventaActualIdx = 0;
            html += '<div class="venta-paginada" id="ventaPaginada">' + renderVentaPagina(0) + '</div>';
        }

        html += '<h3 style="font-size:.95rem;margin:12px 0 6px">Ubicaciones</h3>';
        if (ubicaciones.length === 0) {
            html += '<p class="text-muted">Sin ubicaciones registradas</p>';
        } else {
            html += '<div class="table-responsive"><table class="table"><thead><tr>';
            html += '<th>Detalle</th><th>Coordenadas</th><th>Precisi\u00f3n</th><th>Foto</th><th>Fecha</th><th>Acciones</th>';
            html += '</tr></thead><tbody>';

            $.each(ubicaciones, function (i, u) {
                html += '<tr>';
                html += '<td data-label="Detalle">' + escHtml(u.detalle || '') + '</td>';
                html += '<td data-label="Coordenadas">' + (u.latitud && u.longitud ? '<span class="coords">' + u.latitud + ', ' + u.longitud + '</span>' : '') + '</td>';
                html += '<td data-label="Precisi\u00f3n">' + (u.precision_gps ? u.precision_gps + 'm' : '') + '</td>';
                html += '<td data-label="Foto" class="text-center">';
                if (u.foto_url) {
                    html += '<img src="' + u.foto_url + '" alt="Foto" class="thumb" onclick="verFoto(\'' + u.foto_url + '\')">';
                } else {
                    html += '<span class="no-photo">-</span>';
                }
                html += '</td>';
                html += '<td data-label="Fecha">' + (u.fecha_registro ? formatDate(u.fecha_registro) : '') + '</td>';
                html += '<td data-label="Acciones" class="actions-cell">';
                html += '<button type="button" class="btn btn-sm btn-outline" onclick="irAlCliente(' + (u.latitud || 'null') + ', ' + (u.longitud || 'null') + ')">Ir</button>';
                html += '</td></tr>';
            });

            html += '</tbody></table></div>';
        }

        $('#detalleBody').html(html);
        $('#modalDetalle').fadeIn(150);
    });
}

function escHtml(str) {
    if (!str) return '';
    return $('<span>').text(str).html();
}

function formatDate(dateStr) {
    if (!dateStr) return '';
    var parts = dateStr.split('-');
    return parts[2] + '/' + parts[1] + '/' + parts[0];
}

function editarCliente(id) {
    $.get(BASE_URL + '/?action=clientes.obtener&id=' + id, function (data) {
        $('#clienteId').val(data.id);
        $('#clienteNombre').val(data.nombre);
        $('#clienteDni').val(data.dni || '');
        $('#clienteCelular').val(data.celular || '');
        $('#clienteDetalle').val(data.detalle || '');
        $('#tituloModalCliente').text('Editar Cliente');
        $('#modalCliente').fadeIn(150);
    });
}

function irAlCliente(lat, lng) {
    if (!lat || !lng) {
        alert('Este cliente no tiene coordenadas registradas');
        return;
    }
    if (!navigator.geolocation) {
        var url = 'https://www.google.com/maps/dir/?api=1&destination=' + lat + ',' + lng;
        window.open(url, '_blank');
        return;
    }
    navigator.geolocation.getCurrentPosition(
        function (pos) {
            var url = 'https://www.google.com/maps/dir/' +
                      pos.coords.latitude + ',' + pos.coords.longitude + '/' +
                      lat + ',' + lng;
            window.open(url, '_blank');
        },
        function () {
            var url = 'https://www.google.com/maps/dir/?api=1&destination=' + lat + ',' + lng;
            window.open(url, '_blank');
        },
        { enableHighAccuracy: true, timeout: 10000 }
    );
}

function verFoto(url) {
    $('#fotoGrande').attr('src', url);
    $('#modalFoto').fadeIn(150);
}

function cerrarModalFoto(event) {
    if (!event || event.target.id === 'modalFoto' || event.target.classList.contains('modal-close')) {
        $('#modalFoto').fadeOut(150);
    }
}

function cerrarModal(id) {
    $('#' + id).fadeOut(150);
}

function renderVentaPagina(idx) {
    var v = ventasPaginadas[idx];
    if (!v) return '';
    var total = ventasPaginadas.length;
    var html = '<h3 style="font-size:.95rem;margin:12px 0 6px">Venta ' + (idx + 1) + ' de ' + total + '</h3>';
    html += '<div class="venta-card">';
    html += '<div class="venta-card-row"><span>Fecha</span><strong>' + formatDate(v.fecha) + '</strong></div>';
    html += '<div class="venta-card-row"><span>Balones vendidos</span><strong>' + v.bidones_vendidos + '</strong></div>';
    html += '<div class="venta-card-row"><span>Precio x bal\u00f3n</span><span>S/ ' + parseFloat(v.precio).toFixed(2) + '</span></div>';
    html += '<div class="venta-card-row"><span>Balones vac\u00edos</span><span>' + v.bidones_vacios + '</span></div>';
    html += '<div class="venta-divider"></div>';
    html += '<div class="venta-card-row venta-total"><span>TOTAL</span><strong>S/ ' + parseFloat(v.total).toFixed(2) + '</strong></div>';
    html += '</div>';

    if (total > 1) {
        html += '<div class="venta-paginacion">';
        html += '<button type="button" class="btn btn-outline" onclick="ventaAnterior()" ' + (idx === 0 ? 'disabled' : '') + '>\u2190 Anterior</button>';
        html += '<span class="venta-contador">' + (idx + 1) + ' / ' + total + '</span>';
        html += '<button type="button" class="btn btn-outline" onclick="ventaSiguiente()" ' + (idx === total - 1 ? 'disabled' : '') + '>Siguiente \u2192</button>';
        html += '</div>';
    }

    return html;
}

function ventaAnterior() {
    if (ventaActualIdx > 0) {
        ventaActualIdx--;
        $('#ventaPaginada').html(renderVentaPagina(ventaActualIdx));
    }
}

function ventaSiguiente() {
    if (ventaActualIdx < ventasPaginadas.length - 1) {
        ventaActualIdx++;
        $('#ventaPaginada').html(renderVentaPagina(ventaActualIdx));
    }
}

$(document).on('keydown', function (e) {
    if (e.key === 'Escape') {
        $('.modal-overlay').fadeOut(150);
    }
});
