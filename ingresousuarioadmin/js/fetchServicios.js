document.addEventListener('DOMContentLoaded', function () {
    fetch('fetch_servicios.php')
        .then(response => response.json())
        .then(data => {
            const serviciosContainer = document.getElementById('serviciosRealizados');
            serviciosContainer.style.overflowY = 'scroll';
            serviciosContainer.style.height = '300px'; // Altura para permitir desplazamiento

            data.forEach(service => {
                const serviceItem = document.createElement('div');
                serviceItem.className = 'service-item';
                serviceItem.innerHTML = `<span>#${service.id}</span> <p>${service.descripcion}</p>`;
                serviciosContainer.appendChild(serviceItem);
            });
        })
        .catch(error => console.error('Error al cargar los servicios:', error));
});