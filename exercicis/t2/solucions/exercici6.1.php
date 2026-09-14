<?php
    $paraulaCercada = $_GET['paraula'] ?? '';

    $paginaActual = (int) ($_GET['pagina'] ?? 1);
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 6.1 - Cercador de leads (GET)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Cercador de leads</h2>

        <form method="GET" action="" class="flex gap-2 mb-4">
            <input type="text" name="paraula" placeholder="Cerca un lead..."
                   class="flex-1 border border-gray-300 rounded px-3 py-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                Cercar
            </button>
        </form>

        <!-- VISTA: no cal tocar res d'ací en avall -->
        <?php if (!empty($paraulaCercada)): ?>
            <p class="text-gray-700">
                Has cercat: <strong><?= htmlspecialchars($paraulaCercada) ?></strong>
                (pàgina <?= $paginaActual ?>)
            </p>
        <?php endif; ?>

    </div>

</body>
</html>
