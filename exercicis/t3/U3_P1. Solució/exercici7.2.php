<?php
    function importarLead(string $linia): array {
        $camps = explode(';', $linia);

        // TODO 1: Valida la línia llançant una Exception, cada una amb el seu missatge i el seu codi
        // (segon paràmetre del constructor d'Exception), en este ordre:
        // - Si no té exactament 3 camps (count): 'Format incorrecte: calen 3 camps.', codi 1
        //   (fes esta comprovació abans de llegir els camps)
        // - Després, guarda els camps (sense espais als extrems) en $nom, $email i $pressupost
        //   (per exemple, amb array_map('trim', $camps) i una assignació per desestructuració)
        // - Si $nom està buit: 'El nom és obligatori.', codi 2
        // - Si $email no és vàlid (filter_var): 'L\'email no és vàlid.', codi 3
        // - Si $pressupost no és numèric (is_numeric): 'El pressupost ha de ser un número.', codi 4
        // Si tot és correcte, retorna ['nom' => ..., 'email' => ..., 'pressupost' => ...],
        // amb el pressupost convertit a float
        if (count($camps) !== 3) {
            throw new Exception('Format incorrecte: calen 3 camps.', 1);
        }

        [$nom, $email, $pressupost] = array_map('trim', $camps);

        if ($nom === '') {
            throw new Exception('El nom és obligatori.', 2);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('L\'email no és vàlid.', 3);
        }

        if (!is_numeric($pressupost)) {
            throw new Exception('El pressupost ha de ser un número.', 4);
        }

        return ['nom' => $nom, 'email' => $email, 'pressupost' => (float) $pressupost];
    }

    // Línies a importar (ja proporcionades, format: nom;email;pressupost)
    $linies = [
        'Aina Soler;aina@textils.cat;4500',
        'Marc Climent;marc-at-econova.cat;800',
        'Laura Sanchis;laura@innovacio.cat;12000',
        ';pau@exemple.cat;300',
        'Joan Peris;joan@exemple.cat;abc',
        'Rosa Vidal;rosa@exemple.cat',
    ];

    $importats   = [];
    $errors      = [];
    $processades = 0;

    // TODO 2: Recorre $linies amb un foreach (clau $i i valor $linia). Dins de cada volta:
    // - Dins d'un bloc try, crida a importarLead($linia) i afig el resultat a $importats
    // - Amb un bloc catch (Exception $e), afig a $errors un array amb les claus
    //   'linia' ($i + 1), 'codi' (getCode()) i 'missatge' (getMessage())
    // - Afig un bloc finally que incremente $processades: s'executa tant si hi ha excepció com si no
    foreach ($linies as $i => $linia) {
        try {
            $importats[] = importarLead($linia);
        } catch (Exception $e) {
            $errors[] = [
                'linia'    => $i + 1,
                'codi'     => $e->getCode(),
                'missatge' => $e->getMessage(),
            ];
        } finally {
            $processades++; // s'executa sempre, hi haja excepció o no
        }
    }

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 7.2 - Importació de leads</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <!-- VISTA: no cal tocar res d'ací en avall -->
    <div class="max-w-3xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold text-gray-800">Importació de leads · TechLeads</h1>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <p class="text-gray-700">
                Línies processades: <span class="font-bold"><?= $processades ?></span> ·
                correctes: <span class="font-bold text-green-700"><?= count($importats) ?></span> ·
                amb errors: <span class="font-bold text-red-700"><?= count($errors) ?></span>
            </p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-green-700 mb-3">Leads importats</h2>
            <?php if (empty($importats)): ?>
                <p class="text-gray-500">Cap lead importat.</p>
            <?php else: ?>
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b text-gray-600">
                            <th class="py-2">Nom</th>
                            <th class="py-2">Email</th>
                            <th class="py-2 text-right">Pressupost</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($importats as $lead): ?>
                            <tr class="border-b text-gray-700">
                                <td class="py-2"><?= htmlspecialchars($lead['nom']) ?></td>
                                <td class="py-2"><?= htmlspecialchars($lead['email']) ?></td>
                                <td class="py-2 text-right"><?= number_format($lead['pressupost'], 2, ',', '.') ?> €</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-red-700 mb-3">Línies amb errors</h2>
            <?php if (empty($errors)): ?>
                <p class="text-gray-500">Cap error.</p>
            <?php else: ?>
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b text-gray-600">
                            <th class="py-2">Línia</th>
                            <th class="py-2">Codi</th>
                            <th class="py-2">Motiu</th>
                            <th class="py-2">Contingut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($errors as $error): ?>
                            <tr class="border-b text-gray-700">
                                <td class="py-2"><?= (int) $error['linia'] ?></td>
                                <td class="py-2"><?= (int) $error['codi'] ?></td>
                                <td class="py-2"><?= htmlspecialchars($error['missatge']) ?></td>
                                <td class="py-2 font-mono text-sm"><?= htmlspecialchars($linies[$error['linia'] - 1]) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>
