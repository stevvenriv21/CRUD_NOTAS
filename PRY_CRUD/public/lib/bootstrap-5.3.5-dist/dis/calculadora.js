function enviarValor(arg) {
    //almacenar capturar el valor de mi elemento boton ->(data-valor)
    //6.9.4.etc
    var valor = arg.dataset.valor;
    console.log(valor)

    //almacenar elemento input id txt_caja_texto
    var ca_texto = document.getElementById("txt_caja_texto");

    if (valor === "x" || valor === "-" || valor === "/" || valor === "+") {
        var1 = parseFloat(ca_texto.value);
        operador = valor;
        ca_texto.value = valor;

    } else if (valor === "=") {
        var2 = parseFloat(ca_texto.value);
        var resultado = 0;

        if (operador === "x") {
            resultado = var1 * var2;
        } else if (operador === "/") {
            resultado = var1 / var2;
        } else if (operador === "+") {
            resultado = var1 + var2;
        } else if (operador === "-") {
            resultado = var1 - var2;
        }
        ca_texto.value = resultado;

    } else {
        if (ca_texto.value === "+" || ca_texto.value === "-" || ca_texto.value === "/" || ca_texto.value === "x") {
            ca_texto.value = "";
        }
        ca_texto.value += valor;
    }
}