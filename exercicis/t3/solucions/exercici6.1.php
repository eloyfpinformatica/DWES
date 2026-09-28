<?php
    $errors = [];
    $enviat = $_SERVER['REQUEST_METHOD'] === 'POST';

    if ($enviat) {
        // Dades rebudes (ja proporcionades, no cal que les toques)
        $usuari      = trim($_POST['usuari'] ?? '');
        $contrasenya = $_POST['contrasenya'] ?? '';
        $confirmacio = $_POST['confirmacio'] ?? '';

        // TODO 1: Valida les dades i acumula els errors en $errors:
        // - Si $usuari està buit: 'El nom d\'usuari és obligatori.'
        // - Si $contrasenya i $confirmacio no coincidixen: 'Les contrasenyes no coincidixen.'
        // - Si coincidixen però tenen menys de 8 caràcters (strlen): 'La contrasenya ha de tindre almenys 8 caràcters.'
        if ($usuari === '') {
            $errors[] = 'El nom d\'usuari és obligatori.';
        }

        if ($contrasenya !== $confirmacio) {
            $errors[] = 'Les contrasenyes no coincidixen.';
        } elseif (strlen($contrasenya) < 8) {
            $errors[] = 'La contrasenya ha de tindre almenys 8 caràcters.';
        }

        if (empty($errors)) {
            // TODO 2: Genera el hash de $contrasenya amb password_hash() i PASSWORD_DEFAULT
            // i guarda'l en $hash. (En un cas real, este hash és el que es guardaria a la base
            // de dades: mai la contrasenya en text pla)
            $hash = password_hash($contrasenya, PASSWORD_DEFAULT);

            // TODO 3: Genera un segon hash de la mateixa contrasenya i guarda'l en $hash2.
            // Després declara:
            // - $hashesIguals: si $hash i $hash2 són idèntics (===)
            // - $verificacioCorrecta: el resultat de password_verify() amb $contrasenya i $hash
            // - $verificacioIncorrecta: el resultat de password_verify() amb $contrasenya . 'x' i $hash
            $hash2 = password_hash($contrasenya, PASSWORD_DEFAULT);

            $hashesIguals          = $hash === $hash2;                                 // false: cada hash porta un salt diferent
            $verificacioCorrecta   = password_verify($contrasenya, $hash);             // true
            $verificacioIncorrecta = password_verify($contrasenya . 'x', $hash);       // false
        }
    }
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 6.1 - Registre d'usuari</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <!-- VISTA: no cal tocar res d'ací en avall -->
    <div class="max-w-xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold text-gray-800">Registre d'usuari · TechLeads</h1>

        <?php if ($enviat && !empty($errors)): ?>
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="" novalidate class="bg-white p-6 rounded-lg shadow-md space-y-4">
            <div>
                <label for="usuari" class="block text-sm font-semibold text-gray-700 mb-1">Usuari</label>
                <input type="text" id="usuari" name="usuari" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div>
                <label for="contrasenya" class="block text-sm font-semibold text-gray-700 mb-1">Contrasenya</label>
                <input type="password" id="contrasenya" name="contrasenya" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div>
                <label for="confirmacio" class="block text-sm font-semibold text-gray-700 mb-1">Repetix la contrasenya</label>
                <input type="password" id="confirmacio" name="confirmacio" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                Registrar-se
            </button>
        </form>

        <?php if ($enviat && empty($errors)): ?>
            <div class="bg-white p-6 rounded-lg shadow-md space-y-3">
                <h2 class="text-lg font-semibold text-green-700">Usuari «<?= htmlspecialchars($usuari) ?>» registrat</h2>

                <div>
                    <p class="text-sm font-semibold text-gray-700">Hash que es guardaria a la base de dades:</p>
                    <p class="font-mono text-sm break-all bg-gray-100 rounded p-2"><?= htmlspecialchars($hash) ?></p>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-700">Un segon hash de la mateixa contrasenya:</p>
                    <p class="font-mono text-sm break-all bg-gray-100 rounded p-2"><?= htmlspecialchars($hash2) ?></p>
                </div>

                <ul class="text-gray-700 space-y-1">
                    <li>Els dos hashes són iguals? <span class="font-semibold"><?= $hashesIguals ? 'Sí' : 'No' ?></span></li>
                    <li>Verificació amb la contrasenya correcta: <span class="font-semibold"><?= $verificacioCorrecta ? 'Sí' : 'No' ?></span></li>
                    <li>Verificació amb una contrasenya errònia: <span class="font-semibold"><?= $verificacioIncorrecta ? 'Sí' : 'No' ?></span></li>
                </ul>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>
