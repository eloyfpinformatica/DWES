<?php
    $errors   = [];
    $nomFinal = null;

    $carpetaDesti    = __DIR__ . '/pujades/';
    $grandariaMaxima = 2 * 1024 * 1024; // 2 MB
    $tipusPermesos   = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $arxiu = $_FILES['logo'] ?? null;

        // TODO 1: Si $arxiu és null o el seu camp 'error' és diferent de UPLOAD_ERR_OK,
        // afig a $errors el missatge 'No s\'ha pujat cap arxiu o s\'ha produït un error en la pujada.'
        if ($arxiu === null || $arxiu['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'No s\'ha pujat cap arxiu o s\'ha produït un error en la pujada.';
        }

        // TODO 2: Només si no hi ha errors: si el camp 'size' supera $grandariaMaxima,
        // afig a $errors el missatge 'L\'arxiu no pot superar els 2 MB.'
        if (empty($errors) && $arxiu['size'] > $grandariaMaxima) {
            $errors[] = 'L\'arxiu no pot superar els 2 MB.';
        }

        // TODO 3: Només si no hi ha errors: obtín el tipus real de l'arxiu temporal amb finfo
        // (new finfo(FILEINFO_MIME_TYPE) i el mètode file() sobre 'tmp_name') i guarda'l en $tipusReal.
        // Si $tipusReal no és una clau de $tipusPermesos, afig a $errors el missatge
        // 'Només es permeten imatges JPEG, PNG o WEBP.'
        if (empty($errors)) {
            $finfo     = new finfo(FILEINFO_MIME_TYPE);
            $tipusReal = $finfo->file($arxiu['tmp_name']);

            if (!array_key_exists($tipusReal, $tipusPermesos)) {
                $errors[] = 'Només es permeten imatges JPEG, PNG o WEBP.';
            }
        }

        // TODO 4: Només si no hi ha errors: genera un nom únic amb uniqid('logo_', true), seguit d'un punt
        // i de l'extensió que corresponga a $tipusReal segons $tipusPermesos, i guarda'l en $nomFinal.
        // Mou l'arxiu a $carpetaDesti . $nomFinal amb move_uploaded_file().
        // Si move_uploaded_file() falla, afig a $errors el missatge 'No s\'ha pogut guardar l\'arxiu.'
        // i torna a posar $nomFinal a null
        if (empty($errors)) {
            // L'extensió es pren del tipus real (no del nom que envia l'usuari)
            $nomFinal = uniqid('logo_', true) . '.' . $tipusPermesos[$tipusReal];

            if (!move_uploaded_file($arxiu['tmp_name'], $carpetaDesti . $nomFinal)) {
                $errors[] = 'No s\'ha pogut guardar l\'arxiu.';
                $nomFinal = null;
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 4.5 - Pujar arxius</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <!-- VISTA: no cal tocar res d'ací en avall -->
    <div class="max-w-xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold text-gray-800">Logo de l'empresa · TechLeads</h1>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($nomFinal !== null): ?>
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                <p class="mb-3">Logo pujat correctament com a <span class="font-semibold"><?= htmlspecialchars($nomFinal) ?></span></p>
                <img src="pujades/<?= htmlspecialchars($nomFinal) ?>" alt="Logo pujat" class="h-32 rounded border border-green-300 bg-white p-1">
            </div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md space-y-4">
            <div>
                <label for="logo" class="block text-sm font-semibold text-gray-700 mb-1">
                    Selecciona el logo <span class="font-normal text-gray-500">(JPEG, PNG o WEBP, màx. 2 MB)</span>
                </label>
                <input type="file" id="logo" name="logo" class="w-full text-gray-700">
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                Pujar
            </button>
        </form>

    </div>

</body>
</html>
