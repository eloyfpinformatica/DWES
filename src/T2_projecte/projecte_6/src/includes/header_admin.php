<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bloc 5 - Activitat 5.2 -->
    <title>Panell Privat d'Administració | <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 font-sans min-h-screen">

    <header class="bg-slate-900 text-white py-4 shadow-lg">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <!-- Bloc 5 - Activitat 5.2 -->
                <span class="font-bold text-xl text-indigo-400"><?= APP_NAME ?></span>
                <span class="text-xs bg-indigo-900/60 text-indigo-300 border border-indigo-700 px-2.5 py-0.5 rounded-full font-mono">Àrea Privada</span>
            </div>
            <a href="index.php" class="text-xs text-slate-400 hover:text-white transition">&larr; Anar a la Web Pública</a>
        </div>
    </header>