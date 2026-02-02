import apiUrl from '../../config/urls.js';

// Usamos funciones normales para asegurar que el hoisting o la asignación no fallen
export function loadFilms() {
    let tableBody = document.getElementById("tbody-container");
    if (!tableBody) return;
    tableBody.innerHTML = "";

    fetch(apiUrl + "/films/get_films.php")
        .then(response => response.json())
        .then(data => {
            if (data.status === "OK") {
                data.data.forEach(film => {
                    let row = `<tr>
                        <td>${film.id}</td>
                        <td>${film.name}</td>
                        <td>${film.director}</td>
                        <td>${film.classification}</td>
                        <td><a href="pages/detail.html?id=${film.id}" class="btn btn-primary btn-sm">Ver detalle</a></td>
                    </tr>`;
                    tableBody.innerHTML += row;
                });
            }
        });
}

export function showHideAddForm() {
    const tag = document.getElementById("new-form");
    if(tag) tag.style.display = (tag.style.display === "none") ? "block" : "none";
}

export function addNewFilm() {
    let jsonData = {
        name: document.getElementById("name").value,
        director: document.getElementById("director").value,
        classification: document.getElementById("classification").value,
        img: document.getElementById("img").value,
        plot: document.getElementById("plot").value
    };

    fetch(apiUrl + "/films/add_film.php", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(jsonData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "OK") {
            loadFilms();
            showHideAddForm();
            document.getElementById("form-new-tag").reset();
            alert("Película añadida");
        }
    });
    return false;
}

// Event Listeners e inicialización
document.addEventListener("DOMContentLoaded", loadFilms);

// EXPOSICIÓN GLOBAL: Esto es lo que permite que el HTML vea las funciones
window.loadFilms = loadFilms;
window.showHideAddForm = showHideAddForm;
window.addNewFilm = addNewFilm;