let btnInicio = document.querySelector("#btnInicio");

btnInicio.addEventListener('click', function() {
    sessionStorage.setItem("User", "Claudio");
    window.location.href = "menuvotacion.html";
});