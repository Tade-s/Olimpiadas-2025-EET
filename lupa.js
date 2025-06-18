/*
Este archivo contiene una función de búsqueda, la cuál sirve por si el usuario desea
buscar un paquete en especial, que ya haya visto o desea encontrar.

@author Schimpf Tadeo
@version 1.2
@date 13/06/2025

*/

const paquetes = {
  // Bariloche
  bariloche: '1menubariloche.html',
  nieve: '1menubariloche.html',
  cerro: '1menubariloche.html',
  "cerro catedral": '1menubariloche.html',

  // Córdoba
  cordoba: '1menucordoba.html',
  córdoba: '1menucordoba.html',
  sierras: '1menucordoba.html',
  minas: '1menucordoba.html',

  // Mar del Plata
  "mar del plata": '1menumardelplata.html',
  mar: '1menumardelplata.html',
  playa: '1menumardelplata.html',
  costa: '1menumardelplata.html',

  // Salta y Jujuy (un solo paquete)
  salta: '1menusaltajujuy.html',
  jujuy: '1menusaltajujuy.html',
  noroeste: '1menusaltajujuy.html',
  cafayate: '1menusaltajujuy.html',
  tilcara: '1menusaltajujuy.html',

  // Patagonia
  calafate: '1menucalafate.html',
  glaciar: '1menucalafate.html',
  sur: '1menucalafate.html',

  //Catamarca
  catamarca: '1menucatamarca.html',
  cata: '1menucatamarca.html',

  //Catamarca
  misiones: '1menumisiones.html',
  mision: '1menumisiones.html',

  //Puerto Madryn
  "puerto madryn": '1menupuertom.html',
  puerto: '1menupuertom.html',
  madryn: '1menupuertom.html',

  //Tucumán
  tucuman: '1menutucuman.html',
  tucumán: '1menutucuman.html',

  //Ruta del Safari africano
  africa: '2menuafrica.html',
  áfrica: '2menuafrica.html',
  kenia: '2menuafrica.html',
  tanzania: '2menuafrica.html',
  sudafrica: '2menuafrica.html',
  sudáfrica: '2menuafrica.html',

  //Ruta Andina
  peru: '2menuandino.html',
  perú: '2menuandino.html',
  bolivia: '2menuandino.html',
  chile: '2menuandino.html',

  //Ruta clásica europea
  francia: '2menuclasicaeuropea.html',
  italia: '2menuclasicaeuropea.html',
  suiza: '2menuclasicaeuropea.html',
  alemania: '2menuclasicaeuropea.html',

  //Ruta Caribeña y centroamericana
  mexico: '2menucaribe.html',
  méxico: '2menucaribe.html',
  belice: '2menucaribe.html',
  guatemala: '2menucaribe.html',
  cuba: '2menucaribe.html',

  //Ruta Escandinava
  noruega: '2menuescandinavia.html',
  suecia: '2menuescandinavia.html',
  finlandia: '2menuescandinavia.html',
  dinamarca: '2menuescandinavia.html',


  japon: '2menutradicional.html',
  japón: '2menutradicional.html',

  //Ruta Mil y una Noches
  marruecos: '2menurutamil.html',
  egipto: '2menurutamil.html',
  "emiratos arabes unidos": '2menurutamil.html',
  "emiratos árabes unidos": '2menurutamil.html',

  //Estados unidos 
  "estados unidos": '2menueeuu.html',
  usa: '2menueeuu.html',
  eeuu: '2menueeuu.html',

  autos: "Veiculos.html",
  transporte: "veiculos.html",
  camioneta: "veiculos.html",
  trafi:"veiculos.html",
  mercedes: "veiculos.html",
  toyota: "veiculos.html",
  ford: "veiculos.html",
  kangoo:"veiculos.html",
  volkswagen: "veiculos.html",
  chevrolet: "veiculos.html",
  corolla:"veiculos.html",
  hyunday: "veiculos.html",
  4:"veiculos.html",
  5: "veiculos.html",
  6:"veiculos.html",
  7:"veiculos.html",
  9:"veiculos.html",
  15:"veiculos.html",
  18:"veiculos.html",
  19:"veiculos.html",

  ritz:"3menuritz.html",
  carlton:"3menuritz.html",

  mariana:"3menusans.html",
  sands:"3menusans.html",

  plaza:"3menuplaza.html",
  hotel:"3menuplaza.html",
  famosos:"3menuplaza.html",
  clasico:"3menuplaza.html",

  mirrorcube:"3menucube.html",
  futurista:"3menucube.html",

  aman:"3menuaman.html",
  kyoto:"3menuaman.html",

  terraza:"3menudana.html",
  dana:"3menudana.html",

  tragua:"3menutragua.httml",

  blue:"3menublue.html",
  ridge:"3menublue.html",

  montaña:"3menumontaña.html",
  

};

function normalizarTexto(texto) {
  return texto
    .toLowerCase()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .trim();
}

document.addEventListener('DOMContentLoaded', function () {
  const buscador = document.getElementById('buscador');
  const sugerenciasDiv = document.getElementById('sugerencias');

  buscador.addEventListener('input', () => {
    const texto = normalizarTexto(buscador.value);
    sugerenciasDiv.innerHTML = "";

    if (texto.length === 0) return;

    const coincidencias = Object.keys(paquetes).filter(dest =>
      normalizarTexto(dest).includes(texto) || texto.includes(normalizarTexto(dest))
    );

    coincidencias.forEach(dest => {
      const div = document.createElement('div');
      div.textContent = dest;
      div.classList.add('sugerencia');
      div.onclick = () => {
        window.location.href = paquetes[dest];
      };
      sugerenciasDiv.appendChild(div);
    });
  });

  buscador.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
      const texto = normalizarTexto(buscador.value);
      const destino = Object.keys(paquetes).find(dest =>
        normalizarTexto(dest) === texto || texto.includes(normalizarTexto(dest))
      );

      if (destino) {
        window.location.href = paquetes[destino];
      } else {
        alert("Destino no encontrado");
      }
    }
  });

  document.addEventListener('click', function (e) {
    if (!e.target.closest('.buscador-container')) {
      sugerenciasDiv.innerHTML = "";
    }
  });
});

