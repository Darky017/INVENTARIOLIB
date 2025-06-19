<?php
require_once 'header.php';
?>

<div class="container mt-5">
    <h4 class="mb-4">Generador de Contraseñas Seguras</h4>

    <div class="form-group">
        <label for="length">Longitud de la contraseña</label>
        <input type="number" id="length" class="form-control" value="12" min="4" max="50">
    </div>

    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="include_upper" checked>
        <label class="form-check-label" for="include_upper">Incluir mayúsculas</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="include_lower" checked>
        <label class="form-check-label" for="include_lower">Incluir minúsculas</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="include_numbers" checked>
        <label class="form-check-label" for="include_numbers">Incluir números</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="include_symbols" checked>
        <label class="form-check-label" for="include_symbols">Incluir símbolos</label>
    </div>

    <br>

    <button class="btn btn-primary" onclick="generatePassword()">
        <i class="fas fa-random"></i> Generar
    </button>

    <div class="form-group mt-3">
        <label>Contraseña generada</label>
        <div class="input-group">
            <input type="text" id="password_result" class="form-control" readonly>
            <button class="btn btn-outline-secondary" onclick="copyPassword()">Copiar</button>
        </div>
    </div>
</div>

<script>
function generatePassword() {
    const length = parseInt(document.getElementById('length').value);
    const upper = document.getElementById('include_upper').checked;
    const lower = document.getElementById('include_lower').checked;
    const numbers = document.getElementById('include_numbers').checked;
    const symbols = document.getElementById('include_symbols').checked;

    const upperChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    const lowerChars = "abcdefghijklmnopqrstuvwxyz";
    const numberChars = "0123456789";
    const symbolChars = "!@#$%*_+-=;:,./?";

    let allChars = "";
    if (upper) allChars += upperChars;
    if (lower) allChars += lowerChars;
    if (numbers) allChars += numberChars;
    if (symbols) allChars += symbolChars;

    if (!allChars) {
        alert("Debes seleccionar al menos una opción.");
        return;
    }

    let password = "";
    for (let i = 0; i < length; i++) {
        const randIndex = Math.floor(Math.random() * allChars.length);
        password += allChars[randIndex];
    }

    document.getElementById("password_result").value = password;
}

function copyPassword() {
    const passInput = document.getElementById("password_result");
    passInput.select();
    passInput.setSelectionRange(0, 99999);
    document.execCommand("copy");
    alert("Contraseña copiada al portapapeles.");
}
</script>

<?php require_once 'footer.php'; ?>
