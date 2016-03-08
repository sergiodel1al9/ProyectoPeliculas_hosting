// Asignación de la inicialización de Javascript en la web
window.onload = inicializar;

/**
 * Función para inicializar la página, las variables y eventos necesarios para 
 * su funcionamiento
 * @returns {undefined}
 */
function inicializar()
{

    // Asignamos las funciones a los botones correspondientes
    document.getElementById('añadirActor').addEventListener('click', enviarPostCompleto);
    document.getElementById('añadirDirector').addEventListener('click', enviarPostCompleto);
    document.getElementById('añadirGuionista').addEventListener('click', enviarPostCompleto);

    // Recuperamos todos los botones con nombre Eliminar que se corresponderán 
    // con los botones de eliminar actores, directores y guionistas
    var botonesEliminar = document.getElementsByName('Eliminar');

    // Iteramos por los botones
    for (var i = 0; i < botonesEliminar.length; i++)
    {
        // Les asignamos la función de enviarPostCompleto para que se envie su 
        // información junto a la de formulario formDatosBasicos
        botonesEliminar[i].addEventListener('click', enviarPostCompleto);
    }
}


/**
 * Función que nos permite crear un elemento input oculto
 * @param {string} nombre Nombre del elemento a crear
 * @param {string} valor Valor del elemento a crear
 * @returns {crearInputOculto.el|Element} Un elemento input oculto con el valor 
 * y nombre pasado como parámetro
 */
function crearInputOculto(nombre, valor)
{
    // Creamos el elemento
    var el = document.createElement("input");

    // Lo definimos como tipo oculto
    el.type = "hidden";

    // Le asignamos un nombre
    el.name = nombre;

    // Le asignamos un valor
    el.value = valor;

    // Devolvemos el elemento creado
    return el;

}

/**
 * Función que nos permite realizar un envío de formulario con los valores necesarios para repintar los datos de pantalla;
 * @param {event} event El evento que lanza la función
 * @returns {undefined}
 */
function enviarPostCompleto(event)
{
    myForm = document.getElementById(event.srcElement.id).parentNode;

    myForm.appendChild(crearInputOculto('nombre', document.getElementById('nombre').value));
    myForm.appendChild(crearInputOculto('anyo', document.getElementById('anyo').value));
    myForm.appendChild(crearInputOculto('duracion', document.getElementById('duracion').value));
    myForm.appendChild(crearInputOculto('sinopsis', document.getElementById('sinopsis').value));
    myForm.appendChild(crearInputOculto('genero', document.getElementById('genero').value));
    myForm.appendChild(crearInputOculto('id_pelicula', document.getElementById('id_pelicula').value));

    myForm.submit();

}