import apiUrl from '../../config/urls.js';

function loadFilms() {
    let tableBody = document.getElementById("tbody-container");
    if (!tableBody) return;
    tableBody.innerHTML = "";

    fetch(apiUrl + "/films/get_films.php", {
        method: 'GET'
    })
    .then((response) => {
        if (response.status == 500)
            alert("Se ha producido un error de servidor");
        else {
            response.json().then((data) => {
                if (data.status == "OK") {
                    loadDataInTable(data.data, tableBody);
                }
            });
        }
    });
}

function loadDataInTable(filmsJSON, tableBody) {
    if (filmsJSON.length <= 0) {
        document.getElementById("no-films-message").style.display = "block";
    } else {
        document.getElementById("no-films-message").style.display = "none";
        filmsJSON.forEach(film => loadRow(film, tableBody));
    }
}

function loadRow(film, tableBody) {
    let row = `<tr>
        <th scope="row">${film.id}</th>
        <td>${film.name}</td>
        <td>${film.director}</td>
        <td>${film.classification}</td>
        <td>
            <a href="pages/detail.html?id=${film.id}" role="button" class="btn btn-primary btn-sm">Ver detalle</a>
        </td>
    </tr>`;
    tableBody.innerHTML += row;
}

function showHideAddForm() {
    const tag = document.getElementById("new-form");
    tag.style.display = (tag.style.display === "none") ? "block" : "none";
}

function addNewFilm() {
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
    .then((response) => {
        response.json().then((data) => {
            if (data.status == "OK") {
                loadFilms();
                showHideAddForm();
                document.getElementById("form-new-tag").reset();
                alert("Película añadida");
            }
        });
    });
    return false;
}

document.addEventListener("DOMContentLoaded", loadFilms);

// Exponer funciones al objeto window para que funcionen los onclick del HTML
window.loadFilms = loadFilms;
window.showHideAddForm = showHideAddForm;
window.addNewFilm = addNewFilm;