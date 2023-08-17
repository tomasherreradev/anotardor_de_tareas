<?php include_once __DIR__ . '/header_dash.php'; ?>

    <div class="contenedor-sm">
        <div class="contenedor-nueva-tarea">
            <button
                type="button"
                class="agregar-tarea"
                id="agregar-tarea"> 
                &#43;Agregar tarea</button>
        </div>

        <div class="filtros" id="filtros">
            <div class="filtros-inputs">
                <h2>Filtrar:</h2>
                <div class="campo">
                    <label for="todas">Todas</label>
                    <input 
                        type="radio" 
                        name="filtro" 
                        id="todas"
                        value=""
                        checked
                        >
                </div>

                <div class="campo">
                    <label for="completadas">Completadas</label>
                    <input 
                        type="radio" 
                        name="filtro" 
                        id="completadas"
                        value="1"
                        >
                </div>

                
                <div class="campo">
                    <label for="pendientes">Pendientes</label>
                    <input 
                        type="radio" 
                        name="filtro" 
                        id="pendientes"
                        value="0"
                        >
                </div>
            </div>
        </div>

        <ul class="listado-tareas" id="listado-tareas">
                <!-- codigo de js -->
        </ul>
    </div>

<?php include_once __DIR__ . '/footer_dash.php'; ?>

<?php 
    $script .= '
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="build/js/tareas.js"></script>
    ';
?>
