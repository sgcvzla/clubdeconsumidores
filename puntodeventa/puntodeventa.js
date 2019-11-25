// function inicio() {
// 	pdv.appendChild(new Campo('monto_div_id','cmps','monto_etq_id','etiq','Monto a pagar','monto_cmp_id','campo','input','50','20'));
// }

let monto="", idproveedor=sessionStorage.getItem("id_proveedor"), moneda='bs', tarjeta='';
let datos = new FormData();

idproveedor = (idproveedor==undefined) ? 2 : idproveedor;

function inicio() {
	limpiar();
	document.getElementById("btnvolver").addEventListener('click', function(){
		window.open(sessionStorage.getItem("url_back"), "_self") });
	// cargar parámetros de la tabla
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			respuesta = JSON.parse(this.responseText);
			if (respuesta.exito == 'SI') {
				// document.title = 'Menú principal';
				logo = respuesta.proveedor.logo;
				if (logo!="") {
					document.getElementById("logo").src = "../img/" + logo;
				} else {
					document.getElementById("logo").src = "../img/" + 'sin_imagen.jpg';
				}
				document.getElementById("logo").title = respuesta.proveedor.nombre;
				transacciones = respuesta.transacciones;
				rellenatransacciones(transacciones);
			}
		}
	};
	xmlhttp.open("GET", "../php/proveedores3.php?prov="+idproveedor, true);
	xmlhttp.send();
}

// limpia el formulario
function limpiar() {
	moneda = 'bs';
	monto = "";
	tarjeta = '';
	document.getElementById("divisa").value = "bs";
	document.getElementById("monto").value = "";

	document.getElementById("tarjeta").value = "";

	// document.getElementById("btnescanearqr").style.display = 'inline-block';
	// document.getElementById("btnpresentarqr").style.display = 'inline-block';

	document.getElementById("divisa").focus();
}

// valida la entrada en los campos
function validaciones() {
	let continuar = true, nocontinuar = false, vacios = 0, campo = "";
	if (document.getElementById("divisa").value=="" || document.getElementById("divisa").value==undefined) {
		alert("El campo moneda no puede quedar en blanco");
		vacios++;
		campo = "divisa";
		continuar = false;
		nocontinuar = true;
	}
	if ((document.getElementById("monto").value=="" || document.getElementById("monto").value==undefined) && vacios == 0) {
		alert("El campo monto no puede quedar en blanco");
		vacios++;
		campo = "monto";
	}
	if ((document.getElementById("tarjeta").value=="" || document.getElementById("tarjeta").value==undefined) && vacios == 0) {
		alert("El campo tarjeta no puede quedar en blanco");
		vacios++;
		campo = "tarjeta";
	}
	if (vacios>0) {
		continuar = false;
		nocontinuar = true;
	}
	if (nocontinuar) {
		document.getElementById(campo).focus();
	}
	return continuar;
}

// Enviar la transacción para procesar
function enviar() {
	if (validaciones()) {
		datos.append("idproveedor", idproveedor);
		datos.append("moneda", document.getElementById("divisa").value);
		datos.append("monto", document.getElementById("monto").value);
		datos.append("tarjeta", document.getElementById("tarjeta").value);
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				respuesta = JSON.parse(this.responseText);
				if (respuesta.exito == 'SI') {
					alert(fmensaje(respuesta.mensaje));
					limpiar();
				} else {
					alert(respuesta.mensaje);
				}
			}
		};
		xmlhttp.open("POST", "../php/enviacobro.php", true);
		xmlhttp.send(datos);
	}
}

function rellenatransacciones(transacciones) {
	par = true;
	for (var i = 0; i < transacciones.length; i++) {
		// tarjeta en la columna 1
		txttarjeta = document.createTextNode(transacciones[i].tarjeta);
		cl1 = document.createElement("div");
		cl1.classList.add("columna1");
		cl1.appendChild(txttarjeta);

		// referencia en la columna 2
		txtreferencia = document.createTextNode(transacciones[i].referencia);
		cl2 = document.createElement("div");
		cl2.classList.add("columna2");
		cl2.appendChild(txtreferencia);

		// monto en la columna 3
		txtmonto = document.createTextNode(formatNumber.new(transacciones[i].monto));
		cl3 = document.createElement("div");
		cl3.classList.add("columna3");
		cl3.appendChild(txtmonto);

		// status en la columna 4
		txtstatus = document.createTextNode(transacciones[i].status);
		cl4 = document.createElement("div");
		cl4.classList.add("columna4");
		if (transacciones[i].status=='Por confirmar') {
			cl4.classList.add("rojo");
		} else {
			cl4.classList.add("negro");
		}
		cl4.appendChild(txtstatus);

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

		// Agregar la fila a la tabla
		document.getElementById("transaccionespendientes").appendChild(fila);
	}
}







////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

// function cobro(formadecobro) {
// 	console.log(formadecobro);
// 	switch (formadecobro) {
// 		case 'manual':
// 			alert('manual');
// 			break;
// 	}
// }

//
// function exito_validaciones() {
// 	monedayformadepago = moneda+'-'+formadepago
// 	switch (monedayformadepago) {
// 		case 'bs-online':
// 			propiedades="top=20%, left=50%, width=450, height=635, menubar=0, resizable=0, status=0, titlebar=0, toolbar=0";
// 			window.open("../php/formapagoenlinea.php?monto="+monto+"&ruta=giftcard&urlback="+sessionStorage.getItem("url_back")+"&hash="+respuesta.hash,"_blank",propiedades);
// 			// De ahi llamar a registro giftcard
// 			// Generar la tarjeta
// 			break;
// 		case 'bs-reporte':
// 			document.getElementById("monedas").style.display = 'none';
// 			document.getElementById("formulario").style.display = 'block';
// 			// De ahi llamar a registro giftcard
// 			// Generar la tarjeta
// 			break;
// 		case 'dolares-online':
// 			alert('entro en dolares online')
// 			// De ahi llamar a registro giftcard
// 			// Generar la tarjeta
// 			break;
// 		case 'dolares-reporte':
// 			alert('entro en dolares reporte')
// 			// De ahi llamar a registro giftcard
// 			// Generar la tarjeta
// 			break;
// 		}
// }
//
// function fallo_validaciones() { console.log('fallo'); }
//
// function botondepago(forma) {
// 	if (validaciones()) {
// 		formadepago = forma;
// 		abremodal2();
// 	}
// }
//
// function abremodal2() {
// 	document.getElementById("datospago").style.display = 'block';
//
// 	document.getElementById("btnescanearqr").style.display = 'inline-block';
// 	document.getElementById("btnpresentarqr").style.display = 'none';
// 	document.getElementById("btnpagmanual").style.display = 'none';
// }
//
// function formapago(frmpg) {
// 	tipo = frmpg;
// 	document.getElementById("origen").value = '';
// 	document.getElementById("referencia").value = '';
// 	switch (frmpg) {
// 		case 'efectivo':
// 			document.getElementById("detalle_pago").style.display = 'none';
// 			break;
// 		case 'tarjeta':
// 			document.getElementById("banco-punto").innerHTML = "Punto de venta";
// 			document.getElementById("detalle_pago").style.display = 'block';
// 			break;
// 		case 'transferencia':
// 			document.getElementById("banco-punto").innerHTML = "Banco de origen";
// 			document.getElementById("detalle_pago").style.display = 'block';
// 			break;
// 	}
// }
