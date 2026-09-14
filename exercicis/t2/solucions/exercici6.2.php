<?php
    $nom = '';
    $missatge = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = $_POST['nom'] ?? '';
        $missatge = $_POST['missatge'] ?? '';
    }
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 6.2 - Formulari de contacte (POST)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Contacta amb TechLeads</h2>

        <form method="POST" action="" class="space-y-3 mb-4">
            <input type="text" name="nom" placeholder="El teu nom"
                   class="w-full border border-gray-300 rounded px-3 py-2">
            <textarea name="missatge" placeholder="El teu missatge" rows="3"
                      class="w-full border border-gray-300 rounded px-3 py-2"></textarea>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded">
                Enviar
            </button>
        </form>

        <!-- VISTA: no cal tocar res d'ací en avall -->
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <div class="bg-green-100 text-green-800 p-4 rounded">
                Gràcies, <strong><?= htmlspecialchars($nom) ?></strong>! Hem rebut el teu missatge:
                "<?= htmlspecialchars($missatge) ?>"
            </div>
        <?php endif; ?>

    </div>

</body>
</html>
