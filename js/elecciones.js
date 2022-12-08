if(!sessionStorage.getItem("User")){
    window.location.href = "index.html";
}

let btnMORENA = document.querySelector("#btnMORENA");
let btnIND = document.querySelector("#btnIND");
let btnPRI = document.querySelector("#btnPRI");
let btnPAN = document.querySelector("#btnPAN");
let btnPRD = document.querySelector("#btnPRD");

btnMORENA.addEventListener('click', function() {
    sessionStorage.setItem("Partido", "MORENA");
    window.location.href = "estaseguro.html";
});

btnIND.addEventListener('click', function() {
    sessionStorage.setItem("Partido", "IND");
    window.location.href = "estaseguro.html";
});

btnPRI.addEventListener('click', function() {
    sessionStorage.setItem("Partido", "PRI");
    window.location.href = "estaseguro.html";
});

btnPAN.addEventListener('click', function() {
    sessionStorage.setItem("Partido", "PAN");
    window.location.href = "estaseguro.html";
});

btnPRD.addEventListener('click', function() {
    sessionStorage.setItem("Partido", "PRD");
    window.location.href = "estaseguro.html";
});

