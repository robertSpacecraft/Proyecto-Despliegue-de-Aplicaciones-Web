import apiUrl from '../../config/urls.js';

function loadFilm() {
    let url = new URL(window.location.href);
    let id = url.searchParams.get("id");

    fetch(apiUrl + "/films/get_film.php?id=" + id, {
        method: 'GET'
    })
    .then((response) => {
        response.json().then((data) => {
            if (data.status == "OK") {
                let film = data.data;
                document.getElementById("name").innerHTML = film.name;
                document.getElementById("director").innerHTML = film.director;
                document.getElementById("classification").innerHTML = film.classification;
                document.getElementById("img").src = film.img;
                document.getElementById("plot").innerHTML = film.plot;
            }
        });
    });
}

document.addEventListener("DOMContentLoaded", loadFilm);
window.loadFilm = loadFilm;