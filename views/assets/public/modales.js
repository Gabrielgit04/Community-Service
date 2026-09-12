// Apertura del dialog de eliminación desde los botones de la tabla
function openDeleteDialog(ci) {
    const hiddenCi = document.getElementById("deleteCi");
    const target = document.getElementById("deleteTarget");
    if (hiddenCi) hiddenCi.value = ci;
    if (target) target.textContent = ci;

    const dialog = document.getElementById("dialog-delete-confirm");
    if (dialog && !dialog.open) dialog.showModal();
}

function closeDialogTwo() {
    const dialog = document.getElementById("dialog-delete-confirm");
    dialog.close();
}

// Envío de la eliminación vía POST (con token CSRF en el formulario oculto).
function validarFormularioEliminar() {
    const cedula = document.getElementById("deleteCi").value;
    if (!cedula) {
        Toast.error('Ingrese una cédula válida antes de confirmar.');
        return false;
    }
    const form = document.getElementById("form-delete");
    if (form && typeof form.submit === "function") {
        form.submit();
    }
}

// Apertura del dialog de edición desde los botones de la tabla
function openEditDialog(ci) {
    const ciInput = document.getElementById('edit_ID_CI');
    if (ciInput) ciInput.value = ci;

    // Reset del formulario de edición a su estado inicial
    const select = document.getElementById('choice-update');
    if (select) select.selectedIndex = 0;

    const fixed = document.getElementById('editCampoFijo');
    const createField = document.getElementById('dinamic-input');
    const removeField = document.getElementById('div-input-update');
    if (fixed) fixed.value = '';
    if (createField) { createField.style.display = 'none'; createField.innerHTML = ''; }
    if (removeField) removeField.style.display = 'block';

    const dialogEdit = document.getElementById('dialog-edit');
    if (dialogEdit && !dialogEdit.open) dialogEdit.showModal();
}

function closeEditDialogFather() {
    const dialog = document.getElementById('dialog-edit');
    dialog.close();
}

// Segundo dialog de update (confirmación)
function twoDialogUpdate() {
    const dialogUpdateTwo = document.getElementById('dialog-update-confirm');
    if (dialogUpdateTwo && !dialogUpdateTwo.open) dialogUpdateTwo.showModal();
}

function closeEditDialog() {
    const dialog = document.getElementById('dialog-update-confirm');
    dialog.close();
}

// Envío del formulario de edición (vía GET al controller)
function validarFormularioEditar() {
    // Leer valor de la cédula
    const cedula = document.getElementById("edit_ID_CI").value.trim();
    if (!cedula) {
        Toast.error('Ingrese una cédula válida antes de confirmar.');
        return false;
    }

    // Leer opción seleccionada
    const choiceUpdate = document.getElementById("choice-update").value;
    if (!choiceUpdate) {
        Toast.error('Seleccione una opción válida antes de confirmar.');
        return false;
    }

    // Detectar si el campo dinámico está activo
    const campoDinamico = document.getElementById("editCampo");
    const campoFijo = document.getElementById("editCampoFijo"); // tu input fijo original

    let updateField = "";

    if (campoDinamico && campoDinamico.style.display !== "none") {
        // Si es un SELECT, validar que no esté en la opción deshabilitada
        if (campoDinamico.tagName === "SELECT" && campoDinamico.selectedIndex === 0) {
            Toast.error('Seleccione una opción válida antes de confirmar.');
            return false;
        }
        updateField = campoDinamico.value.trim();
    } else if (campoFijo && campoFijo.style.display !== "none") {
        updateField = campoFijo.value.trim();
    }

    if (!updateField) {
        Toast.error('El campo está vacío.');
        return false;
    }

    // Enviar vía POST con el token CSRF del formulario oculto.
    const hiddenForm = document.getElementById("form-update");
    if (!hiddenForm || typeof hiddenForm.submit !== "function") {
        Toast.error('No se pudo enviar la actualización.');
        return false;
    }
    document.getElementById("form-update-cedula").value = cedula;
    document.getElementById("form-update-choice").value = choiceUpdate;
    document.getElementById("form-update-value").value = updateField;
    hiddenForm.submit();
}

function changeInput() {
    const campo = document.getElementById("choice-update");
    const removeField = document.getElementById("div-input-update"); // contenedor del campo fijo
    const createField = document.getElementById("dinamic-input");    // contenedor del campo dinámico

    campo.addEventListener("change", function () {
        const specialFields = ["Birth_Date", "Voting_Center", "Committee_Name", "Vote_Type", "Sex"];
        
        // Limpiar siempre antes de crear
        createField.innerHTML = "";
        
        if (specialFields.includes(this.value)) {
            if (removeField) removeField.style.display = "none";
            createField.style.display = "flex";

            let newField;

            switch (this.value) {
                case "Birth_Date":
                    newField = document.createElement("input");
                    newField.type = "date";
                    break;
                case "Voting_Center":
                    newField = document.createElement("select");
                    newField.innerHTML = `
                        <option selected disabled>Selecciona el centro de votación</option>
                        <option value="Liceo Bolivariano Maestro Gallegos">Liceo Bolivariano Maestro Gallegos</option>
                        <option value="Caipa">Caipa</option>
                        <option value="Alicia Tremont de Medina">Alicia Tremont de Medina</option>
                        <option value="Inces">Inces</option>
                    `;
                    break;
                case "Committee_Name":
                    newField = document.createElement("select");
                    newField.innerHTML = `
                        <optgroup label="Seleccione un comité">
                            <option value="alimentacion">Alimentación</option>
                            <option value="economia_comunal">Economía comunal</option>
                            <option value="empleo">Empleo</option>
                            <option value="deporte_juventud">Deporte y juventud</option>
                            <option value="energia_gas">Mesa técnica de energía y gas</option>
                            <option value="agua">Mesa técnica de agua</option>
                            <option value="educacion_cultura">Educación, cultura y formación ciudadana</option>
                            <option value="habitat_tierra">Hábitat, vivienda y tierra</option>
                            <option value="medios_alternativos">Medios alternativos</option>
                            <option value="seguridad_defensa">Seguridad y defensa</option>
                            <option value="proteccion_nna">Protección de NNA</option>
                            <option value="salud">Salud</option>
                            <option value="planificacion">Planificación</option>
                            <option value="parlamento">Parlamento</option>
                        </optgroup>
                    `;
                    break;
                case "Vote_Type":
                    newField = document.createElement("select");
                    newField.innerHTML = `
                        <option selected disabled>Selecciona el tipo de voto</option>
                        <option value="Presencial">Presencial</option>
                        <option value="Asistido">Asistido</option>
                    `;
                    break;
                case "Sex":
                    newField = document.createElement("select");
                    newField.innerHTML = `
                        <option selected disabled>Selecciona tu género</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                    `;
                    break;
            }

            // Asignar ID único al campo dinámico
            newField.id = "editCampo";
            createField.appendChild(newField);

        } else {
            // Mostrar el campo fijo si no es especial
            createField.style.display = "none";
            if (removeField) removeField.style.display = "block";
        }
    });
}