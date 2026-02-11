var active_hobbies = document.getElementById("active_hobbies");
var hobbies = document.getElementById("hobbies");

active_hobbies.addEventListener("change", function() {
    if (this.checked) {
        hobbies.removeAttribute("disabled");
        hobbies.setAttribute("required", "required");
    } else {
        hobbies.removeAttribute("required");
        hobbies.setAttribute("disabled", "disabled");
    }
});

var activer_sports = document.getElementById("active_sports");
var sports = document.getElementById("sports");

activer_sports.addEventListener("change", function() {
    if (this.checked) {
        sports.removeAttribute("disabled");
        sports.setAttribute("required", "required");
    } else {
        sports.removeAttribute("required");
        sports.setAttribute("disabled", "disabled");
    }
});