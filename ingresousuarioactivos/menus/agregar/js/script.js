document.getElementById('uploadForm').addEventListener('submit', function(event) {
    event.preventDefault();
    let formData = new FormData(this);
    let statusMessage = document.getElementById('statusMessage');
    statusMessage.innerHTML = '⏳ Cargando...';
    statusMessage.style.color = 'orange';

    fetch('back/subir_imagen.php', {
        method: 'POST',
        body: formData
    }).then(response => response.text()).then(result => {
        if (result.includes('exitosamente')) {
            statusMessage.innerHTML = '✔️ Cargado exitosamente';
            statusMessage.style.color = 'green';
        } else {
            statusMessage.innerHTML = '❌ Error al cargar';
            statusMessage.style.color = 'red';
        }
    });
});
