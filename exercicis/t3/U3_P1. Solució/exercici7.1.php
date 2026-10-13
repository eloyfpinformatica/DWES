<?php
    function calcularComissio(float $venda, float $percentatge): float {
        // TODO 1: Llança una Exception (throw new Exception(...)) en estos casos:
        // - Si $venda és negativa: 'La venda no pot ser negativa.'
        // - Si $percentatge està fora del rang de 0 a 100: 'El percentatge ha d\'estar entre 0 i 100.'
        // Si les dades són correctes, retorna la comissió: $venda * $percentatge / 100
        if ($venda < 0) {
            throw new Exception('La venda no pot ser negativa.');
        }

        if ($percentatge < 0 || $percentatge > 100) {
            throw new Exception('El percentatge ha d\'estar entre 0 i 100.');
        }

        return $venda * $percentatge / 100;
    }

    $comissio = null;
    $error    = null;
    $enviat   = $_SERVER['REQUEST_METHOD'] === 'POST';

    if ($enviat) {
        // Dades rebudes (ja proporcionades, no cal que les toques)
        $venda       = (float) ($_POST['venda'] ?? 0);
        $percentatge = (float) ($_POST['percentatge'] ?? 0);

        // TODO 2: Dins d'un bloc try, crida a calcularComissio($venda, $percentatge)
        // i guarda el resultat en $comissio.
        // Amb un bloc catch (Exception $e), guarda el missatge de l'excepció
        // (getMessage()) en $error
        try {
            $comissio = calcularComissio($venda, $percentatge);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 7.1 - Comissió comercial</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <!-- VISTA: no cal tocar res d'ací en avall -->
    <div class="max-w-xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold text-gray-800">Comissió comercial · TechLeads</h1>

        <?php if ($error !== null): ?>
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($comissio !== null): ?>
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                Sobre una venda de <?= number_format($venda, 2, ',', '.') ?> € amb un <?= $percentatge ?>%,
                la comissió és de <span class="font-bold"><?= number_format($comissio, 2, ',', '.') ?> €</span>.
            </div>
        <?php endif; ?>

        <!-- novalidate: perquè puguis provar valors incorrectes -->
        <form method="POST" action="" novalidate class="bg-white p-6 rounded-lg shadow-md space-y-4">
            <div>
                <label for="venda" class="block text-sm font-semibold text-gray-700 mb-1">Import de la venda (€)</label>
                <input type="text" id="venda" name="venda" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div>
                <label for="percentatge" class="block text-sm font-semibold text-gray-700 mb-1">Percentatge de comissió (%)</label>
                <input type="text" id="percentatge" name="percentatge" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                Calcular
            </button>
        </form>

    </div>

</body>
</html>
