import apiUrl from '../../config/urls.js';

function loadFilm() {
    let url = new URL(window.location.href);
    let id = url.searchParams.get("id");

    if (!id) {
        console.error("No se ha proporcionado un ID de película");
        return;
    }

    fetch(apiUrl + "/films/get_film.php?id=" + id)
        .then(response => {
            if (!response.ok) throw new Error("Error en la respuesta de la API");
            return response.json();
        })
        .then(data => {
            if (data.status === "OK") {
                const film = data.data;
                // Mapeo de datos a los elementos del HTML
                document.getElementById("name").innerHTML = film.name;
                document.getElementById("img").src = film.img;
                document.getElementById("director").innerHTML = film.director;
                document.getElementById("classification").innerHTML = film.classification;
                document.getElementById("plot").innerHTML = film.plot;
            } else {
                alert("No se pudo encontrar la película.");
            }
        })
        .catch(error => {
            console.error("Error al cargar la película:", error);
        });
}

// Se ejecuta al cargar el DOM
document.addEventListener("DOMContentLoaded", loadFilm);

// Exponemos la función por si fuera necesaria desde fuera del módulo
window.loadFilm = loadFilm;