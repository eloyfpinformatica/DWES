<?php
    $enviat = $_SERVER['REQUEST_METHOD'] === 'POST';

    // TODO 1: Recupera l'array associatiu $_POST['contacte'] (amb ?? [] per defecte)
    // i guarda'l en $contacte. Després declara $nom i $email amb els valors
    // de les claus 'nom' i 'email' d'eixe array (amb ?? i '' per defecte)
    $contacte = $_POST['contacte'] ?? [];
    $nom      = $contacte['nom'] ?? '';
    $email    = $contacte['email'] ?? '';

    // TODO 2: Recupera l'array $_POST['serveis'] (amb ?? [] per defecte) i guarda'l en $serveis.
    // Declara $totalServeis amb el nombre de serveis marcats (count)
    $serveis      = $_POST['serveis'] ?? [];
    $totalServeis = count($serveis);

    // TODO 3: Recupera l'array $_POST['idiomes'] (amb ?? [] per defecte) i guarda'l en $idiomes.
    // Declara $resumIdiomes amb els idiomes separats per comes (implode)
    $idiomes      = $_POST['idiomes'] ?? [];
    $resumIdiomes = implode(', ', $idiomes);

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 4.1 - Arrays en formularis</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <!-- VISTA: no cal tocar res d'ací en avall -->
    <div class="max-w-xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold text-gray-800">Sol·licitud d'informació · TechLeads</h1>

        <form method="POST" action="" class="bg-white p-6 rounded-lg shadow-md space-y-4">
            <div>
                <label for="nom" class="block text-sm font-semibold text-gray-700 mb-1">Nom</label>
                <input type="text" id="nom" name="contacte[nom]" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="contacte[email]" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <fieldset>
                <legend class="block text-sm font-semibold text-gray-700 mb-1">Serveis d'interés</legend>
                <div class="flex flex-wrap gap-4 text-gray-700">
                    <label class="flex items-center gap-2"><input type="checkbox" name="serveis[]" value="Web"> Web</label>
                    <label class="flex items-center gap-2"><input type="checkbox" name="serveis[]" value="App mòbil"> App mòbil</label>
                    <label class="flex items-center gap-2"><input type="checkbox" name="serveis[]" value="SEO"> SEO</label>
                    <label class="flex items-center gap-2"><input type="checkbox" name="serveis[]" value="Cloud"> Cloud</label>
                </div>
            </fieldset>

            <div>
                <label for="idiomes" class="block text-sm font-semibold text-gray-700 mb-1">
                    Idiomes de comunicació <span class="font-normal text-gray-500">(Ctrl/Cmd + clic per a triar-ne diversos)</span>
                </label>
                <select id="idiomes" name="idiomes[]" multiple size="4" class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="Valencià">Valencià</option>
                    <option value="Castellà">Castellà</option>
                    <option value="Anglés">Anglés</option>
                    <option value="Francés">Francés</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                Enviar sol·licitud
            </button>
        </form>

        <?php if ($enviat): ?>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-green-700 mb-3">Sol·licitud rebuda</h2>
                <p class="text-gray-700"><span class="font-semibold">Nom:</span> <?= htmlspecialchars($nom) ?></p>
                <p class="text-gray-700"><span class="font-semibold">Email:</span> <?= htmlspecialchars($email) ?></p>

                <p class="text-gray-700 mt-2"><span class="font-semibold">Serveis triats (<?= $totalServeis ?>):</span></p>
                <?php if ($totalServeis === 0): ?>
                    <p class="text-gray-500">Cap servei seleccionat.</p>
                <?php else: ?>
                    <ul class="list-disc list-inside text-gray-700">
                        <?php foreach ($serveis as $servei): ?>
                            <li><?= htmlspecialchars($servei) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <p class="text-gray-700 mt-2">
                    <span class="font-semibold">Idiomes:</span>
                    <?= $resumIdiomes !== '' ? htmlspecialchars($resumIdiomes) : 'Cap idioma seleccionat' ?>
                </p>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>
