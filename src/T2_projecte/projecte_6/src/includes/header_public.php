<?php
    // Bloc 1 - Activitat 1.2s
    //$autor = "Eloy";

    //Bloc 2 - Activitat 2.5
    //Bloc 5 - Activitat 5.2 el comentem/eliminem
    //define("AUTOR", "Eloy");

    //Bloc 4 - Activitat 4.1

    /*
    $catalegServeis = [
        "web" => "Desenvolupament Web Custom",
        "ecommerce" => "Botiga Online (eCommerce)",
        "seo" => "Auditoria SEO & Rendiment",
        "app" => "Aplicació Mòbil (App)"
    ];
    */
    
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bloc 5 - Activitat 5.2 -->
    <title><?= APP_NAME ?> | Serveis de Desenvolupament Web</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col justify-between">

    <!-- Capçalera Pública -->
    <header class="bg-white border-b border-slate-200 py-4 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <span class="font-extrabold text-2xl text-indigo-600"><?= APP_NAME ?></span>
                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded font-mono">v1.0</span>
            </div>
            <a href="admin.php" class="text-xs font-semibold text-slate-500 hover:text-indigo-600 transition">
                Accés Privat (Admin) &rarr;
            </a>
        </div>
    </header>