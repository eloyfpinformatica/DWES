const mostrarMayusculas = texto => texto.toUpperCase();

function procesarTexto(texto, funcion){
    return funcion(texto);
}
console.log(procesarTexto("asdasdcasa De Juego", mostrarMayusculas));