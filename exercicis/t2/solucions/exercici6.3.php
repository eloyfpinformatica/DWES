<?php
    $errors = [];
    $nom = '';
    $email = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nom = trim($_POST['nom'] ?? '');

        $email = trim($_POST['email'] ?? '');

        if (empty($nom)) {
            $errors['nom'] = "El camp nom és obligatori.";
        } elseif (mb_strlen($nom) < 2) {
            $errors['nom'] = "El nom ha de tindre almenys 2 caràcters.";
        }

        if (empty($email)) {
            $errors['email'] = "El correu electrònic és obligatori.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "El format del correu no és vàlid.";
        }
    }
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 6.3 - Validació de formulari</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Formulari de contacte</h2>

        <!-- VISTA: no cal tocar res d'ací en avall -->
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)): ?>
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                Formulari enviat correctament! Gràcies, <?= htmlspecialchars($nom) ?>.
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-3">
            <div>
                <input type="text" name="nom" value="<?= htmlspecialchars($nom) ?>" placeholder="El teu nom"
                       class="w-full border border-gray-300 rounded px-3 py-2">
                <?php if (isset($errors['nom'])): ?>
                    <p class="text-red-600 text-sm mt-1"><?= $errors['nom'] ?></p>
                <?php endif; ?>
            </div>

            <div>
                <input type="text" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="El teu correu"
                       class="w-full border border-gray-300 rounded px-3 py-2">
                <?php if (isset($errors['email'])): ?>
                    <p class="text-red-600 text-sm mt-1"><?= $errors['email'] ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded">
                Enviar
            </button>
        </form>

    </div>

</body>
</html>
