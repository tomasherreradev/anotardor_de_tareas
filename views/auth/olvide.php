<div class="contenedor olvide">
    <?php
        include_once __DIR__ . '/../templates/nombre-sitio.php';
    ?>


    <div class="contenedor-sm">
        <?php
            include_once __DIR__ . '/../templates/alertas.php';
        ?>
            <p class="descripcion-pagina">Recupera tu cuenta de UpTask</p>
            <form class="formulario" method="POST" action="/olvide">
                <div class="campo">
                    <label for="email">Email</label>
                    <input 
                        type="email"
                        id="email"
                        placeholder="Tu email"
                        name="email"
                    >
                </div>
                <input type="submit" class="boton" value="Enviar Instrucciones">
            </form>

            <div class="acciones">
                <a href="/crear">¿Aún no tienes una cuenta? Obtén una</a>
                <a href="/">Iniciar Sesión</a>
            </div>
        </div> <!-- contenedor formulario -->
        

</div>

