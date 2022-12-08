let btnRegistrar = document.querySelector("#registrar");
let camera_button = document.querySelector("#start-camera");
let try_again = document.querySelector("#try_again");
let video = document.querySelector("#video");
let click_button = document.querySelector("#click-photo");
let canvas = document.querySelector("#canvas");
let nameInput = document.querySelector("#name");
let curpInput = document.querySelector("#curp");
let contrasenaInput = document.querySelector("#contrasena");
let contrasenaVerifyInput = document.querySelector("#contrasenaVerify");
let image_data_url
let stream
let waitMessage = document.querySelector("#waitMessage");
nameInput.disabled = true
curpInput.disabled = true
contrasenaInput.disabled = true
contrasenaVerifyInput.disabled = true

camera_button.addEventListener('click', async function() {
    stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
	video.srcObject = stream;
    camera_button.classList.add('d-none');
    video.classList.remove('d-none');
    click_button.classList.remove('d-none');
    try_again.classList.add('d-none');
    nameInput.disabled = true
    curpInput.disabled = true
    contrasenaInput.disabled = true
    contrasenaVerifyInput.disabled = true
});
try_again.addEventListener('click', async function() {
    tryAgain()
});

click_button.addEventListener('click', async function() {
    waitMessage.classList.remove('d-none')
    Promise.all([
        faceapi.loadTinyFaceDetectorModel('lib/modelsFaceApi')
    ]).then(setCanva)
});

async function tryAgain(){
    waitMessage.classList.add('d-none')
    stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
    canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
    video.srcObject = stream;
    try_again.classList.add('d-none');
    canvas.classList.add('d-none');
    video.classList.remove('d-none');
    click_button.classList.remove('d-none');
    nameInput.disabled = true
    curpInput.disabled = true
    contrasenaInput.disabled = true
    contrasenaVerifyInput.disabled = true
    image_data_url = null
}

async function setCanva(){
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    image_data_url = canvas.toDataURL();
    // data url of the image
    console.log(image_data_url);
    video.pause();
    stream.getTracks().forEach(function(track) {
        track.stop();
    });
    
    const detectionsPhoto = await faceapi.detectSingleFace(canvas, new faceapi.TinyFaceDetectorOptions());
    console.log(detectionsPhoto)
    if(detectionsPhoto){
        if(detectionsPhoto._score>0.6){
            waitMessage.classList.add('d-none')
            video.classList.add('d-none');
            canvas.classList.remove('d-none');
            click_button.classList.add('d-none');
            try_again.classList.remove('d-none');
            nameInput.disabled = false
            curpInput.disabled = false
            contrasenaInput.disabled = false
            contrasenaVerifyInput.disabled = false
        }else{
            swal.fire({
                title: 'No se registró correctamente la cara',
                icon: 'error',
                text: 'Vuelva a intentarlo. Por favor descubra su cara, colóquese frente a la cámara y mire hacia ella.'
            }).then(() => {
                tryAgain()
            });   
        }
    }else{
        swal.fire({
            title: 'No se encontró ningún rostro',
            icon: 'error',
            text: 'Vuelva a intentarlo'
        }).then(() => {
            tryAgain()
        });   
    }
}

btnRegistrar.addEventListener('click', function(){
    console.log(image_data_url)
    let name = $("#name").val().trim();
    let curp = $("#curp").val().trim();
    let pass = $("#contrasena").val().trim();
    let passConfirm = $("#contrasenaVerify").val().trim();
    console.log(curp.length)
    
    if(image_data_url){
        if (name && curp && pass && passConfirm) {
            if(curp.length!=18){
                swal.fire({
                    title: 'CURP inválido',
                    icon: 'error',
                    text: 'Ingrese un CURP válido de 18 caracteres'
                }).then(() => {
                });
                return
            }
            if (pass === passConfirm) {
                if(pass.length<8){
                    swal.fire({
                        title: 'Contraseña inválida',
                        icon: 'error',
                        text: 'Ingrese una contraseña de al menos 8 caracteres'
                    }).then(() => {
                    });
                    return
                }
                const formData = new FormData();
                formData.append("Nombre", name);
                formData.append("Password", pass);
                formData.append("Curp", curp);
                formData.append("Foto", image_data_url);
                Promise.resolve($.ajax({
                    type: 'POST',
                    url: URLAPI + "usuarios/register",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function(response) {},
                    error: function(err) {}
                }).then((response) => {
                    console.log(response)
                    if (response.success) {
                        swal.fire({
                            title: 'Registro Exitoso',
                            icon: 'success'
                        }).then(() => {
                            window.location.href = URL;
                        });
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
            } else {
                swal.fire({
                    title: 'Ha ocurrido un error',
                    icon: 'error',
                    text: 'Las contraseñas no coinciden'
                });
            }
        } else {
            swal.fire({
                title: 'Faltan datos',
                icon: 'error',
                text: 'Rellene todos los campos'
            });
        }
    } else {
        swal.fire({
            title: 'Sin foto',
            icon: 'error',
            text: 'Por favor tómese una foto de frente'
        });
    }


});