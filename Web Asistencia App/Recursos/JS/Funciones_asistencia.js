function Guardar_Asistencia(){

    Swal.fire({
        title: "¿Desea guardar las asistencias?",
        showCancelButton: true,
        showCancelButton: true,
        confirmButtonText: "Guardar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Asistencia guardada con éxito!",
                showConfirmButton: false,
                timer: 1500
            });
            
            setTimeout(() => {
                document.getElementById('form_registrar_asistencia').submit()
            }, 1600);

        } else if (result.isDenied) {

            Swal.fire("No se guardó la asistencia");
        }
      });
}

function Registrar_Asistencia(id) {

    document.getElementById('asistencia').value = id;
    document.getElementById('form_asistencias').submit();  
}

function Eliminar_Asistencia(id_eliminar) {
    Swal.fire({
        title: "¿Desea eliminar la asistencia?",
        showCancelButton: true,
        showCancelButton: true,
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Se ha eliminado la asistencia!",
                showConfirmButton: false,
                timer: 1500
            });

            setTimeout(() => {
                document.getElementById('input_eliminar_asistencia').value = id_eliminar;
                document.getElementById('form_eliminar_asistencia').submit();; 
            }, 1600);
        } else if (result.isDenied) {

            Swal.fire("No se elimino la asistencia");
        }
    });
}

function Editar_Asistencia(id) {

    document.getElementById('input_modificar_asistencia').value = id;
    document.getElementById('form_modificar_asistencia').submit();  
}

function Seleccionar() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');

    checkboxes.forEach(checkbox => {

        checkbox.checked = true;
    });
}


const boton_asistencia_anterior = document.getElementById("asistencia_anterior");
const listado_anterior = document.getElementById("listado_anterior");
const lista_asistencia = document.getElementById("lista_asistencia");

boton_asistencia_anterior.addEventListener("click", function() {

    if (listado_anterior.style.display === "none") {
        listado_anterior.style.display = "grid";
        lista_asistencia.style.display = "none";
    }
});

