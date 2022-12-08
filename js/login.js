//Login
let validarCURP = document.querySelector("#validarCurp");
let btnReconocimiento = document.querySelector("#btnReconocimiento");
let userImage = new Image()
let seccionReconocimiento = document.querySelector("#reconocimientoContainer")
let permisoContainer = document.querySelector("#permisoContainer")
let seccionForm = document.querySelector("#contactForm")
let video = document.querySelector("#video")
let videoContainer = document.querySelector("#videoContainer")
let userData

validarCURP.addEventListener('click', function(){
    console.log("1")
    let curp = $("#curpLoginInput").val().trim();

    if(curp){

        if(curp.length!=18){
            swal.fire({
                title: 'CURP inválido',
                icon: 'error',
                text: 'Ingrese un CURP válido de 18 caracteres'
            }).then(() => {
            });
        }
        const formData = new FormData();
        formData.append("Curp", curp);
        
        Promise.resolve($.ajax({
            type: 'POST',
            url: URLAPI + "usuarios/getUserByCURP",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response) {},
            error: function(err) {}
        }).then((response) => {
            console.log(response)
            if (response.success) {
                userData = response.data[0]
                userImage.src = response.data[0].Foto
                initSeccionReconocimiento();
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
    } else{
        swal.fire({
            title: 'Ingrese su CURP',
            icon: 'error'
        }).then(() => {

        });
    }
    

});


function initSeccionReconocimiento(){
    seccionForm.classList.add("d-none");
    permisoContainer.classList.remove("d-none");
    video.classList.remove("d-none");
}

btnReconocimiento.addEventListener('click', async function() {
    Promise.all([
        faceapi.loadTinyFaceDetectorModel('lib/modelsFaceApi'),
        faceapi.loadFaceLandmarkModel('lib/modelsFaceApi'),
        faceapi.loadFaceRecognitionModel('lib/modelsFaceApi')
    ]).then(startVideo)
    permisoContainer.classList.add("d-none");
    reconocimientoContainer.classList.remove("d-none");
});


function startVideo(){
    navigator.getUserMedia = (
        navigator.getUserMedia ||
        navigator.webkitGetUserMedia ||
        navigator.mozGetUserMedia ||
        navigator.msGetUserMedia);
    navigator.getUserMedia(
        { video:{} },
        stream => video.srcObject = stream,
        err => console.log(err)
    )
}

video.addEventListener('play', () =>{
    const canvas = faceapi.createCanvasFromMedia(video);
    document.getElementById("videoContainer").append(canvas)
    const displaySize = { width: video.width, height: video.height };
    faceapi.matchDimensions(canvas, displaySize);

    interval = setInterval(async () =>{
        const detectionsPhoto = await faceapi.detectSingleFace(userImage, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();
        const FaceDescriptors = new faceapi.LabeledFaceDescriptors(userData.Nombre, [detectionsPhoto.descriptor])
        const Matcher = new faceapi.FaceMatcher(FaceDescriptors, 0.40)    
        const detections = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();
        
        if(detections){
            const resizedDetections = faceapi.resizeResults(detections, displaySize)
            const ultMatch = Matcher.findBestMatch(detections.descriptor)
            console.log(ultMatch)
            canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height)
            faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);

            if(ultMatch._label!='unknown'){
                clearInterval(interval)
                loginExitoso()
            }
        }
    }, 100);
})

function loginExitoso(){
    swal.fire({
        title: 'Inicio de sesión exitoso',
        icon: 'success',
        text:  'Bienvenido, '+userData.Nombre
    }).then(() => {
        // Redireccionamiento a votación
        window.location.href = "menuvotacion.html";
    });
    
}