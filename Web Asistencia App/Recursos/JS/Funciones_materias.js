const boton_registrar_materias = document.getElementById("boton_registrar_materias");
const registrar_materia = document.getElementById("registrar_materia");

const boton_materias = document.getElementById("boton_materias");
const lista_materias = document.getElementById("lista_materias");

boton_registrar_materias.addEventListener("click", function() {

    if (registrar_materia.style.display === "none") {
        registrar_materia.style.display = "grid";
        lista_materias.style.display = "none";
    }
});

boton_materias.addEventListener("click", function() {

    if (lista_materias.style.display === "none") {
        lista_materias.style.display = "flex";
        registrar_materia.style.display = "none";
    }
});

function Editar_Materia(id) {

    const editar_materia = document.getElementById("editar_materia");

    editar_materia.style.display = "flex";
    lista_materias.style.display = "none";
    registrar_materia.style.display = "none";

  }

function Registrar_Materia() {

    const nombre = document.getElementById("nombre").value;
    const departamento = document.getElementById("departamento").value;
    const curso = document.getElementById("curso").value; 

    if(!nombre || !departamento || !curso) {
        Swal.fire({
            icon: "error",
            title: "Debe rellenar los datos"
        });

    } else {

    Swal.fire({
        title: "¿Desea registrar la materia?",
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Registrar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Materia registrada con éxito!",
                showConfirmButton: false,
                timer: 1500
            });

            setTimeout(() => {
                document.getElementById('form_registrar_materia').submit();
            }, 1600);
        }
    }).catch((error) => {
        console.error("Error en la registración:", error);
    });
}}

function Guardar_Materia() {

    let nombre = document.getElementById("nombre_editar").value;
    let departamento = document.getElementById("departamento_editar").value;
    let curso = document.getElementById("curso_editar").value; console.log(nombre);

    if (!nombre || !departamento || !curso) {
        Swal.fire({
            icon: "error",
            title: "Debe rellenar los datos"
        });

    } else {

    Swal.fire({
        title: "¿Desea guardar las modificaciones?",
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Guardar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Materia editada con éxito!",
                showConfirmButton: false,
                timer: 1500
            });

            setTimeout(() => {
                document.getElementById('form_editar').submit();
            }, 1600);
        };
    }).catch((error) => {
        console.error("Error en la registración:", error);
    });
}}

function Eliminar_Materia(id) {
    console.log(id)
    Swal.fire({
      title: "¿Desea eliminar la materia?",
      text: "Esta acción no se puede deshacer.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Eliminar",
      cancelButtonText: "Cancelar"
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          position: "center",
          icon: "success",
          title: "Materia eliminada con éxito!",
          showConfirmButton: false,
          timer: 1500
        });
    
        setTimeout(() => {
          document.getElementById('inputEliminacion').value = id;
          document.getElementById('formEliminacion').submit();
        }, 1600);
      } else if (result.isDenied) {
        Swal.fire("No se elimino el alumno");
      }
    }).catch((error) => {
      console.error("Error en la eliminación:", error);
    });
}