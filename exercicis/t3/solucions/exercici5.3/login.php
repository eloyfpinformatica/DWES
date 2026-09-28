<?php
    // TODO 1: Inicia la sessió amb session_start()
    session_start();

    // Usuaris autoritzats i el seu rol (ja proporcionat).
    // En este exercici no hi ha contrasenya: l'autenticació real, amb password_hash(),
    // la farem al punt 6
    $usuaris = ['ana' => 'comercial', 'marc' => 'administrador'];
    $errors  = [];

    // TODO 2: Si l'usuari ja té la sessió iniciada ($_SESSION['usuari'] existix),
    // redirigeix a 'pagina_privada.php' amb exit
    if (isset($_SESSION['usuari'])) {
        header('Location: pagina_privada.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuari = trim($_POST['usuari'] ?? '');

        // TODO 3: Si $usuari és una clau de l'array $usuaris, guarda en $_SESSION['usuari'] el seu nom
        // i en $_SESSION['rol'] el rol que li corresponga, i redirigeix a 'pagina_privada.php' amb exit.
        // Si no, afig a $errors el missatge 'Accés denegat: usuari no reconegut.'
        if (array_key_exists($usuari, $usuaris)) {
            $_SESSION['usuari'] = $usuari;
            $_SESSION['rol']    = $usuaris[$usuari];
            header('Location: pagina_privada.php');
            exit;
        } else {
            $errors[] = 'Accés denegat: usuari no reconegut.';
        }
    }
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 5.3 - Iniciar sessió</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-8">

    <!-- VISTA: no cal tocar res d'ací en avall -->
    <div class="max-w-sm w-full space-y-4">

        <?php foreach ($errors as $error): ?>
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded"><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>

        <form method="POST" action="" class="bg-white p-8 rounded-lg shadow-md space-y-4">
            <h1 class="text-2xl font-bold text-gray-800">Iniciar sessió</h1>
            <p class="text-sm text-gray-500">Usuaris de prova: <code>ana</code> i <code>marc</code></p>

            <div>
                <label for="usuari" class="block text-sm font-semibold text-gray-700 mb-1">Usuari</label>
                <input type="text" id="usuari" name="usuari" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                Entrar
            </button>
        </form>

    </div>

</body>
</html>
