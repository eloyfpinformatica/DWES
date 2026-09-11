<?php
    $tecnologies = ['PHP', 'MySQL', 'TailwindCSS', 'JavaScript'];

    $primera = $tecnologies[0];

    $ultima = $tecnologies[count($tecnologies) - 1];

    $tecnologies[] = 'Docker';

    $llistaTec = '';
    foreach ($tecnologies as $index => $tecnologia) {
        $llistaTec .= "<li>Llenguatge {$index}: {$tecnologia}</li>";
    }
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 4.1 - Tecnologies del stack</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <div class="max-w-2xl mx-auto">

        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <p class="text-gray-700">Primera tecnologia: <span class="font-semibold"><?= $primera ?></span></p>
            <p class="text-gray-700">Última tecnologia (abans d'afegir Docker): <span class="font-semibold"><?= $ultima ?></span></p>
            <p class="text-gray-700">Total de tecnologies (després d'afegir Docker): <span class="font-semibold"><?= count($tecnologies) ?></span></p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Stack complet</h3>
            <ul class="list-disc list-inside text-gray-700">
                <?= $llistaTec ?>
            </ul>
        </div>

    </div>

</body>
</html>
