<?php
    // TODO 1: Inicia la sessió amb session_start()
    session_start();

    // TODO 2: Buida $_SESSION amb un array buit i destruïx la sessió amb session_destroy()
    $_SESSION = [];
    session_destroy();

    // TODO 3: Redirigeix a 'login.php' amb exit
    header('Location: login.php');
    exit;
