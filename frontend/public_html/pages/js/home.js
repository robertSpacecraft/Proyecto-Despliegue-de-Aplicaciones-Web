import apiUrl from '../../config/urls.js';

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
    tag.style.display = (tag.style.display === "none") ? "block" : "none";
}

// ... Resto de funciones (addNewFilm) usando la misma lógica ...

document.addEventListener("DOMContentLoaded", loadFilms);

// Exponer a window para los botones onclick del HTML
window.loadFilms = loadFilms;
window.showHideAddForm = showHideAddForm;
window.addNewFilm = addNewFilm;