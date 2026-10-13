<?php
    // TODO 1: Carrega Lead.php amb require_once i una ruta construïda amb __DIR__
    require_once __DIR__ . '/Lead.php';

    // TODO 2: Crea l'array $leads amb tres objectes de la classe Lead, amb new Lead(nom, empresa, pressupost):
    // - 'Aina Soler', 'Tèxtils S.L.', 4500
    // - 'Marc Climent', 'Econova', 800
    // - 'Laura Sanchis', 'Innovació Tech', 12000
    $leads = [
        new Lead('Aina Soler', 'Tèxtils S.L.', 4500),
        new Lead('Marc Climent', 'Econova', 800),
        new Lead('Laura Sanchis', 'Innovació Tech', 12000),
    ];

    // TODO 3: Aplica un descompte del 20% al tercer lead ($leads[2]) amb el seu mètode aplicarDescompte()
    $leads[2]->aplicarDescompte(20);

    // TODO 4: Declara $total: recorre $leads amb un foreach i suma el resultat de getPressupost() de cada lead
    $total = 0.0;
    foreach ($leads as $lead) {
        $total += $lead->getPressupost();
    }

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 8.1 - Classe Lead</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <!-- VISTA: no cal tocar res d'ací en avall -->
    <div class="max-w-3xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold text-gray-800">Cartera de leads · TechLeads</h1>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <p class="text-gray-700 mb-4">
                Primer lead de la llista:
                <span class="font-semibold text-blue-700"><?= htmlspecialchars((string) $leads[0]) ?></span>
            </p>

            <table class="w-full text-left">
                <thead>
                    <tr class="border-b text-gray-600">
                        <th class="py-2">Nom</th>
                        <th class="py-2">Empresa</th>
                        <th class="py-2">Categoria</th>
                        <th class="py-2 text-right">Pressupost</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leads as $lead): ?>
                        <?php
                            $classesCategoria = match ($lead->getCategoria()) {
                                'Gran'  => 'bg-purple-100 text-purple-800',
                                'Mitjà' => 'bg-blue-100 text-blue-800',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        ?>
                        <tr class="border-b text-gray-700">
                            <td class="py-2"><?= htmlspecialchars($lead->getNom()) ?></td>
                            <td class="py-2"><?= htmlspecialchars($lead->getEmpresa()) ?></td>
                            <td class="py-2">
                                <span class="px-2 py-1 rounded text-sm font-semibold <?= $classesCategoria ?>">
                                    <?= htmlspecialchars($lead->getCategoria()) ?>
                                </span>
                            </td>
                            <td class="py-2 text-right"><?= number_format($lead->getPressupost(), 2, ',', '.') ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="font-bold text-gray-800">
                        <td colspan="3" class="py-3">Total</td>
                        <td class="py-3 text-right"><?= number_format($total, 2, ',', '.') ?> €</td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>

</body>
</html>
