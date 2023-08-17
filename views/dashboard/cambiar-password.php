<?php include_once __DIR__ . '/header_dash.php'; ?>

<div class="contenedor-sm">
    <?php 
        include_once __DIR__ . '/../templates/alertas.php';
    ?>

    <a href="/perfil" class="enlace">Volver al perfil</a>


    <form method="POST" action="/cambiar-password" class="formulario">
        <div class="campo">
            <label for="password">Password Actual</label>
            <input 
                type="password"
                name="password_actual"
                placeholder="Tu password actual"
                >
        </div>

        <div class="campo">
            <label for="password">Password nuevo</label>
            <input 
                type="password"
                name="password_nuevo"
                placeholder="Tu password nuevo"
                >
        </div>

        <input type="submit" value="Guardar Cambios">
    </form>
</div>

<?php include_once __DIR__ . '/footer_dash.php'; ?>