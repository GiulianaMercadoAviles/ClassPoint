document.querySelectorAll('.materia').forEach(function(div) {
    div.addEventListener('click', function() {
        const materiaId = this.getAttribute('data-id');

        document.getElementById('inputMateria').value = materiaId;

        document.getElementById('formMateria').submit();
        });
    });

const boton_lista_parametros = document.getElementById("boton_lista_parametros");
const lista_parametros = document.getElementById("lista_parametros");

const boton_modificar_parametros = document.getElementById("boton_modificar_parametros");
const modificar_parametros = document.getElementById("modificar_parametros");
  
boton_lista_parametros.addEventListener("click", function() {
    console.log("hythty")
    if (lista_parametros.style.display === "none") {
        lista_parametros.style.display = "flex";
        modificar_parametros.style.display = "none";
    }
});

boton_modificar_parametros.addEventListener("click", function() {
    if (modificar_parametros.style.display === "none") {
        modificar_parametros.style.display = "flex";
        lista_parametros.style.display = "none";
    }
});

function Modificar_ram() {

    const nota_promocion = document.getElementById('nota_promocion').value;
    const asistencia_promocion = document.getElementById('asistencia_promocion').value;
    const nota_regular = document.getElementById('nota_regular').value;
    const asistencia_regular = document.getElementById('asistencia_regular').value;

    if (!nota_promocion || !asistencia_promocion || !nota_regular || !asistencia_regular) {

        Swal.fire({
          icon: 'warning',
          title: 'Establecer Parametros',
          text: 'Por favor, establecer los nuevos parametros antes de continuar.'
        });
        return;

    } else {

        if (nota_promocion < 1 || nota_promocion > 10) {

            Swal.fire({
              icon: 'warning',
              title: 'Parametros inválido',
              text: 'Nota de promoción no valida'
            });
            return;
    
        } else if (asistencia_promocion < 1 || asistencia_promocion > 100) {

            Swal.fire({
              icon: 'warning',
              title: 'Parametros inválido',
              text: 'Asistencia de promoción no valida'
            });
            return;
    
        } else if (nota_regular < 1 || nota_regular > 100) { 
            
            Swal.fire({
            icon: 'warning',
            title: 'Parametros inválido',
            text: 'Nota de regularidad no valida'
          });
          return;
  
        } else if (asistencia_regular < 1 || asistencia_regular > 100) {
        
            Swal.fire({
                icon: 'warning',
                title: 'Parametros inválido',
                text: 'Asistencia de regularidad no valida'
            });
            return;
  
        } else {

            Swal.fire({
                title: "¿Seguro que quiere modificar los parametros?",
                showCancelButton: true,
                confirmButtonText: "Modificar",
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Parametros Modificados",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    
                    setTimeout(() => {
                        document.getElementById('form_ram').submit();  
                    }, 1600);
                }
            });
        }
    }
}
