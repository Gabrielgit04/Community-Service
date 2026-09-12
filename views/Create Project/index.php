<?php
$__root = dirname(__DIR__);
while (!is_file($__root . '/config.php')) { $__root = dirname($__root); }
require_once $__root . '/config.php';

requireLogin();

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<base href="<?php echo base_url('/'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/assets/css/base.css">
    <link rel="stylesheet" href="views/assets/css/create project.css">
    <script src="views/assets/public/toasts.js"></script>
    <script src="views/assets/public/transition.js"></script>
    <title>Crear Proyecto</title>
</head>
<body>

    <header class="proj-form-header">
        <a href="views/index.php" class="volver" title="Volver">
            <img src="views/assets/imgs/icons/nav/arrow-left.svg" alt="Volver">
        </a>
        <h1>Creación del Proyecto</h1>
    </header>

    <form id="formulario_datos" action="<?php echo base_url('/controller/system-project/create project.php') ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="list-option">
            <article class="contenedor_entradas1">
                <input type="hidden" name="Id" value="<?php $id_project= mt_rand(10000000, 99999999); echo "$id_project" ?>">
                <label class="campo">
                    <span>Título del Proyecto</span>
                    <input class="titulo" type="text" minlength="10" maxlength="150" autocomplete="off" placeholder="Ejemplo: Recuperación del parque..." name="Titulo" required>
                </label>
                <label class="campo">
                    <span>Fecha de Inicio</span>
                    <input class="fecha" type="date" name="Fecha_inicio" min="2020-01-01" max="2050-12-31" required>
                </label>
                <label class="campo">
                    <span>Fecha de Culminación</span>
                    <input class="fecha" type="date" name="Fecha_culminacion" min="2020-01-01" max="2050-12-31" required>
                </label>
                <label class="campo">
                    <span>Estado</span>
                    <select class="estado" name="Estado" required>
                        <option value="Planificando">Planificando</option>
                        <option value="En_Proceso">En Proceso</option>
                        <option value="Incompleto">Incompleto</option>
                        <option value="Finalizado">Finalizado</option>
                    </select>
                </label>
            </article>

            <article class="contenedor_entradas2">
                <h2>Lista de Entradas y Precios</h2>
                <div class="contenedor_entradas" id="contenedor_entradas"></div>

                <button type="button" class="btn-accion btn-agregar" onclick="agregarCampo()">Agregar Entrada</button>

                <div class="total-box">
                    Total Presupuestado: <span id="total_suma">0.00</span>
                </div>

                <input type="submit" value="Guardar Proyecto" class="btn-accion btn-agregar btn-guardar">
            </article>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            agregarCampo();
        });

        let indiceEntrada = 0;

        function agregarCampo() {
            indiceEntrada++;
            const contenedor = document.getElementById('contenedor_entradas');

            const grupo = document.createElement('div');
            grupo.classList.add('entrada-grupo');
            grupo.dataset.id = indiceEntrada;

            const inputTexto = document.createElement('input');
            inputTexto.type = 'text';
            inputTexto.name = 'descripciones[' + indiceEntrada + ']';
            inputTexto.placeholder = `Describa el Gasto`;
            inputTexto.required = true;
            inputTexto.autocomplete = 'off';

            const labelPrecio = document.createElement('label');
            labelPrecio.textContent = 'Precio:';

            const inputPrecio = document.createElement('input');
            inputPrecio.type = 'text';
            inputPrecio.inputMode = 'decimal';
            inputPrecio.name = 'precios[' + indiceEntrada + ']';
            inputPrecio.placeholder = 'Precio Bolivar';
            inputPrecio.required = true;
            inputPrecio.autocomplete = 'off';
            inputPrecio.addEventListener('input', calcularTotal);

            const botonEliminar = document.createElement('button');
            botonEliminar.type = 'button';
            botonEliminar.classList.add('btn-accion', 'btn-eliminar');
            botonEliminar.textContent = 'Eliminar';
            botonEliminar.onclick = function() {
                eliminarCampo(grupo);
            };

            grupo.appendChild(inputTexto);
            grupo.appendChild(labelPrecio);
            grupo.appendChild(inputPrecio);
            grupo.appendChild(botonEliminar);

            contenedor.appendChild(grupo);

            calcularTotal();
        }

        function eliminarCampo(elementoGrupo) {
            elementoGrupo.remove();
            calcularTotal();
        }

        function calcularTotal() {
            const camposPrecio = document.querySelectorAll('#contenedor_entradas input[name^="precios"]');
            let sumaTotal = 0;

            camposPrecio.forEach(input => {
                let valorStr = input.value || '0';
                valorStr = valorStr.replace(',', '.');
                const valor = parseFloat(valorStr) || 0;
                sumaTotal += valor;
            });

            document.getElementById('total_suma').textContent = sumaTotal.toFixed(2);
        }
    </script>

</body>
</html>
