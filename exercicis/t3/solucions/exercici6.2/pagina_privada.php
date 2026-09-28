<?php
    session_start();

    if (!isset($_SESSION['usuari'])) {
        header('Location: login.php');
        exit;
    }

    $usuari = $_SESSION['usuari'];
    $rol    = $_SESSION['rol'];
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 6.2 - Pàgina privada</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <div class="max-w-xl mx-auto space-y-6">

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold text-gray-800 mb-1">Benvingut/da, <?= htmlspecialchars($usuari) ?>!</h1>
            <p class="text-gray-600">Rol: <span class="font-semibold text-blue-700"><?= htmlspecialchars($rol) ?></span></p>
        </div>

        <?php if ($rol === 'administrador'): ?>
            <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-3 rounded">
                Panell d'administració: només el veuen els administradors.
            </div>
        <?php endif; ?>

        <a href="logout.php" class="inline-block bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded">
            Tancar sessió
        </a>

    </div>

</body>
</html>
