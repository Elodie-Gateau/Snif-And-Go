const toggleButton = document.getElementById("toggle-advanced");
const advancedPanel = document.getElementById("trail-advanced");

if (toggleButton && advancedPanel) {
    toggleButton.addEventListener("click", () => {
        const hidden = advancedPanel.classList.toggle("hidden");
        const open = !hidden;
        console.log(open);
        if (open) {
            toggleButton.textContent = "Masquer les critères";
        } else {
            toggleButton.textContent = "Afficher plus de critères";
        }
    });
}
