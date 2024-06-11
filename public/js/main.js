document.getElementById('electrodomesticoForm').addEventListener('submit', function(event) {
    let isValid = true;

    // Validar nombre
    const nombre = document.getElementById('nombre').value;
    const nombreError = document.getElementById('nombreError');
    if (!/^[a-zA-Z]+$/.test(nombre)) {
        nombreError.classList.remove('hidden');
        isValid = false;
    } else {
        nombreError.classList.add('hidden');
    }

    // Validar peso
    const peso = document.getElementById('peso').value;
    const pesoError = document.getElementById('pesoError');
    if (!/^\d+$/.test(peso)) {
        pesoError.classList.remove('hidden');
        isValid = false;
    } else {
        pesoError.classList.add('hidden');
    }

    if (!isValid) {
        event.preventDefault();
    }
});
