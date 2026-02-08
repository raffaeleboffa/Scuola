document.addEventListener("DOMContentLoaded", () => {
    const categories = ["antipasti", "formaggi", "primi", "secondi", "contorni", "dessert", "frutta", "cafeAmari"];
    
    // Mostra la prima sezione
    document.getElementById("antipasti").style.display = "flex";
    
    // Ripristina i valori dal localStorage e aggiunge gli event listener
    categories.forEach(cat => {
        const select = document.getElementById(cat + "_select");
        select.value = localStorage.getItem(cat) || "none";
        select.addEventListener("change", function() {
            localStorage.setItem(cat, this.value);
        });
    });
});

function openSez(sezione) {
    var sections = document.getElementsByClassName("sez");
    
    for (let i = 0; i < sections.length; i++) {
        sections[i].style.display = "none";
    }

    document.getElementById(sezione).style.display = "flex";
}

document.getElementById("antipasti_select").addEventListener("change", function() {
    localStorage.setItem("antipasti", this.value);
});
document.getElementById("formaggi_select").addEventListener("change", function() {
    localStorage.setItem("formaggi", this.value);
});
document.getElementById("primi_select").addEventListener("change", function() {
    localStorage.setItem("primi", this.value);
});
document.getElementById("secondi_select").addEventListener("change", function() {
    localStorage.setItem("secondi", this.value);
});
document.getElementById("contorni_select").addEventListener("change", function() {
    localStorage.setItem("contorni", this.value);
});
document.getElementById("dessert_select").addEventListener("change", function() {
    localStorage.setItem("dessert", this.value);
});
document.getElementById("frutta_select").addEventListener("change", function() {
    localStorage.setItem("frutta", this.value);
});
document.getElementById("cafeAmari_select").addEventListener("change", function() {
    localStorage.setItem("cafeAmari", this.value);
});