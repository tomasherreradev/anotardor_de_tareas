<div class="contenedor reestablecer">
    <?php 
        include_once __DIR__ . '/../templates/nombre-sitio.php';
    ?>

    <div class="contenedor-sm">
    <?php 
        include_once __DIR__ . '/../templates/alertas.php';
        if($error) {
            return;
        }
    ?>
        <p class="descripcion-pagina">Coloca tu nuevo password</p>
        <form class="formulario" method="POST">

            <div class="campo">
                <label for="password">Password</label>
                <input 
                    type="password"
                    id="password"
                    placeholder="Tu password"
                    name="password"
                >
            </div>

            <input type="submit" class="boton" value="Guardar Password">
        </form>

        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Obtén una</a>
            <a href="/">Iniciar Sesión</a>
        </div>
    </div> <!-- contenedor formulario -->
    
</div>