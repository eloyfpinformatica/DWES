<?php
    // TODO 1: Carrega Lead.php amb require_once i una ruta construïda amb __DIR__
    require_once __DIR__ . '/Lead.php';

    // TODO 2: Crea l'array $leads amb tres objectes: new Lead('Aina Soler'), new Lead('Marc Climent') i new Lead('Laura Sanchis')
    $leads = [
        new Lead('Aina Soler'),
        new Lead('Marc Climent'),
        new Lead('Laura Sanchis'),
    ];

    // TODO 3: Fes avançar l'estat del primer lead dues vegades i el del segon una vegada, amb avancarEstat().
    // El tercer lead ha de quedar com a nou
    $leads[0]->avancarEstat();
    $leads[0]->avancarEstat();
    $leads[1]->avancarEstat();

    // TODO 4: Declara $total amb el nombre de leads creats, cridant al mètode estàtic Lead::getTotalCreats()
    $total = Lead::getTotalCreats();

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 8.3 - Static, constants i readonly</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <!-- VISTA: no cal tocar res d'ací en avall -->
    <div class="max-w-2xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold text-gray-800">Seguiment de leads · TechLeads</h1>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <p class="text-sm text-gray-500 mb-4">
                Estats possibles:
                <span class="font-mono"><?= Lead::ESTAT_NOU ?> → <?= Lead::ESTAT_CONTACTAT ?> → <?= Lead::ESTAT_TANCAT ?></span>
            </p>

            <table class="w-full text-left">
                <thead>
                    <tr class="border-b text-gray-600">
                        <th class="py-2">Id</th>
                        <th class="py-2">Nom</th>
                        <th class="py-2">Estat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leads as $lead): ?>
                        <?php
                            $classesEstat = match ($lead->getEstat()) {
                                Lead::ESTAT_NOU       => 'bg-gray-100 text-gray-700',
                                Lead::ESTAT_CONTACTAT => 'bg-yellow-100 text-yellow-800',
                                Lead::ESTAT_TANCAT    => 'bg-green-100 text-green-800',
                            };
                        ?>
                        <tr class="border-b text-gray-700">
                            <td class="py-2">#<?= $lead->id ?></td>
                            <td class="py-2"><?= htmlspecialchars($lead->nom) ?></td>
                            <td class="py-2">
                                <span class="px-2 py-1 rounded text-sm font-semibold <?= $classesEstat ?>">
                                    <?= htmlspecialchars($lead->getEstat()) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p class="mt-4 text-gray-700">Leads creats: <span class="font-bold text-blue-700"><?= $total ?></span></p>
        </div>

    </div>

</body>
</html>
