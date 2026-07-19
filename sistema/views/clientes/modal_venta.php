<div id="modalVenta" class="modal-overlay" style="display:none">
    <div class="modal-container">
        <div class="modal-header">
            <h2>Registrar Venta</h2>
            <button type="button" class="modal-close" onclick="cerrarModal('modalVenta')">&times;</button>
        </div>

        <form id="formVenta" onsubmit="guardarVenta(event)" autocomplete="off">
            <input type="hidden" name="cliente_id" id="ventaClienteId">
            <div class="modal-body" id="ventaFormBody">
                <p class="text-muted" id="ventaClienteNombre" style="margin-bottom:16px;font-weight:600"></p>

                <div class="form-group">
                    <label for="ventaBidonesVendidos">Balones de gas vendidos</label>
                    <input type="number" id="ventaBidonesVendidos" name="bidones_vendidos"
                           class="form-control input-lg" min="0" step="1" value="0"
                           inputmode="numeric" oninput="calcularTotalVenta()"
                           onfocus="this.select()" placeholder="Cantidad de balones">
                </div>

                <div class="form-group">
                    <label for="ventaPrecio">Precio por balón (S/)</label>
                    <input type="number" id="ventaPrecio" name="precio"
                           class="form-control input-lg" min="0" step="0.10" value="0"
                           inputmode="decimal" oninput="calcularTotalVenta()"
                           onfocus="this.select()" placeholder="0.00">
                </div>

                <div class="form-group">
                    <label for="ventaBidonesVacios">Balones vacíos devueltos</label>
                    <input type="number" id="ventaBidonesVacios" name="bidones_vacios"
                           class="form-control input-lg" min="0" step="1" value="0"
                           inputmode="numeric" onfocus="this.select()"
                           placeholder="Cantidad de balones vacíos">
                </div>

                <div class="total-box" id="ventaTotalBox">
                    Total: S/ <span id="ventaTotal">0.00</span>
                </div>
            </div>

            <div id="ventaTicket" class="modal-body" style="display:none">
                <div class="ticket">
                    <div class="ticket-header">
                        <strong>COMPROBANTE DE VENTA</strong>
                    </div>
                    <div class="ticket-body" id="ventaTicketBody"></div>
                    <div class="ticket-actions">
                        <button type="button" class="btn btn-success btn-lg btn-block"
                                id="btnWhatsApp" onclick="enviarWhatsApp()">
                            Enviar por WhatsApp
                        </button>
                        <button type="button" class="btn btn-primary btn-lg btn-block"
                                onclick="imprimirTicket()">
                            Imprimir ticket
                        </button>
                        <button type="button" class="btn btn-secondary btn-lg btn-block"
                                onclick="cerrarModal('modalVenta')">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal-footer" id="ventaFooter">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalVenta')">Cancelar</button>
                <button type="submit" class="btn btn-success btn-lg">Guardar venta</button>
            </div>
        </form>
    </div>
</div>
