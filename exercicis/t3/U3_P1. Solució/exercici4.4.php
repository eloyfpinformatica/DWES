<?php
    function e(string $valor): string {
        return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
    }

    // Dades de suport per al formulari (ja proporcionades)
    $serveis    = ['web' => 'Desenvolupament web', 'app' => 'Aplicació mòbil', 'seo' => 'Consultoria SEO'];
    $prioritats = ['alta' => 'Alta', 'mitjana' => 'Mitjana', 'baixa' => 'Baixa'];
    $interessosDisponibles = ['facturacio' => 'Facturació', 'crm' => 'CRM', 'analitica' => 'Analítica'];

    $errors   = [];
    $enviatOk = false;

    $nom           = '';
    $email         = '';
    $servei        = '';
    $prioritat     = '';
    $interessos    = [];
    $acceptaTermes = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Captura de dades (ja proporcionada)
        $nom           = trim($_POST['nom'] ?? '');
        $email         = trim($_POST['email'] ?? '');
        $servei        = $_POST['servei'] ?? '';
        $prioritat     = $_POST['prioritat'] ?? '';
        $interessos    = $_POST['interessos'] ?? [];
        $acceptaTermes = isset($_POST['termes']);

        // Validació (ja proporcionada)
        if ($nom === '') {
            $errors[] = 'El nom és obligatori.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'L\'email no és vàlid.';
        }
        if (!array_key_exists($servei, $serveis)) {
            $errors[] = 'Has de triar un servei.';
        }
        if (!array_key_exists($prioritat, $prioritats)) {
            $errors[] = 'Has de triar una prioritat.';
        }
        if (!$acceptaTermes) {
            $errors[] = 'Has d\'acceptar els termes i condicions.';
        }

        if (empty($errors)) {
            // TODO 1: Les dades són vàlides. Marca $enviatOk com a true i buida tots els valors
            // ($nom, $email, $servei, $prioritat, $interessos i $acceptaTermes) tornant-los
            // al seu estat inicial, perquè el formulari es mostre buit després d'un enviament correcte
            $enviatOk      = true;
            $nom           = '';
            $email         = '';
            $servei        = '';
            $prioritat     = '';
            $interessos    = [];
            $acceptaTermes = false;
        }
    }
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 4.4 - Sticky forms</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <!-- VISTA -->
    <div class="max-w-xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold text-gray-800">Alta de lead · TechLeads</h1>

        <?php if ($enviatOk): ?>
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                Lead donat d'alta correctament!
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="" novalidate class="bg-white p-6 rounded-lg shadow-md space-y-4">

            
            <div>
                <label for="nom" class="block text-sm font-semibold text-gray-700 mb-1">Nom</label>
                <input type="text" id="nom" name="nom" value="<?= e($nom) ?>" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" value="<?= e($email) ?>" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div>
                <label for="servei" class="block text-sm font-semibold text-gray-700 mb-1">Servei</label>
                <select id="servei" name="servei" class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">-- Selecciona --</option>
                    <?php foreach ($serveis as $codi => $etiqueta): ?>
                        <option value="<?= e($codi) ?>" <?= $servei === $codi ? 'selected' : '' ?>><?= e($etiqueta) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <fieldset>
                <legend class="block text-sm font-semibold text-gray-700 mb-1">Prioritat</legend>
                <div class="flex gap-4 text-gray-700">
                    <?php foreach ($prioritats as $codi => $etiqueta): ?>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="prioritat" value="<?= e($codi) ?>" <?= $prioritat === $codi ? 'checked' : '' ?>>
                            <?= e($etiqueta) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <fieldset>
                <legend class="block text-sm font-semibold text-gray-700 mb-1">Àrees d'interés</legend>
                <div class="flex flex-wrap gap-4 text-gray-700">
                    <?php foreach ($interessosDisponibles as $codi => $etiqueta): ?>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="interessos[]" value="<?= e($codi) ?>" <?= in_array($codi, $interessos) ? 'checked' : '' ?>>
                            <?= e($etiqueta) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <label class="flex items-center gap-2 text-gray-700">
                <input type="checkbox" name="termes" <?= $acceptaTermes ? 'checked' : '' ?>>
                Accepte els termes i condicions
            </label>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                Donar d'alta
            </button>
        </form>

    </div>

</body>
</html>
