const maxFileSize = 10 * 1024 * 1024; // 10 MB
const allowedFormats = ["image/jpeg", "image/png", "image/jpg"];
document
    .getElementById("cropper_modal")
    .addEventListener("hidden.bs.modal", function () {
        document.body.classList.remove("modal-open");
        $('body').css('overflow', '');

        // Eliminar manualmente cualquier backdrop restante
        const backdrops = document.querySelectorAll(".modal-backdrop");
        backdrops.forEach((backdrop) => backdrop.remove());
    });

let cropper;
let originalImage;

function initCropper(imageUrl) {
    const modal_image = document.getElementById("modal_image");
    modal_image.src = imageUrl;

    if (cropper) {
        cropper.destroy();
    }

    cropper = new Cropper(modal_image, {
        aspectRatio: 1,
    });

    const cropper_modal = new bootstrap.Modal(
        document.getElementById("cropper_modal")
    );
    cropper_modal.show();
}

window.openCropperModal = function() {
    document.getElementById("cropper_modal").classList.remove("hidden");
    document.body.classList.add("overflow-hidden");
}
window.closeCropperModal = function() {
    document.getElementById("cropper_modal").classList.add("hidden");
    document.body.classList.remove("overflow-hidden");
}

document.getElementById("input_image").addEventListener("change", function (e) {
    const file = e.target.files[0];
    const errorDiv = document.getElementById("file_error");

    console.log(file.type);
    if (file) {
        // Validar formato y tamaño
        if (!allowedFormats.includes(file.type)) {
            errorDiv.innerText = "Formato no permitido. Usa .jpg, .jpeg o .png";
            errorDiv.style.display = "block";
            e.target.value = ""; // Limpia el input
            return;
        }

        if (file.size > maxFileSize) {
            errorDiv.innerText = "El tamaño del archivo supera los 10MB.";
            errorDiv.style.display = "block";
            e.target.value = ""; // Limpia el input
            return;
        }

        errorDiv.style.display = "none"; // Si pasa la validación, oculta el error

        const reader = new FileReader();
        reader.onload = function (event) {
            original_image = event.target.result;
            initCropper(event.target.result);
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById("cropButton").addEventListener("click", function () {
    if (!cropper) {
        alert("Selecciona una imagen para continuar.");
        return;
    }

    const croppedCanvas = cropper.getCroppedCanvas({
        width: 400,
        height: 400,
    });

    const cropped_image = croppedCanvas.toDataURL("image/png", 1.0);
    document.getElementById("cropped_image").value = cropped_image;
    document.getElementById("preview_image").src = cropped_image;
    document.getElementById("preview_image").style.display = "block";
    document.getElementById("cropper_button").innerHTML = "Cambiar Imagen";
    document.getElementById("cropped_image").dispatchEvent(new Event("input"));

    closeCropperModal();

    original_image = null;
});
