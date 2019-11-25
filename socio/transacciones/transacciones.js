var respuesta, respuesta;

// Inicializa la aplicación
function inicio() {
	var paroimpar, idsocio, xmlhttp = new XMLHttpRequest();
	idsocio = sessionStorage.getItem("idsocio");
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			respuesta = JSON.parse(this.responseText);
			transacciones = respuesta.transacciones;
			console.log(transacciones);
			// Rellena la lista de transacciones
			pintartransacciones();
			limpiar();
		}
	};
	xmlhttp.open("GET", "../../php/transac_socio.php?idsocio="+idsocio, true);
	xmlhttp.send();
	document.getElementById("btnvolver").addEventListener('click', function(){
		window.open(sessionStorage.getItem("url_back"), "_self") }
	);
}

// limpia el formulario
function limpiar() {
	var acciones = document.getElementsByTagName('input');
	for (var i = 0; i < acciones.length; i++) {
		acciones[i].checked = false;
	}
	if (acciones.length > 0) {
		acciones[0].focus();
	}
}

// Envía los datos del formulario
function accion(id) {
	let arr_accion = id.split ("-");
	let datos = new FormData();
	datos.append("accion", arr_accion[0]);
	datos.append("transaccion", arr_accion[1]);

	let xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			respuesta = JSON.parse(this.responseText);
			if (respuesta.exito == 'SI') {
				alert(respuesta.mensaje);
				for (var i = transacciones.length-1; i >= 0; i--) {
					document.getElementById("tabla").removeChild(document.getElementById('fila-'+transacciones[i].id));
				}
				inicio();
				limpiar();
			}
		}
	};
	xmlhttp.open("POST", "../../php/confirma_transaccion_socio.php", true);
	xmlhttp.send(datos);
}

// Rellena la tabla
function pintartransacciones() {
	par = true;
	for (var i = 0; i < transacciones.length; i++) {
		// nombre en la columna 1
		fecha = transacciones[i].fecha.substr(8,2)+'/'+transacciones[i].fecha.substr(5,2)+'/'+transacciones[i].fecha.substr(0,4);
		txtfecha = document.createTextNode(fecha);
		cl1 = document.createElement("div");
		cl1.classList.add("columna1");
		cl1.appendChild(txtfecha);

		// banco en la columna 2
		txtbanco = document.createTextNode(transacciones[i].nombreproveedor);
		cl2 = document.createElement("div");
		cl2.classList.add("columna2");
		cl2.appendChild(txtbanco);

		// referencia en la columna 3
		txtreferencia = document.createTextNode(transacciones[i].documento);
		cl3 = document.createElement("div");
		cl3.classList.add("columna3");
		cl3.appendChild(txtreferencia);

		// monto en la columna 4
		txtmonto = document.createTextNode(formatNumber.new(transacciones[i].monto));
		cl4 = document.createElement("div");
		cl4.classList.add("columna4");
		cl4.appendChild(txtmonto);

		// acciones en la columna 4
		// Confirmar
		txtconfirmar = document.createTextNode('Confirmar');
		btnconfirmar = document.createElement("button");
		btnconfirmar.classList.add("btnopciones");
		btnconfirmar.id = 'confirmar-'+transacciones[i].id;
		// Agregar evento click para cambiar el status
		btnconfirmar.addEventListener('click', function(){ accion(this.id) });
		btnconfirmar.appendChild(txtconfirmar);
		// Rechazar
		txtrechazar = document.createTextNode('Rechazar');
		btnrechazar = document.createElement("button");
		btnrechazar.classList.add("btnopciones");
		btnrechazar.id = 'rechazar-'+transacciones[i].id;
		// Agregar evento click para cambiar el status
		btnrechazar.addEventListener('click', function(){ accion(this.id) });
		btnrechazar.appendChild(txtrechazar);
		// Agregar objetos a la columna 5
		cl5 = document.createElement("div");
		cl5.classList.add("columna5");
		cl5.appendChild(btnconfirmar);
		cl5.appendChild(btnrechazar);

		// Crear fila para la transacción
		fila = document.createElement("div");
		fila.id = 'fila-'+transacciones[i].id;
		fila.classList.add("fila");
		// Agregar la clase para definir el comportamiento del css
		if (par) {
			fila.classList.add("par");
			paroimpar = "par";
			par = false;
		} else {
			fila.classList.add("impar");
			paroimpar = "impar";
			par = true;
		}
		// Agregar las columnas a la fila
		fila.appendChild(cl1);
		fila.appendChild(cl2);
		fila.appendChild(cl3);
		fila.appendChild(cl4);
		fila.appendChild(cl5);

		// Agregar la fila a la tabla
		document.getElementById("tabla").appendChild(fila);
	}
}
