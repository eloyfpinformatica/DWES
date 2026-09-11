<?php
    require_once __DIR__ . '/helpers.php';

    $llistatLeads = [
        ['id' => 1, 'nom' => 'Joan Garcia', 'estat' => 'nou'],
        ['id' => 2, 'nom' => 'Laura Sanchis', 'estat' => 'convertit'],
        ['id' => 3, 'nom' => 'Marc Climent', 'estat' => 'nou'],
        ['id' => 4, 'nom' => 'Aina Martínez', 'estat' => 'descartat'],
    ];

    $totalNous = comptarLeadsNous($llistatLeads);

    $hasNotificacions = $totalNous > 0;
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 5.3 - Inclusió i separació de vistes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">

        <!-- VISTA: no cal tocar res d'ací en avall -->
        <?php if ($hasNotificacions): ?>
            <div class="bg-blue-100 text-blue-800 p-4 rounded mb-4">
                Tens <?= $totalNous ?> lead(s) nou(s) pendent(s) d'assignar.
            </div>
            <ul class="list-disc list-inside text-gray-700">
                <?php foreach ($llistatLeads as $lead): ?>
                    <?php if ($lead['estat'] === 'nou'): ?>
                        <li><?= htmlspecialchars($lead['nom']) ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-600">No tens notificacions pendents.</p>
        <?php endif; ?>

    </div>

</body>
</html>
