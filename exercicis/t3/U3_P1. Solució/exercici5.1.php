<?php
    // TODO 1: Si $_GET['tema'] val 'clar' o 'fosc' (comprova-ho amb in_array), crea la cookie 'tema'
    // amb setcookie(): valor = el tema rebut, expira en 30 dies, path '/' i httponly activat.
    // Després redirigeix a 'exercici5.1.php' amb header('Location: ...') i exit
    $temaSolicitat = $_GET['tema'] ?? '';
    if (in_array($temaSolicitat, ['clar', 'fosc'], true)) {
        setcookie(
            name: 'tema',
            value: $temaSolicitat,
            expires_or_options: time() + 3600 * 24 * 30, // 30 dies
            path: '/',
            httponly: true
        );
        // La cookie no estarà a $_COOKIE fins a la pròxima petició: redirigim per a veure el canvi
        header('Location: exercici5.1.php');
        exit;
    }

    // TODO 2: Si $_GET['tema'] val 'esborrar', elimina la cookie 'tema' (data d'expiració
    // en el passat i path '/') i redirigeix a 'exercici5.1.php' amb exit
    if (($_GET['tema'] ?? '') === 'esborrar') {
        setcookie('tema', '', time() - 3600, '/'); // data en el passat = eliminar
        header('Location: exercici5.1.php');
        exit;
    }

    // TODO 3: Llig la cookie 'tema' de $_COOKIE amb ?? (per defecte 'clar') i guarda-la en $tema
    $tema = $_COOKIE['tema'] ?? 'clar';

?>
<?php $fosc = $tema === 'fosc'; ?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 5.1 - Cookie de preferències</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="<?= $fosc ? 'bg-gray-900 text-gray-100' : 'bg-gray-100 text-gray-800' ?> min-h-screen p-8">

    <!-- VISTA: no cal tocar res d'ací en avall -->
    <div class="max-w-xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold">Preferències · TechLeads</h1>

        <div class="<?= $fosc ? 'bg-gray-800' : 'bg-white' ?> p-6 rounded-lg shadow-md space-y-4">
            <p>Tema actual: <span class="font-semibold"><?= $fosc ? 'fosc' : 'clar' ?></span></p>
            <p class="text-sm opacity-75">
                Valor de la cookie <code>tema</code>:
                <?= htmlspecialchars($_COOKIE['tema'] ?? '(no definida)') ?>
            </p>

            <div class="flex flex-wrap gap-3">
                <a href="?tema=clar" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">Mode clar</a>
                <a href="?tema=fosc" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold px-4 py-2 rounded">Mode fosc</a>
                <a href="?tema=esborrar" class="bg-gray-400 hover:bg-gray-500 text-white font-semibold px-4 py-2 rounded">Oblidar preferència</a>
            </div>
        </div>

    </div>

</body>
</html>
