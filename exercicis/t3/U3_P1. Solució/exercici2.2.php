<?php
    // Array ja proporcionat, no cal que el toques
    $leads = [
        1 => ['id' => 1, 'nom' => 'Aina Soler',    'empresa' => 'Textils S.L.',  'pressupost' => 4500.0],
        2 => ['id' => 2, 'nom' => 'Marc Climent',  'empresa' => 'Econova',       'pressupost' => 800.0],
        3 => ['id' => 3, 'nom' => 'Laura Sanchis', 'empresa' => 'Innovacio Tech', 'pressupost' => 12000.0],
    ];

    // Id demanat per la URL (ja proporcionat), per exemple: exercici2.2.php?id=1
    $id = (int) ($_GET['id'] ?? 0);
    $lead = $leads[$id] ?? null;

    // TODO 1: La resposta és JSON, no HTML
    header('Content-Type: application/json');

    // TODO 2: Lead inexistent -> 404 + missatge d'error en JSON + exit
    if ($lead === null) {
        http_response_code(404);
        echo json_encode(['error' => 'Lead no trobat']);
        exit;
    }

    // TODO 3: Lead trobat -> es retorna en JSON (codi 200 per defecte)
    echo json_encode($lead);
