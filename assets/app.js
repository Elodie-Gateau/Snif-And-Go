import "./bootstrap.js";
import "./styles/app.css";
import "./js/trail-search.js";
import { initCityAutocomplete } from "./js/cities.js";

function init() {
    // ne bind que si le formulaire est présent
    if (document.getElementById("trail_startCode")) {
        initCityAutocomplete();
        console.log("[cities] init ok");
    }
}

// 1) au premier chargement + à chaque rendu Turbo (y compris depuis le cache)
document.addEventListener("DOMContentLoaded", init);
document.addEventListener("turbo:render", init);
document.addEventListener("turbo:load", init);

// 2) avant que Turbo mette la page au cache, on “nettoie” les champs pour
//    supprimer les écouteurs et data-* afin que le prochain init rebinde proprement
document.addEventListener("turbo:before-cache", () => {
    ["trail_startCode", "trail_endCode"].forEach((id) => {
        const el = document.getElementById(id);
        if (el) {
            el.replaceWith(el.cloneNode(true)); // enlève listeners + data-bound
        }
    });
});
