const boton_ingresar_nota = document.getElementById("boton_ingresar_nota");
const ingresar_notas = document.getElementById("ingresar_notas");

const boton_lista_notas = document.getElementById("boton_lista_notas");
const lista_notas = document.getElementById("lista_notas");

boton_ingresar_nota.addEventListener("click", function() {
    if (ingresar_notas.style.display === "none") {
        ingresar_notas.style.display = "grid";
        lista_notas.style.display = "none";
    } 
});

boton_lista_notas.addEventListener("click", function() {
    if (lista_notas.style.display === "none") {
        lista_notas.style.display = "grid";
        ingresar_notas.style.display = "none";
    }
});

function Editar_nota(id) {

  const editar_nota = document.getElementById("div_editar_nota");
  editar_nota.style.display = "grid";
  lista_notas.style.display = "none";
  ingresar_notas.style.display = "none";
  
  document.getElementById('input_editar').value = id;
}

function Guardar_edicion(id) {

  const instancia = document.getElementById('instancia_notas').value;
  const nota_editar = document.getElementById('input_nota_editar').value;

  if (!instancia || !nota_editar) {

    Swal.fire({
      icon: 'warning',
      title: 'Complete los campos',
      text: 'Por favor, complete los campos de instancia y de nota.'
    });
    return;

  } else {

    if (nota_editar < 1 || nota_editar > 10) {

      Swal.fire({
        icon: 'warning',
        title: 'Nota inválida',
        text: 'La nota debe ser un número entre 1 y 10.'
      });
      return;

    } else {

      Swal.fire({
        title: "¿Desea editar la nota?",
        showCancelButton: true,
        confirmButtonText: "Confirmar"
      }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
            position: "center",
            icon: "success",
            title: "La nota ha sido editada con éxito!",
            showConfirmButton: false,
            timer: 1500
        });
        setTimeout(() => {
          
          document.getElementById('input_nota_editar').value = id;
          document.getElementById('form_editar_notas').submit();
        }, 1600);
      }
      });
  }
}}

function Eliminar_nota(id) {

  const eliminar_nota = document.getElementById("div_eliminar_nota");
  eliminar_nota.style.display = "block";
  lista_notas.style.display = "none";
  ingresar_notas.style.display = "none";
  
  document.getElementById('input_eliminar').value = id;
}

function Eliminacion() {

  Swal.fire({
      title: "¿Desea eliminar la nota?",
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
              title: "Nota eliminada con éxito!",
              showConfirmButton: false,
              timer: 1500
          });

          setTimeout(() => {
              document.getElementById('form_eliminar_notas').submit();
          }, 1600);

      } else if (result.isDenied) {
          Swal.fire("No se elimino el alumno");
        }
        
  }).catch((error) => {
      console.error("Error en la eliminación:", error);
  });
}


function Guardar_notas() {

  const instancia = document.getElementById('instancia_notas').value;
  const nota = document.getElementById('input_nota').value;

  if (!instancia || !nota) {

    Swal.fire({
      icon: 'warning',
      title: 'Seleccione una instancia de evalución',
      text: 'Por favor, elija una instancia antes de continuar.'
    });
    return;

  } else {

    if (nota < 1 || nota > 10) {

      Swal.fire({
        icon: 'warning',
        title: 'Nota inválida',
        text: 'La nota debe ser un número entre 1 y 10.'
      });
      return;

    } else {

      Swal.fire({
          title: "¿Desea guardar las notas?",
          showDenyButton: true,
          showCancelButton: true,
          confirmButtonText: "Guardar",
          denyButtonText: `Cancelar`
        }).then((result) => {
        
          if (result.isConfirmed) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Notas guardadas con éxito!",
                showConfirmButton: false,
                timer: 1500
            });

            setTimeout(() => {
              document.getElementById('form_notas').submit();
            }, 1600);
            
          } else if (result.isDenied) {
            Swal.fire("No se guardaron las notas");
          }
      })
    };
  }
}
