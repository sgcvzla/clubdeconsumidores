var cards, tipomoneda = 'bs';
var email;
var idproveedor = sessionStorage.getItem("idproveedor");

if (email==undefined) { email = 'soluciones2000@gmail.com'; }
if (idproveedor==undefined) { idproveedor = 1; }

var pagobs = function() { pagoenlinea('bs'); }
var pagodolar = function() { pagoenlinea('dolar'); }
var reportebs = function () { reporte('bs'); }
var reportedolar = function () { reporte('dolar'); }
/*
navigator.serviceWorker.register("./sw.js");

let promptEvent = null;
window.addEventListener("beforeinstallprompt",(e)=>{
	console.log("lista para instalar");
	promptEvent = e;
	document.getElementById("instalar").classList.add("Active");
});

document.getElementById("instalar").addEventListener("click", (e)=>{
	promptEvent = e;
});
*/

function inicio() {
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			console.log(this.responseText);
			respuesta = JSON.parse(this.responseText);
			document.getElementById("titulosocio").innerHTML = respuesta.nombresocio;

			cards = respuesta.cards;
			for (var i = 0; i < cards.length; i++) {
				// Parte superior
				// Logo
				lgc = document.createElement("img");
				lgc.classList.add("left");
				lgc.src = "../img/"+cards[i].logoproveedor;
				lgc.width = 60;
				lgc.height = 60;
				// Titulo
				txtgc = document.createTextNode("Tarjeta de regalo");
				tgc = document.createElement("h2");
				tgc.classList.add("title");
				tgc.appendChild(txtgc);
				// Monto
				txtm = document.createTextNode(formatNumber.new(cards[i].saldo)+" "+cards[i].simbolomoneda);
				mgc = document.createElement("h3");
				mgc.id = cards[i].card+"-monto";
				mgc.classList.add("price");
				mgc.appendChild(txtm);
				// Parte alta de la tarjeta
				tp = document.createElement("div");
				tp.classList.add("top");
				tp.style.background = cards[i].color;
				tp.appendChild(lgc);
				tp.appendChild(tgc);
				tp.appendChild(mgc);
				// Parte inferior
				// simbolo
				sgc = document.createElement("img");
				sgc.id = cards[i].card+"-qr";
				sgc.style.margin = "-5.5px 5.5px";
				sgc.src = "../img/"+cards[i].dibujomoneda;
				sgc.width = 50;
				sgc.height = 50;
				// seccion del qr
				msgc = document.createElement("div");
				msgc.classList.add("left");
				msgc.appendChild(sgc);
				// Status
				txtst = document.createTextNode(cards[i].status);
				stgc = document.createElement("h3");
				stgc.id = cards[i].card+"-status";
				stgc.classList.add("status");
				if (cards[i].status!="Lista para usar") {
					stgc.style.color = "red";
				}
				stgc.appendChild(txtst);
				// seccion del status
				dstgc = document.createElement("div");
				dstgc.classList.add("right");
				dstgc.appendChild(stgc);
				// Parte baja de la tarjeta
				bt = document.createElement("div");
				bt.classList.add("bottom");
				bt.appendChild(msgc);
				bt.appendChild(dstgc);
				// Cuadro tarjeta
				dcard = document.createElement("div");
				dcard.id = cards[i].card;
				dcard.classList.add("block");
				dcard.classList.add("front");
				dcard.title = "Haga click para seleccionar";
					console.log(cards[i]);
				if (cards[i].status=="Lista para usar") {
					dcard.addEventListener('click', function(){ abremodal(this.id) });
				}
				dcard.appendChild(tp);
				dcard.appendChild(bt);
				// Agregar al catálogo
				document.getElementById("cards").appendChild(dcard);
			}
		}
	};
	xmlhttp.open("GET", "../php/buscagiftcards.php?email="+email, true);
	xmlhttp.send();
}
