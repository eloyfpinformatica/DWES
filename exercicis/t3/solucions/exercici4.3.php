<?php
    // TODO 1: Defineix la funció e(string $valor): string que retorne
    // htmlspecialchars($valor, ENT_QUOTES, 'UTF-8')
    function e(string $valor): string {
        return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
    }

    // Comentaris de mostra (ja proporcionats). Fixa't en el segon i el tercer!
    $comentaris = [
        ['autor' => 'Aina',                 'text' => 'Molt interessada en el pressupost de la web.'],
        ['autor' => 'Visitant',             'text' => '<script>alert("XSS: este codi s\'ha executat!")</script>'],
        ['autor' => 'Marc <b>(client)</b>', 'text' => 'Ens agrada l\'enfocament "cloud" & la proposta.'],
    ];

    $cerca = $_GET['q'] ?? '';
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 4.3 - XSS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <!-- VISTA -->
    <div class="max-w-xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold text-gray-800">Notes sobre el lead · TechLeads</h1>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Cerca en les notes</h2>

            
            <form method="GET" action="" class="flex gap-2 mb-3">
                <input type="text" name="q" value="<?= e($cerca) ?>" class="flex-1 border border-gray-300 rounded px-3 py-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">Cerca</button>
            </form>

            <?php if ($cerca !== ''): ?>
                <p class="text-gray-700 mb-1">Resultats per a: <span class="font-semibold text-purple-700"><?= e($cerca) ?></span></p>

                
                <a href="?q=<?= urlencode($cerca) ?>" class="text-blue-600 hover:underline">Repetir la cerca</a>
            <?php endif; ?>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">Comentaris</h2>

            
            <?php foreach ($comentaris as $comentari): ?>
                <div class="border-b py-3">
                    <p class="font-semibold text-gray-800"><?= e($comentari['autor']) ?></p>
                    <p class="text-gray-600"><?= e($comentari['text']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

</body>
</html>
