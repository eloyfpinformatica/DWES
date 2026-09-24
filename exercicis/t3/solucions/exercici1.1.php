<?php
    // TODO 1: Informació de la petició amb $_SERVER
    $metode = $_SERVER['REQUEST_METHOD'];
    $host   = $_SERVER['HTTP_HOST'];
    $uri    = $_SERVER['REQUEST_URI'];

    // TODO 2: Paràmetre GET amb valor per defecte
    $lead = $_GET['lead'] ?? 'Cap lead seleccionat';

    // TODO 3: Nota enviada per POST (només si la petició és POST)
    $nota = '';
    if ($metode === 'POST') {
        $nota = trim($_POST['nota'] ?? '');
    }

    // TODO 4: Les supervariables són arrays: es poden comptar com qualsevol altre
    $resum = [
        'GET'    => count($_GET),
        'POST'   => count($_POST),
        'COOKIE' => count($_COOKIE),
        'FILES'  => count($_FILES),
    ];

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 1.1 - Panell de la petició</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <!-- VISTA: no cal tocar res d'ací en avall -->
    <div class="max-w-3xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold text-gray-800">Panell de la petició · TechLeads</h1>

        <!-- $_SERVER -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Informació de la petició <code class="text-sm text-blue-700">$_SERVER</code></h2>
            <ul class="text-gray-700 space-y-1">
                <li><span class="font-semibold">Mètode:</span> <?= htmlspecialchars($metode) ?></li>
                <li><span class="font-semibold">Host:</span> <?= htmlspecialchars($host) ?></li>
                <li><span class="font-semibold">Ruta sol·licitada:</span> <?= htmlspecialchars($uri) ?></li>
            </ul>
        </div>

        <!-- $_GET -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Selecciona un lead <code class="text-sm text-blue-700">$_GET</code></h2>
            <div class="flex gap-3 mb-4">
                <a href="?lead=Aina" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">Aina</a>
                <a href="?lead=Marc" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">Marc</a>
                <a href="exercici1.1.php" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded">Netejar</a>
            </div>
            <p class="text-gray-700">Lead seleccionat: <span class="font-semibold text-purple-700"><?= htmlspecialchars($lead) ?></span></p>
        </div>

        <!-- $_POST -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Afig una nota <code class="text-sm text-blue-700">$_POST</code></h2>
            <form method="POST" action="" class="flex gap-2 mb-4">
                <input type="text" name="nota" placeholder="Escriu una nota..."
                       class="flex-1 border border-gray-300 rounded px-3 py-2">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded">
                    Enviar
                </button>
            </form>
            <?php if ($metode === 'POST'): ?>
                <p class="text-gray-700">Nota rebuda: <span class="font-semibold text-green-700"><?= htmlspecialchars($nota) ?></span></p>
            <?php else: ?>
                <p class="text-gray-500">Encara no has enviat cap nota.</p>
            <?php endif; ?>
        </div>

        <!-- Resum -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Resum de supervariables</h2>
            <ul class="text-gray-700 space-y-1">
                <?php foreach ($resum as $nomSuperglobal => $total): ?>
                    <li><code class="text-blue-700">$_<?= $nomSuperglobal ?></code>: <?= $total ?> element(s)</li>
                <?php endforeach; ?>
            </ul>
        </div>

    </div>

</body>
</html>
