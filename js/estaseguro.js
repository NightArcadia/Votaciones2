let AceptarVotacion = document.querySelector("#AceptarVotacion");

AceptarVotacion.addEventListener('click', function() {
    Promise.resolve($.ajax({
        type: 'POST',
        url: URLAPI + "voto/voto"+sessionStorage.getItem("Partido"),
        processData: false,
        contentType: false,
        dataType: "json",
        success: function(response) {},
        error: function(err) {}
    }).then((response) => {
        console.log(response)
        if (response.success) {
            sessionStorage.removeItem("Partido");
            sessionStorage.removeItem("User");
            window.location.href = "gracias.html";
        } else {
            swal.fire({
                title: 'Ha ocurrido un error',
                icon: 'error',
                text: response.message
            });
        }
    }).catch((err) => {
        console.error(err);
    }));

    
});