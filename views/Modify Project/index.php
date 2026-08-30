<?php
$__root = dirname(__DIR__);
while (!is_file($__root . '/config.php')) { $__root = dirname($__root); }
require_once $__root . '/config.php';
?>

<?php

session_start();

if(!isset($_SESSION['nombre'])){
    redirect('/views/login/index.php');
};

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: No se proporcionó ID de proyecto para modificar.");
}

$id_project = htmlspecialchars($_GET['id']);
$proyecto = null;
$detalles = [];

try {
    $pdo = conexionDB();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt_project = $pdo->prepare("SELECT * FROM projects WHERE id_project = ?");
    $stmt_project->execute([$id_project]);
    $proyecto = $stmt_project->fetch(PDO::FETCH_ASSOC);

    if (!$proyecto) {
        die("Error: Proyecto con ID '$id_project' no encontrado.");
    }

    $stmt_details = $pdo->prepare("SELECT * FROM date_projects WHERE id_project = ?");
    $stmt_details->execute([$id_project]);
    $detalles = $stmt_details->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error de Base de Datos al cargar datos: " . $e->getMessage());
}

function format_price_for_display($price) {
    return str_replace('.', ',', (string)number_format($price, 2, '.', ''));
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <base href="<?php echo base_url('/'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/assets/css/base.css">
    <link rel="stylesheet" href="views/assets/css/modidy project.css">
    <script src="views/assets/public/toasts.js"></script>
    <script src="views/assets/public/transition.js"></script>
    <title>Modificar Proyecto: <?php echo htmlspecialchars($proyecto['name_project']); ?></title>
</head>
<body>

    <header class="proj-form-header">
        <a href="views/index.php" class="volver" title="Volver">
            <img src="views/assets/imgs/icons/nav/arrow-left.svg" alt="Volver">
        </a>
        <h1>Modificar Proyecto</h1>
    </header>

    <form id="formulario_datos" action="<?php echo base_url('/controller/system-project/modify project.php') ?>" method="POST">
        <div class="list-option">
            <article class="contenedor_entradas1">
                <input type="hidden" name="Id" value="<?php echo htmlspecialchars($proyecto['id_project']); ?>">
                <label class="campo">
                    <span>Título del Proyecto</span>
                    <input class="titulo" type="text" minlength="10" maxlength="150" autocomplete="off" placeholder="Título del Proyecto" name="Titulo" value="<?php echo htmlspecialchars($proyecto['name_project']); ?>" required>
                </label>
                <label class="campo">
                    <span>Fecha de Inicio</span>
                    <input class="fecha" type="date" name="Fecha_inicio" value="<?php echo htmlspecialchars($proyecto['fecha_inicio_project']); ?>" min="2020-01-01" max="2050-12-31" required>
                </label>
                <label class="campo">
                    <span>Fecha de Culminación</span>
                    <input class="fecha" type="date" name="Fecha_culminacion" value="<?php echo htmlspecialchars($proyecto['fecha_final_project']); ?>" min="2020-01-01" max="2050-12-31" required>
                </label>
                <label class="campo">
                    <span>Estado</span>
                    <select class="estado" name="Estado" required>
                        <?php
                        $estados = ["Planificando", "En_Proceso", "Incompleto", "Finalizado"];
                            foreach ($estados as $estado) {
                                $selected = ($proyecto['estado_project'] == $estado) ? 'selected' : '';
                                echo "<option value=\"$estado\" $selected> " . htmlspecialchars(str_replace('_', ' ', $estado)) . " </option>";
                            }
                        ?>
                    </select>
                </label>
            </article>

            <article class="contenedor_entradas2">
                <h2>Lista de Entradas</h2>
                <div class="contenedor_entradas" id="contenedor_entradas">
                    <?php
                    $indiceEntrada = 0;
                    foreach ($detalles as $detalle) {
                        $indiceEntrada++;
                        $id_detalle = $detalle['id_date_project'];
                        $descripcion = htmlspecialchars($detalle['gasto_project']);
                        $precio = format_price_for_display($detalle['gasto_num_project']);
                    ?>
                    <div class="entrada-grupo" data-id="<?php echo $indiceEntrada; ?>">
                        <input type="hidden" name="detalles_existentes[<?php echo $indiceEntrada; ?>][id]" value="<?php echo htmlspecialchars($id_detalle); ?>">
                        <input type="text" name="detalles_existentes[<?php echo $indiceEntrada; ?>][descripcion]" placeholder="Describa el Gasto" value="<?php echo $descripcion; ?>" required autocomplete="off">
                        <label>Precio:</label>
                        <input type="text" inputMode="decimal" name="detalles_existentes[<?php echo $indiceEntrada; ?>][precio]" placeholder="Precio Bolivar" value="<?php echo $precio; ?>" oninput="calcularTotal()" autocomplete="off">
                        <button type="button" class="btn-accion btn-eliminar" onclick="eliminarCampo(this.parentNode, '<?php echo htmlspecialchars($id_detalle); ?>')">Eliminar</button>
                    </div>
                    <?php
                    }
                    ?>
                </div>
                <button type="button" class="btn-accion btn-agregar" onclick="agregarCampo()">Agregar Entrada Nueva</button>

                <div class="total-box">
                    Total Presupuestado: <span id="total_suma"><?php echo number_format($proyecto['presupuesto_project'], 2, '.', ''); ?></span>
                </div>

                <input type="submit" value="Guardar Modificaciones" class="btn-accion btn-agregar btn-guardar">
            </article>
        </div>
    </form>

    <script>
        let indiceEntrada = <?php echo $indiceEntrada; ?>;
        let detallesAEliminar = [];

        function agregarCampo() {
            indiceEntrada++;
            const contenedor = document.getElementById('contenedor_entradas');

            const grupo = document.createElement('div');
            grupo.classList.add('entrada-grupo');
            grupo.dataset.id = indiceEntrada;

            const inputTexto = document.createElement('input');
            inputTexto.type = 'text';
            inputTexto.name = 'detalles_nuevos[' + indiceEntrada + '][descripcion]';
            inputTexto.placeholder = `Describa el Gasto (Nuevo)`;
            inputTexto.required = true;
            inputTexto.autocomplete = 'off';

            const labelPrecio = document.createElement('label');
            labelPrecio.textContent = 'Precio:';

            const inputPrecio = document.createElement('input');
            inputPrecio.type = 'text';
            inputPrecio.inputMode = 'decimal';
            inputPrecio.name = 'detalles_nuevos[' + indiceEntrada + '][precio]';
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

        function eliminarCampo(elementoGrupo, idDetalleExistente) {
            if (idDetalleExistente) {
                if (!detallesAEliminar.includes(idDetalleExistente)) {
                    detallesAEliminar.push(idDetalleExistente);
                }

                let inputOculto = document.getElementById('input_detalles_eliminar');
                if (!inputOculto) {
                    inputOculto = document.createElement('input');
                    inputOculto.type = 'hidden';
                    inputOculto.name = 'detalles_a_eliminar';
                    inputOculto.id = 'input_detalles_eliminar';
                    document.getElementById('formulario_datos').appendChild(inputOculto);
                }
                inputOculto.value = detallesAEliminar.join(',');
            }

            elementoGrupo.remove();
            calcularTotal();
        }

        function calcularTotal() {
            const camposPrecio = document.querySelectorAll('#contenedor_entradas .entrada-grupo input[name$="[precio]"]');
            let sumaTotal = 0;

            camposPrecio.forEach(input => {
                let valorStr = input.value || '0';
                valorStr = valorStr.replace(',', '.');
                const valor = parseFloat(valorStr) || 0;
                sumaTotal += valor;
            });

            document.getElementById('total_suma').textContent = sumaTotal.toFixed(2);
        }

        document.addEventListener('DOMContentLoaded', () => {
            calcularTotal();
        });
    </script>

</body>
</html>
