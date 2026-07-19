<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>LEONEL GAS - Iniciar sesi&oacute;n</title>
    <link rel="icon" type="image/png" href="<?= assetUrl('img/favicon.png') ?>">
    <link rel="stylesheet" href="<?= assetUrl('css/styles.css') ?>">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-box">
            <div class="login-logo">
                <img src="<?= assetUrl('img/logo.png') ?>" alt="LEONEL GAS">
            </div>
            <p class="login-sub">Clientes y Ubicaciones</p>

            <form id="formLogin" onsubmit="ingresar(event)" autocomplete="off">
                <div class="form-group">
                    <label for="loginUsuario">Usuario</label>
                    <input type="text" id="loginUsuario" name="usuario" class="form-control input-lg"
                           value="prueba" placeholder="Ingrese su usuario" required autofocus>
                </div>
                <div class="form-group">
                    <label for="loginPassword">Contrase&ntilde;a</label>
                    <input type="password" id="loginPassword" name="password" class="form-control input-lg"
                           value="prueba123" placeholder="Ingrese su contrase&ntilde;a" required>
                </div>
                <div id="loginError" class="status-msg error" style="display:none"></div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">Ingresar</button>
            </form>
        </div>
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        function ingresar(event) {
            event.preventDefault();
            var data = new FormData($('#formLogin')[0]);
            $('#loginError').hide();
            $.ajax({
                url: '<?= baseUrl() ?>/index.php?action=auth.entrar',
                method: 'POST',
                data: data,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        window.location.href = '<?= baseUrl() ?>/index.php';
                    } else {
                        $('#loginError').html(res.message || 'Error').show();
                    }
                },
                error: function () {
                    $('#loginError').html('Error de conexi&oacute;n').show();
                }
            });
        }
    </script>
</body>
</html>
