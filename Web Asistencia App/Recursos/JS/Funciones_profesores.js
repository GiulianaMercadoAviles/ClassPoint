const boton_profesores = document.getElementById("boton_profesores");
const lista_profesores = document.getElementById("lista_profesor");

const boton_registrar_profesores = document.getElementById("boton_registrar_profesores");
const registrar_profesores = document.getElementById("registrar_profesores");

const boton_asignar_profesor = document.getElementById("boton_asignar_profesor");
const asignar_profesor = document.getElementById("asignar_profesor");

const editar_profesor = document.getElementById("editar_profesor");
const datos_profesor = document.getElementById("datos_profesor");


boton_profesores.addEventListener("click", function() {
    if (lista_profesores.style.display === "none") {
        lista_profesores.style.display = "grid";
        registrar_profesores.style.display = "none";
        editar_profesor.style.display = "none";
        asignar_profesor.style.display = "none";
    }
});

boton_registrar_profesores.addEventListener("click", function() {
    if (registrar_profesores.style.display === "none") {
        registrar_profesores.style.display = "flex";
        lista_profesores.style.display = "none";
        editar_profesor.style.display = "none";
        asignar_profesor.style.display = "none";
    }
});

function Perfil_Profesor(id) {
    // Establecer el valor del input oculto con el ID de la institución
    document.getElementById('input_profesor').value = id;
  
    // Enviar el formulario
    document.getElementById('form_profesor').submit();
}

function cargarDatosProfesor(id) {
    
    editar_profesor.style.display = "block";
    datos_profesor.style.display = "none";

    console.log(id)
    document.getElementById('input_editar').value = id;
}

function Eliminar_Profesor() {
    console.log()
    Swal.fire({
        title: "¿Desea eliminar al profesor?",
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
                title: "Profesor eliminado con éxito!",
                showConfirmButton: false,
                timer: 1500
            });

            setTimeout(() => {
                document.getElementById('form_eliminar_profesor').submit();
            }, 1600);
        } else if (result.isDenied) {
            Swal.fire("No se elimino el profesor");
          }
    }).catch((error) => {
        console.error("Error en la eliminación:", error);
    });
}

function Registrar_Profesor(id) {

    let nombre = document.getElementById("nombre").value;
    let apellido = document.getElementById("apellido").value;
    let fecha_nacimiento = document.getElementById('fecha_nacimiento').value;
    let dni = document.getElementById("dni").value;
    let legajo = document.getElementById("legajo").value;
    let email = document.getElementById("email").value;
    let contrasena = document.getElementById("contrasena").value;
    let fecha_actual = new Date();

    if (!nombre || !apellido || !fecha_nacimiento || !dni || !legajo || !email || !contrasena) {
        Swal.fire({
            icon: "error",
            title: "Debe rellenar los datos del profesor"
        });

    } else {

        if (dni.length !== 8) {
            Swal.fire({
                icon: "error",
                title: "El D.N.I debe tener 8 dígitos"
            });
    
        } else if (legajo.length !== 8) {
            Swal.fire({
                icon: "error",
                title: "El número de legajo debe tener 8 dígitos"
            });
    
        } else if (fecha_nacimiento >= fecha_actual) {
            Swal.fire({
                icon: "error",
                title: "Fecha de Nacimineto inválida"
            });
    
        } else {

            console.log(id)
            Swal.fire({
                title: "¿Desea registrar al profesor?",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Registrar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Profesor registrado con éxito!",
                        showConfirmButton: false,
                        timer: 1500
                    });

                    setTimeout(() => {
                        document.getElementById('form_registrar_profesor').submit();
                    }, 1600);
                }
            }).catch((error) => {
                console.error("Error en la eliminación:", error);
            });
        }
    }
}


function Editar_Profesor(id) {

    let nombre = document.getElementById("nombre").value;
    let apellido = document.getElementById("apellido").value;
    let fecha_nacimiento = document.getElementById('fecha_nacimiento').value;
    let dni = document.getElementById("dni").value;
    let legajo = document.getElementById("legajo").value;
    let email = document.getElementById("email").value;
    let fecha_actual = new Date();

    if (!nombre || !apellido || !fecha_nacimiento || !dni || !legajo || !email) {
        Swal.fire({
            icon: "error",
            title: "Debe rellenar los datos del profesor"
        });

    } else {

        if (dni.length !== 8) {
            Swal.fire({
                icon: "error",
                title: "El D.N.I debe tener 8 dígitos"
            });
    
        } else if (legajo.length !== 8) {
            Swal.fire({
                icon: "error",
                title: "El número de legajo debe tener 8 dígitos"
            });
    
        } else if (fecha_nacimiento >= fecha_actual) {
            Swal.fire({
                icon: "error",
                title: "Fecha de Nacimiento inválida"
            });
    
        } else { 
    console.log(id)
    Swal.fire({
        title: "¿Desea guardar los cambio?",
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Guardar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Informacion del profesor editada con éxito!",
                showConfirmButton: false,
                timer: 1500
            });

            setTimeout(() => {
                document.getElementById('form_editar_profesor').submit();
            }, 1600);
        }
    }).catch((error) => {
        console.error("Error en la edición:", error);
    });
}}}

function Asignar_Profesor(id) {

    const materia = document.getElementById('asignar_materia').value;

    if (!materia) {
        Swal.fire({
          icon: 'warning',
          title: 'Seleccione una materia',
          text: 'Por favor, elija la materia antes de continuar.'
        });
        return;
      } else {

    Swal.fire({
        title: "¿Desea asignar al profesor?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Profesor asignado con éxito!",
                showConfirmButton: false,
                timer: 1500
            });

            setTimeout(() => {
                document.getElementById('asignar_profesor').value = id;
                document.getElementById('form_asignar_profesor').submit();
            }, 1600);
        }
    }).catch((error) => {
        console.error("Error en la asignacíon:", error);
    });
}}

function Desasignar_Profesor(id) {
    console.log(id)
    Swal.fire({
        title: "¿Desea desasignar al profesor de la materia?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Se ha eliminado la asignación con éxito!",
                showConfirmButton: false,
                timer: 1500
            });

            setTimeout(() => {
                document.getElementById('input_desasignar_materia').value = id;
                document.getElementById('form_desasignar').submit();
            }, 1600);
        }
    }).catch((error) => {
        console.error("Error en la eliminación:", error);
    });
}