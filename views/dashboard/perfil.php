<?php include_once __DIR__ . '/header_dash.php'; ?>

<div class="contenedor-sm">
    <?php 
        include_once __DIR__ . '/../templates/alertas.php';
    ?>

    <a href="/cambiar-password" class="enlace">Cambiar mi contraseña</a>

    <form method="POST" action="/perfil" class="formulario">
        <div class="campo">
            <label for="nombre">Nombre</label>
            <input 
                type="text"
                value="<?php echo $usuario->nombre ?>"
                name="nombre"
                placeholder="Tu nombre"
                >
        </div>

        <div class="campo">
            <label for="email">Email</label>
            <input 
                type="text"
                value="<?php echo $usuario->email ?>"
                name="email"
                placeholder="Tu email"
                >
        </div>

        <input type="submit" value="Guardar Cambios">
    </form>
</div>

<?php include_once __DIR__ . '/footer_dash.php'; ?>