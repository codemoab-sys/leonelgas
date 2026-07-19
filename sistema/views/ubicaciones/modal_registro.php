<div id="modalUbicacion" class="modal-overlay" style="display:none">
    <div class="modal-container modal-lg">
        <div class="modal-header">
            <h2>Registrar nueva ubicación</h2>
            <button type="button" class="modal-close" onclick="cerrarModal('modalUbicacion')">&times;</button>
        </div>
        <form id="formUbicacion" onsubmit="guardarUbicacion(event)" autocomplete="off" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="form-group">
                    <label for="selectClienteModal">Cliente</label>
                    <select id="selectClienteModal" class="form-control search-select" style="width:100%"
                            required></select>
                    <button type="button" class="btn btn-sm btn-link" onclick="abrirModalNuevoCliente()">
                        + Nuevo cliente
                    </button>
                </div>

                <div class="form-group">
                    <label for="ubicacionDetalle">Detalle de ubicación</label>
                    <textarea id="ubicacionDetalle" name="detalle" class="form-control" rows="3"
                              placeholder="Casa color verde&#10;Frente al parque&#10;Segundo piso"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <label for="ubicacionLatitud">Latitud</label>
                        <input type="text" id="ubicacionLatitud" name="latitud" class="form-control"
                               placeholder="-8.109256" readonly>
                    </div>
                    <div class="form-group half">
                        <label for="ubicacionLongitud">Longitud</label>
                        <input type="text" id="ubicacionLongitud" name="longitud" class="form-control"
                               placeholder="-79.021365" readonly>
                    </div>
                </div>

                <input type="hidden" id="ubicacionPrecision" name="precision_gps">

                <button type="button" class="btn btn-info btn-lg btn-block" onclick="obtenerUbicacion()">
                    Obtener ubicación
                </button>

                <div id="ubicacionStatus" class="status-msg" style="display:none"></div>

                <div class="form-group">
                    <label>Fachada</label>
                    <div class="file-upload">
                        <input type="file" id="ubicacionFoto" name="foto"
                               accept="image/*" capture="environment"
                               onchange="previsualizarFoto(event)">
                        <label for="ubicacionFoto" class="btn btn-camera">
                            Tomar fotografía
                        </label>
                    </div>
                    <div id="fotoPreview" class="foto-preview" style="display:none">
                        <img id="fotoPreviewImg" src="" alt="Vista previa">
                        <button type="button" class="btn btn-sm btn-danger" onclick="quitarFoto()">Quitar</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalUbicacion')">Cancelar</button>
                <button type="submit" class="btn btn-primary btn-lg">Guardar ubicación</button>
            </div>
        </form>
    </div>
</div>
