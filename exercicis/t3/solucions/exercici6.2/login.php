<?php
    // TODO 1: Inicia la sessió amb session_start()
    session_start();

    // TODO 2: Carrega 'usuaris_bd.php' amb require_once i una ruta construïda amb __DIR__
    // (este arxiu defineix l'array $usuaris)
    require_once __DIR__ . '/usuaris_bd.php';

    $errors = [];

    // TODO 3: Si l'usuari ja té la sessió iniciada ($_SESSION['usuari'] existix),
    // redirigeix a 'pagina_privada.php' amb exit
    if (isset($_SESSION['usuari'])) {
        header('Location: pagina_privada.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuari      = trim($_POST['usuari'] ?? '');
        $contrasenya = $_POST['contrasenya'] ?? '';

        // TODO 4: Comprova que $usuari existix a $usuaris I que password_verify() confirma la contrasenya
        // contra el 'hash' d'eixe usuari. Si és així:
        //   - Regenera l'ID de sessió amb session_regenerate_id(true)
        //   - Guarda en $_SESSION['usuari'] el nom i en $_SESSION['rol'] el seu rol
        //   - Redirigeix a 'pagina_privada.php' amb exit
        // Si no, afig a $errors el missatge genèric 'Usuari o contrasenya incorrectes.'
        if (isset($usuaris[$usuari]) && password_verify($contrasenya, $usuaris[$usuari]['hash'])) {
            // Regenerar l'ID de sessió en autenticar: evita la fixació de sessió
            session_regenerate_id(true);

            $_SESSION['usuari'] = $usuari;
            $_SESSION['rol']    = $usuaris[$usuari]['rol'];
            header('Location: pagina_privada.php');
            exit;
        } else {
            // Missatge genèric: no revela si l'usuari existix o no
            $errors[] = 'Usuari o contrasenya incorrectes.';
        }
    }
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 6.2 - Iniciar sessió</title>
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

            <div>
                <label for="usuari" class="block text-sm font-semibold text-gray-700 mb-1">Usuari</label>
                <input type="text" id="usuari" name="usuari" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div>
                <label for="contrasenya" class="block text-sm font-semibold text-gray-700 mb-1">Contrasenya</label>
                <input type="password" id="contrasenya" name="contrasenya" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                Entrar
            </button>
        </form>

    </div>

</body>
</html>
