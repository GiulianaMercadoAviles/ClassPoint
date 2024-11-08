// Seleccionar institucion
document.querySelectorAll('.institucion').forEach(function(div) {
    div.addEventListener('click', function() {
      const institucionId = this.getAttribute('data-id');

      // Establecer el valor del input oculto con el ID de la institución
      document.getElementById('inputInstitucion').value = institucionId;

      // Enviar el formulario
      document.getElementById('formInstitucion').submit();
    });
});

document.querySelectorAll('.institucion').forEach(function(div) {
    div.addEventListener('click', function() {
    const institucionId = this.getAttribute('data-id');
      console.log(institucionId);
        
      // Establecer el valor del input oculto con el ID de la institución
      document.getElementById('input_institucion').value = institucionId;
  
      // Enviar el formulario
      document.getElementById('form_institucion').submit();
    });
});

const boton_instituciones = document.getElementById("boton_instituciones");
const lista_instituciones = document.getElementById("lista_instituciones");
  
const boton_registrar_instituciones = document.getElementById("boton_registrar_instituciones");
const registrar_instituciones = document.getElementById("registrar_instituciones");
  
const boton_editar_instituciones = document.getElementById("boton_editar_instituciones");
const editar_institucion = document.getElementById("editar_institucion");
  
const boton_eliminar_instituciones = document.getElementById("boton_eliminar_instituciones");
const eliminar_instituciones = document.getElementById("eliminar_instituciones");
  
boton_instituciones.addEventListener("click", function() {
  
  if (lista_instituciones.style.display === "none") {

    lista_instituciones.style.display = "flex";
    registrar_instituciones.style.display = "none";
    editar_institucion.style.display = "none";
    eliminar_instituciones.style.display = "none";
    }
});
  
boton_registrar_instituciones.addEventListener("click", function() {
  
  if (registrar_instituciones.style.display === "none") {

    registrar_instituciones.style.display = "flex";
    lista_instituciones.style.display = "none";
    editar_institucion.style.display = "none";
    eliminar_instituciones.style.display = "none";
    }
});

function Editar_Institucion(id) {

  editar_institucion.style.display = "flex";
  lista_instituciones.style.display = "none";
  registrar_instituciones.style.display = "none";
  
  document.getElementById('input_editar').value = id;
}
  
boton_eliminar_instituciones.addEventListener("click", function() {
  
  if (eliminar_instituciones.style.display === "none") {
    eliminar_instituciones.style.display = "flex";
    lista_instituciones.style.display = "none";
    editar_instituciones.style.display = "none";
    registrar_instituciones.style.display = "none";
  }
});
  
document.querySelectorAll('.institucion').forEach(function(div) {
  div.addEventListener('click', function() {
    const institucionId = this.getAttribute('data-id');
    console.log(institucionId);
    
    // Establecer el valor del input oculto con el ID de la institución
    document.getElementById('input_institucion').value = institucionId;
    
    // Enviar el formulario
    document.getElementById('form_institucion').submit();
  });
});
  
function Eliminar_Institucion(id) {

    Swal.fire({
      title: "¿Desea eliminar la institución?",
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
          title: "Institución eliminada con éxito!",
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

function Guardar_Institucion(id) {

  let nombre = document.getElementById("nombre_editar").value
    let direccion = document.getElementById("direccion_editar").value
    let cue = document.getElementById("cue_editar").value
    
    if (!nombre || !direccion || !cue){
      Swal.fire({
          icon: "error",
          title: "Debe rellenar los datos de la Institución"
      });

    } else {

      if (cue.length !== 9) {
        Swal.fire({
            icon: "error",
            title: "El C.U.E debe tener 9 dígitos"
        });

    } else {  
      Swal.fire({
          title: "¿Desea guardar los cambios?",
          showCancelButton: true,
          confirmButtonText: "Guardar",
          denyButtonText: `Cancelar`
      }).then((result) => {
          if (result.isConfirmed) {
              // Show success message and wait for it to close
              Swal.fire({
                  position: "center",
                  icon: "success",
                  title: "Institución editada con éxito!",
                  showConfirmButton: false,
                  timer: 1500
              }).then(() => {

                setTimeout(() => {
                  document.getElementById('form_editar').submit();
                }, 1600);
              });
          }
      });
    }
  }
}

function Registrar_Institucion() {

    let nombre = document.getElementById("nombre").value
    let direccion = document.getElementById("direccion").value
    let cue = document.getElementById("cue").value
    
    if (!nombre || !direccion || !cue){
      Swal.fire({
          icon: "error",
          title: "Debe rellenar los datos de la Institución"
      });

    } else {

      if (cue.length !== 9) {
        Swal.fire({
            icon: "error",
            title: "El C.U.E debe tener 9 dígitos"
        });

    } else {

        Swal.fire({
          title: "¿Desea registrar la Institución?",
          icon: "info",
          showCancelButton: true,
          confirmButtonText: "Registrar",
          cancelButtonText: "Cancelar"
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire({
              position: "center",
              icon: "success",
              title: "Institución registrada con éxito!",
              showConfirmButton: false,
              timer: 1500
            });
        
            setTimeout(() => {
              document.getElementById('form_registrar').submit();
            }, 1600);
          } else if (result.isDenied) {
            Swal.fire("No se elimino el alumno");
          }
        }).catch((error) => {
          console.error("Error en la eliminación:", error);
        });
    }
  }
}
