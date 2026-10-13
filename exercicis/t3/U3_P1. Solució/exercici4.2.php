<?php
    $errors = [];
    $enviat = $_SERVER['REQUEST_METHOD'] === 'POST';

    if ($enviat) {
        // Dades rebudes (ja proporcionades, no cal que les toques)
        $nom        = trim($_POST['nom'] ?? '');
        $email      = trim($_POST['email'] ?? '');
        $web        = trim($_POST['web'] ?? '');
        $pressupost = trim($_POST['pressupost'] ?? '');

        // TODO 1: El nom és obligatori. Si està buit, afig a $errors
        // el missatge 'El nom és obligatori.'
        if ($nom === '') {
            $errors[] = 'El nom és obligatori.';
        }

        // TODO 2: L'email ha de ser vàlid (filter_var amb FILTER_VALIDATE_EMAIL).
        // Si no ho és, afig a $errors el missatge 'L\'email no és vàlid.'
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'L\'email no és vàlid.';
        }

        // TODO 3: La web és opcional, però si s'ha escrit alguna cosa ha de ser una URL vàlida
        // (filter_var amb FILTER_VALIDATE_URL). Missatge: 'La web no és una URL vàlida.'
        if ($web !== '' && !filter_var($web, FILTER_VALIDATE_URL)) {
            $errors[] = 'La web no és una URL vàlida.';
        }

        // TODO 4: El pressupost ha de ser un enter entre 0 i 100000
        // (filter_var amb FILTER_VALIDATE_INT i les opcions min_range i max_range).
        // Compte: 0 és un valor vàlid. Missatge: 'El pressupost ha de ser un enter entre 0 i 100000.'
        $pressupostValid = filter_var($pressupost, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0, 'max_range' => 100000]
        ]);

        // Es compara amb === false perquè el 0 és vàlid (i empty(0) diria que no)
        if ($pressupostValid === false) {
            $errors[] = 'El pressupost ha de ser un enter entre 0 i 100000.';
        }
    }
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 4.2 - Validació</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <!-- VISTA: no cal tocar res d'ací en avall -->
    <div class="max-w-xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold text-gray-800">Alta de lead · TechLeads</h1>

        <?php if ($enviat && empty($errors)): ?>
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                Lead donat d'alta correctament!
            </div>
        <?php elseif ($enviat): ?>
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded">
                <p class="font-semibold mb-1">Revisa les dades:</p>
                <ul class="list-disc list-inside">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- novalidate: desactiva la validació del navegador perquè pugues provar la del servidor -->
        <form method="POST" action="" novalidate class="bg-white p-6 rounded-lg shadow-md space-y-4">
            <div>
                <label for="nom" class="block text-sm font-semibold text-gray-700 mb-1">Nom</label>
                <input type="text" id="nom" name="nom" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div>
                <label for="web" class="block text-sm font-semibold text-gray-700 mb-1">
                    Web <span class="font-normal text-gray-500">(opcional)</span>
                </label>
                <input type="url" id="web" name="web" placeholder="https://..." class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div>
                <label for="pressupost" class="block text-sm font-semibold text-gray-700 mb-1">Pressupost estimat (€)</label>
                <input type="text" id="pressupost" name="pressupost" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                Donar d'alta
            </button>
        </form>

    </div>

</body>
</html>
