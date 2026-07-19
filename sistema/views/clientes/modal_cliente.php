<div id="modalCliente" class="modal-overlay" style="display:none">
    <div class="modal-container">
        <div class="modal-header">
            <h2 id="tituloModalCliente">Nuevo Cliente</h2>
            <button type="button" class="modal-close" onclick="cerrarModal('modalCliente')">&times;</button>
        </div>
        <form id="formCliente" onsubmit="guardarCliente(event)" autocomplete="off">
            <input type="hidden" name="id" id="clienteId">
            <div class="modal-body">
                <div class="form-group">
                    <label for="clienteNombre">Nombre *</label>
                    <input type="text" id="clienteNombre" name="nombre" class="form-control" required
                           placeholder="Nombre del cliente">
                </div>
                <div class="form-group">
                    <label for="clienteDni">DNI</label>
                    <input type="text" id="clienteDni" name="dni" class="form-control" placeholder="12345678">
                </div>
                <div class="form-group">
                    <label for="clienteCelular">Celular</label>
                    <input type="text" id="clienteCelular" name="celular" class="form-control" placeholder="999888777">
                </div>
                <div class="form-group">
                    <label for="clienteDetalle">Detalle</label>
                    <textarea id="clienteDetalle" name="detalle" class="form-control" rows="3"
                              placeholder="Cliente nuevo&#10;Siempre paga efectivo"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalCliente')">Cancelar</button>
                <button type="submit" class="btn btn-primary btn-lg">Guardar</button>
            </div>
        </form>
    </div>
</div>
