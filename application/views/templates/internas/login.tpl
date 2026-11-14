<body
    style="background-image: url('{$base_url}public/imagenes/fondo_login.jpg'); background-size: 100% 100%; background-repeat: no-repeat;height: 100vh;display: block;">
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="card">
                <img class="row h-100 justify-content-center align-items-center logo-login" src="{$base_url}public/imagenes/insignia-rd.png">
                <div class="card-body">
                    <form action="{$base_url}login/ingresar" class="bform formulario" method="post">
                        <h4 class="card-title"></h4>
                        <span class="response"></span>
                        <div class="form-group">
                            <input type="text" class="form-control" name="txt_usuario" autofocus placeholder="Usuario">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="txt_contrasena" placeholder="Contraseña"
                                data-eye>
                        </div>
                        <div class="form-group no-margin">
                            <button class="save btn btn-primary btn-block"><i class="fas fa-sign-in-alt"></i> Ingresar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>