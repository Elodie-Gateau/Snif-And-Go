const inputModeSelectGpx = document.querySelector("#trail_inputMode_0");
const inputModeSelectManuel = document.querySelector("#trail_inputMode_1");
const gpxSection = document.querySelector("[data-section='gpx']");
const manualSection = document.querySelector("[data-section='manual']");

console.log("toggleSections init");
if (inputModeSelectGpx & inputModeSelectManuel) {
    inputModeSelectGpx.addEventListener("click", () => {
        gpxSection.classList.remove("hidden");
        manualSection.classList.add("hidden");
    });

    inputModeSelectManuel.addEventListener("click", () => {
        gpxSection.classList.add("hidden");
        manualSection.classList.remove("hidden");
    });
}
