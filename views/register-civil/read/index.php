<?php
$__root = dirname(__DIR__);
while (!is_file($__root . '/config.php')) { $__root = dirname($__root); }
require_once $__root . '/config.php';
session_start();

if (!isset($_SESSION['correo'])) {
    redirect('/views/login/index.php');
};

$mensaje = '';
if (isset($_SESSION['delete']) && $_SESSION['delete'] == true) {
    $mensaje = 'El ciudadano ha sido eliminado correctamente';
    unset($_SESSION['delete']);
}
if (isset($_SESSION['mensaje_update']) && $_SESSION['mensaje_update'] == true) {
    $mensaje = 'Registro actualizado correctamente';
    unset($_SESSION['mensaje_update']);
}
$toastError = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);

$db = conexionDB();

if (!empty($_SESSION['search'])) {
    $civiles = $_SESSION['search'];
    unset($_SESSION['search']);
} else {
    if (!empty($db)) {
        $sql = $db->prepare('SELECT * FROM People_Data');
        $sql->execute();
        $civiles = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <base href="<?php echo base_url('/'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/assets/css/base.css">
    <link rel="stylesheet" href="views/assets/css/rud.css">
    <script src="views/assets/public/toasts.js"></script>
    <script src="views/assets/public/transition.js"></script>
    <title>Civiles registrados</title>
</head>

<body>
    <header class="header-registradas">
        <?php if (!empty($mensaje)): ?>
            <script>Toast.success(<?php echo json_encode($mensaje); ?>);</script>
        <?php endif; ?>

        <?php if (!empty($toastError)): ?>
            <script>Toast.error(<?php echo json_encode($toastError); ?>);</script>
        <?php endif; ?>



        <div class="header-left">
            <a href="views/register-civil/home-register.html" class="btn-back" title="Volver">
                <img src="views/assets/imgs/icons/nav/arrow-left.svg" alt="Volver">
            </a>
            <h2>Personas registradas en la comunidad</h2>
        </div>
        <span class="span-search">
            <form action="controller/register-civil/search.php" autocomplete="off" method="POST">
                <button id="btnSearch"><img src="views/assets/imgs/icons/actions/search.svg" alt="search"></button>
                <input type="search" name="search" class="search" id="search" placeholder="Buscar por Nombre | Apellido | Cedula" style="text-transform: capitalize; " required>
            </form>
        </span>
    </header>
    <section class="read-all-cont">

        <div class="table-container" readonly>
            <table>
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Género</th>
                        <th>Teléfono</th>
                        <th>Comité</th>
                        <th>Direccion</th>
                        <th>Fecha de nacimiento</th>
                        <th>Edad</th>
                        <th>Email</th>
                        <th>Codigo del carnet de la patria</th>
                        <th>Serial del carnet de la patria</th>
                        <th>Centro de votacion</th>
                        <th>Tipo de voto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se insertarán las filas de datos dinámicamente -->
                    <?php if (is_array($civiles)): ?>
                        <?php foreach ($civiles as $civil): ?>
                            <tr>
                                <td><?php echo $civil['ID_CI'] ?></td>
                                <td><?php echo htmlspecialchars($civil['FirstName']) ?></td>
                                <td><?php echo htmlspecialchars($civil['LastName']) ?></td>
                                <td><?php echo htmlspecialchars($civil['Sex']) ?></td>
                                <td><?php echo $civil['Phone_Number'] ?></td>
                                <td><?php echo htmlspecialchars($civil['Committee_Name']) ?></td>
                                <td><?php echo htmlspecialchars($civil['Address_Civil']) ?></td>
                                <td><?php echo $civil['Birth_Date'] ?></td>
                                <td><?php echo $civil['Age'] ?></td>
                                <td><?php echo htmlspecialchars($civil['Email_Address']) ?></td>
                                <td><?php echo htmlspecialchars($civil['Patria_Card_Code']) ?></td>
                                <td><?php echo htmlspecialchars($civil['Patria_Card_Serial']) ?></td>
                                <td><?php echo htmlspecialchars($civil['Voting_Center']) ?></td>
                                <td><?php echo htmlspecialchars($civil['Vote_Type']) ?></td>
                                <td>
                                    <div class="row-actions">
                                        <button type="button" class="action-btn edit" title="Editar" onclick="openEditDialog(<?php echo (int)$civil['ID_CI'] ?>)">
                                            <img src="views/assets/imgs/icons/actions/edit.svg" alt="Editar">
                                        </button>
                                        <button type="button" class="action-btn delete" title="Eliminar" onclick="openDeleteDialog(<?php echo (int)$civil['ID_CI'] ?>)">
                                            <img src="views/assets/imgs/icons/actions/trash.svg" alt="Eliminar">
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif ?>

                </tbody>
            </table>
        </div>
        <dialog id="dialog-delete-confirm">
            <h3 style="color: darkslategray;">Confirmar eliminación</h3>
            <p>¿Estás seguro de que deseas eliminar al ciudadano con cédula <b id="deleteTarget">—</b>? Esta acción no se puede deshacer.</p>
            <input type="hidden" id="deleteCi" value="">
            <div class="actions">
                <button class="danger" name='confirmDelete' id="confirm-btn" type="button" onclick="validarFormularioEliminar()">Eliminar</button>
                <button class="cancel" id="close" type="button" onclick="closeDialogTwo()">Cancelar</button>
            </div>
        </dialog>


        <!-- Edit dialog -->
        <dialog id="dialog-edit" class="dialog-edit">
            <span><img src="views/assets/imgs/icons/actions/xbox-x.svg" alt="hidemodal" onclick="closeEditDialogFather()" id="close-edit-btn"></span>
            <h3>Editar registro</h3>
            <div class="form-index" autocomplete="off">
                    <div class="input_area">
                        <input type="text" name="cedula" id="edit_ID_CI" class="entry" placeholder="Cédula" autocomplete="off" readonly>
                        <div class="labelline"><span><img src="views/assets/imgs/icons/fields/id.svg" alt="icon" class="icon_id"></span></div>
                    </div>
                    <div class="input_area">
                        <label for="choice-update" name="choiceUpdate" class="sr-only">Campo a actualizar</label>
                        <select name="choiceUpdate" id="choice-update" required onclick="changeInput()">
                            <option value="FirstName">Nombres</option>
                            <option value="LastName">Apellidos</option>
                            <option value="ID_CI">Cédula</option>
                            <option value="Sex">Sexo (M/F)</option>
                            <option value="Phone_Number">Teléfono</option>
                            <option value="Committee_Name">Comité al que pertenece</option>
                            <option value="Address_Civil">Dirección</option>
                            <option value="Birth_Date">Fecha de nacimiento</option>
                            <option value="Age">Edad</option>
                            <option value="Email_Address">Correo electrónico</option>
                            <option value="Patria_Card_Code">Código del carnet de la patria</option>
                            <option value="Patria_Card_Serial">Serial del carnet de la patria</option>
                            <option value="Voting_Center">Centro de votación</option>
                            <option value="Vote_Type">Tipo de voto</option>
                        </select>
                    </div>
                    <div class="input_area" id="div-input-update">
                        <input type="text" name="UPDATE_FIELD" id="editCampoFijo" class="entry" placeholder="Actualice el campo" autocomplete="off">
                        <div class="labelline"><span><img src="views/assets/imgs/icons/actions/user-edit.svg" alt="icon" class="icon-user-edit"></span></div>
                    </div>
                    <div id="dinamic-input"></div>

                    <div class="btns-update">
                        <button type="submit" id="btn-submit-update" class="submit-btn-edit" onclick="twoDialogUpdate()">Actualizar</button>
                    </div>
            </div>
        </dialog>
        <dialog id="dialog-update-confirm">
            <h3>Confirmar actualización</h3>
            <p>¿Estás seguro de que deseas actualizar la información de este usuario?</p>
            <div class="actions">
                <button type="button" class="submit-btn-edit" id="confirm-btn" onclick="validarFormularioEditar()">Confirmar</button>
                <button type="button" class="cancel" id="closeDialogTwo" onclick="closeEditDialog()">Cancelar</button>
            </div>
        </dialog>


    </section>

    <script src="views/assets/public/modales.js"></script>
</body>

</html>