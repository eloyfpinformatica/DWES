<?php
    $llistatLeads = [
        [
            'id'         => 1,
            'nom'        => 'Joan Garcia',
            'empresa'    => 'Textils S.L.',
            'pressupost' => 4500.00,
            'estat'      => 'nou'
        ],
        [
            'id'         => 2,
            'nom'        => 'Laura Sanchis',
            'empresa'    => 'Innovació Tech',
            'pressupost' => 12000.00,
            'estat'      => 'convertit'
        ],
        [
            'id'         => 3,
            'nom'        => 'Marc Climent',
            'empresa'    => 'Econova',
            'pressupost' => 800.00,
            'estat'      => 'descartat'
        ]
    ];

    $filesTaula = '';
    foreach ($llistatLeads as $lead) {
        $filesTaula .= "<tr><td class='p-2'>{$lead['id']}</td><td class='p-2'>{$lead['nom']}</td><td class='p-2'>{$lead['empresa']}</td><td class='p-2'>{$lead['pressupost']} €</td></tr>";
    }

    $totalLeads = count($llistatLeads);

    $leadsEnterprise = array_filter($llistatLeads, function ($lead) {
        return $lead['pressupost'] >= 1500;
    });

    $totalEnterprise = count($leadsEnterprise);
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 4.3 - Llistat de leads</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <div class="max-w-3xl mx-auto">

        <h1 class="text-2xl font-bold text-gray-800 mb-4">Llistat de Leads</h1>

        <table class="w-full bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="p-2 text-left">ID</th>
                    <th class="p-2 text-left">Nom</th>
                    <th class="p-2 text-left">Empresa</th>
                    <th class="p-2 text-left">Pressupost</th>
                </tr>
            </thead>
            <tbody>
                <?= $filesTaula ?>
            </tbody>
        </table>

        <div class="bg-white p-6 rounded-lg shadow-md flex justify-around text-center">
            <div>
                <p class="text-gray-500 text-sm">Total de leads</p>
                <p class="text-2xl font-bold text-blue-700"><?= $totalLeads ?></p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Leads Enterprise (&ge; 1500 €)</p>
                <p class="text-2xl font-bold text-green-700"><?= $totalEnterprise ?></p>
            </div>
        </div>

    </div>

</body>
</html>
