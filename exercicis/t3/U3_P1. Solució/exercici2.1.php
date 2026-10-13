<?php
    // TODO 1: Simulem que el visitant ja és client
    $esClient = true;

    // TODO 2: Redirecció + exit (sense exit, el codi posterior continuaria executant-se)
    if ($esClient) {
        header('Location: zona-clients.php');
        exit;
    }

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 2.1 - Redirecció</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <!-- VISTA: no cal tocar res d'ací en avall -->
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full text-center">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Zona pública</h1>
        <p class="text-gray-600">Este contingut només el veuen els visitants que encara no són clients de TechLeads.</p>
    </div>

</body>
</html>
