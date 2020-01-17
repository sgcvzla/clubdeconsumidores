// Variables públicas, constantes y función de transformación de números.
let ruta = "img/";
let opcfiltro = 'Todas';
let datos;

function cargadatos() {
	fparametros();
	let xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			if (this.responseText == 'No') {
				lista = '<h2 class="titulo">No hay comercios que mostrar.</h2>';
				document.getElementById("comercios").innerHTML = lista;
			} else {
				datos = JSON.parse(this.responseText);
				if (datos.filtros != undefined) {
					for (let i = 0; i < datos.filtros.length; i++) {
						txtopc = document.createTextNode(datos.filtros[i]);
						opc = document.createElement('option');
						opc.appendChild(txtopc);
						opc.value = datos.filtros[i];
						document.getElementById('opciones_filtros').appendChild(opc);
					}
				}
				// document.getElementById("comercios").innerHTML = 'Cargando comercios...';
				if (datos.registros != undefined) {
					for (let i = 0; i < datos.registros.length; i++) {
						dibujaropcion(datos.registros[i]);
					}
				}
			}
		}
	}

	xmlhttp.open("GET", "php/buscacomercios.php", true);
	xmlhttp.send();
}


function filtrar(filtro) {
	// Asignar el valor a la variable
	let opcfiltro = filtro.value;

	// Eliminar todos los valores
	largo = comercios.getElementsByTagName('a').length;
	for (let i = 0; i < largo; i++) {
		comercios.removeChild(comercios.getElementsByTagName('a')[0]);
	}

	// Dibujar las opciones filtradas
	for (let i = 0; i < datos.registros.length; i++) {
		if (opcfiltro == "Todas" || datos.registros[i].categoria == opcfiltro) {
			dibujaropcion(datos.registros[i]);
		}
	}
}


function comercio(valor) {
	sessionStorage.setItem("id_proveedor", valor);
	// window.location.replace("menu.html?id_proveedor="+valor);
}


function dibujaropcion(registro) {
	// Logo del comercio
	imgprov = document.createElement("img");
	imgprov.classList.add("img_comercio");
	imgprov.src = (registro.imagen == "") ? ruta+'sin_imagen.jpg' : ruta+registro.imagen;
	imgprov.title = registro.nombre;

	//div para la imagen
	divimpr = document.createElement("div");
	divimpr.classList.add("imagen");
	divimpr.align="center";
	divimpr.appendChild(imgprov);

	// Texto del botón
	txtprov = document.createTextNode(registro.nombre);
	spnprov = document.createElement("span");
	spnprov.style.marginBottom = '1em';
	spnprov.appendChild(txtprov);

	//div que agrupa imagen y texto
	divprov = document.createElement("div");
	divprov.align="center";
	divprov.classList.add("item");
	divprov.appendChild(divimpr);
	divprov.appendChild(spnprov);

	// Llamada del menú
	lnkprov = document.createElement("a");
	lnkprov.id = registro.id;
	lnkprov.classList.add(registro.categoria);
	lnkprov.href = 'menu.html?id_proveedor='+registro.id;
	lnkprov.addEventListener('click', function(){ comercio(this.id) });
	lnkprov.appendChild(divprov);

	// Agregar al menú
	comercios.appendChild(lnkprov);
}
