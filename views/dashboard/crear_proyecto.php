<?php include_once __DIR__ . '/header_dash.php'; ?>

    <div class="contenedor-sm">
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <form method="POST" class="formulario">
            <?php include_once __DIR__ . '/formulario-proyecto.php'; ?>
            <input type="submit" value="Crear Proyecto">
        </form>
    </div>

<?php include_once __DIR__ . '/footer_dash.php'; ?>