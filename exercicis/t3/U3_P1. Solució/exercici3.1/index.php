<?php
    // TODO 1: La variable es defineix ABANS del require: l'arxiu inclòs comparteix l'àmbit
    $titolPagina = 'Inici';

    // TODO 2: Imprescindible -> require
    require 'capcalera.php';

    // TODO 3: Opcional -> include (si no existeix, només hi ha un Warning)
    include 'banner-ofertes.php';

?>

<!-- CONTINGUT: no cal tocar res d'ací en avall, excepte el TODO 4 -->
<main class="max-w-3xl mx-auto p-6 flex-1">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Benvinguts a TechLeads</h1>
    <p class="text-gray-600">Gestiona els teus leads comercials de manera senzilla.</p>
</main>

<?php
    // TODO 4
    require 'peu.php';
?>
