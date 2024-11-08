const boton_lista_alumnos = document.getElementById("boton_lista_alumnos");
const lista_alumnos = document.getElementById("lista_alumnos");

const boton_registrar_alumnos = document.getElementById("boton_registrar_alumnos");
const dar_alta_alumnos = document.getElementById("dar_alta_alumnos");

const editar_alumno = document.getElementById("editar_alumno");

boton_lista_alumnos.addEventListener("click", function() {

    if (lista_alumnos.style.display === "none") {
        lista_alumnos.style.display = "flex";
        dar_alta_alumnos.style.display = "none";
        editar_alumno.style.display = "none";
    }
});

boton_registrar_alumnos.addEventListener("click", function() {

    if (dar_alta_alumnos.style.display === "none") {
        dar_alta_alumnos.style.display = "flex";
        lista_alumnos.style.display = "none";
        editar_alumno.style.display = "none";
    }
});

function Perfil_Alumnos(id) {
  
    // Establecer el valor del input oculto con el ID de la institución
    document.getElementById('input_alumno').value = id;
    document.getElementById('form_alumnos').submit();
}

function Modificar_datos(id) {

    if (editar_alumno.style.display === "none") {
        editar_alumno.style.display = "flex";
        lista_alumnos.style.display = "none";
        dar_alta_alumnos.style.display = "none";
    }

    document.getElementById('input_editar').value = id;
}

function Eliminar_Alumno(id) {

    Swal.fire({
        title: "¿Desea eliminar al alumno?",
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
                title: "Alumno eliminado con éxito!",
                showConfirmButton: false,
                timer: 1500
            });

            setTimeout(() => {
                document.getElementById('form_eliminar_alumno').submit();
            }, 1600);
        } else if (result.isDenied) {
            Swal.fire("No se elimino el alumno");
          }
    }).catch((error) => {
        console.error("Error en la eliminación:", error);
    });
}

function Registrar_Alumno(id) {
    console.log(id)

    let nombre = document.getElementById("nombre").value
    let apellido = document.getElementById("apellido").value
    let fecha_nacimiento = document.getElementById("fecha_nacimiento").value
    let dni = document.getElementById("dni").value

    if(!nombre || !apellido || !fecha_nacimiento || !dni){
        Swal.fire({
            icon: "error",
            title: "Debe rellenar los datos del alumno"
        });

    } else {

        if (dni.length !== 8) {
            Swal.fire({
                icon: "error",
                title: "El D.N.I debe tener 8 dígitos"
            });
    
        } else if (fecha_nacimiento >= fecha_actual) {

            Swal.fire({
                icon: "error",
                title: "Fecha de Nacimiento inválida"
            });
    
        } else {

            Swal.fire({
                title: "¿Desea registrar al alumno?",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Registrar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Alumno registrado con éxito!",
                        showConfirmButton: false,
                        timer: 1500
                    });

                    setTimeout(() => {
                        document.getElementById('form_registrar_alumno').submit();
                    }, 1600);
                }
            }).catch((error) => {
                console.error("Error en la eliminación:", error);
            });
        }
    }
}

function Editar_Alumno() {

    let fecha_actual = new Date();
    let nombre = document.getElementById("nombre_editar").value;
    let apellido = document.getElementById("apellido_editar").value;
    let fecha_nacimiento = document.getElementById("fecha_nacimiento_editar").value;
    let dni = document.getElementById("dni_editar").value;

    if (!nombre || !apellido || !fecha_nacimiento || !dni){
        Swal.fire({
            icon: "error",
            title: "Debe rellenar los datos del alumno"
        });

    } else {

        if (dni.length !== 8) {
            Swal.fire({
                icon: "error",
                title: "El D.N.I debe tener 8 dígitos"
            });
    
        } else if (fecha_nacimiento >= fecha_actual) {

            Swal.fire({
                icon: "error",
                title: "Fecha de Nacimiento inválida"
            });
    
        } else {

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
                        title: "Informacion del alumno editada con éxito!",
                        showConfirmButton: false,
                        timer: 1500
                    });

                    setTimeout(() => {
                        document.getElementById('form_editar_alumno').submit();
                    }, 1600);
                }
            }).catch((error) => {
                console.error("Error en la edición:", error);
            });
        }
    }
}

function Inscribir_Alumno(id) {

    const materia = document.getElementById('materia').value;

    if (!materia) {
        Swal.fire({
          icon: 'warning',
          title: 'Seleccione una materia',
          text: 'Por favor, elija la materia antes de continuar.'
        });
        return;

    } else {

        Swal.fire({
            title: "¿Dese inscribir al alumno?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Aceptar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Alumno inscripto con éxito!",
                    showConfirmButton: false,
                    timer: 1500
                });

                setTimeout(() => {
                    document.getElementById('form_inscribir_alumno').submit();
                }, 1600);
            }
        }).catch((error) => {
            console.error("Error en la inscripción:", error);
        });
    }
}

function Desinscribir_Alumno(id) {

    Swal.fire({
        title: "¿Desea desinscribir al alumno de la materia?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Se ha eliminado la inscripción con éxito!",
                showConfirmButton: false,
                timer: 1500
            });

            setTimeout(() => {
                document.getElementById('input_desinscribir_materia').value = id;
                document.getElementById('form_desinscribir').submit();
            }, 1600);
        }
    }).catch((error) => {
        console.error("Error en la eliminación:", error);
    });
}