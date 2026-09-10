<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detall del Lead | TechLeads</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 font-sans min-h-screen">

    <header class="bg-slate-900 text-white py-4 shadow-lg">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <span class="font-bold text-xl text-indigo-400">TechLeads Admin</span>
            <a href="admin.php" class="text-xs text-slate-400 hover:text-white transition">&larr; Tornar al Panell</a>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-6 py-12">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            
            <div class="flex justify-between items-start mb-6">
                <div>
                    <span class="text-xs font-mono text-slate-400">ID: #001</span>
                    <h1 class="text-2xl font-bold text-slate-900 mt-1">Maria Garcia</h1>
                    <p class="text-slate-500 text-sm">Logística Garcia S.L.</p>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">Nou</span>
            </div>

            <div class="space-y-4 border-t border-b border-slate-100 py-6 my-6 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Correu Electrònic:</span>
                    <span class="font-medium text-slate-900">maria@empresa.com</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Servei Sol·licitat:</span>
                    <span class="font-medium text-slate-900">Botiga Online (eCommerce)</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Pressupost Estimat:</span>
                    <span class="font-mono font-bold text-indigo-600">4.500,00 €</span>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Detalls del projecte</h3>
                <p class="bg-slate-50 p-4 rounded-xl text-slate-700 text-sm border border-slate-200">
                    Necessitem una plataforma e-commerce per a gestionar les nostres vendes de recanvis industrials amb integració ERP.
                </p>
            </div>

            <a href="admin.php" class="inline-block text-sm font-medium text-indigo-600 hover:text-indigo-800">
                &larr; Tornar al llistat de leads
            </a>

        </div>

    </main>

</body>
</html>