<?php
// Simula una taula d'usuaris de la base de dades.
// Fixa't que NOMÉS es guarden els hashes, mai les contrasenyes en text pla.
$usuaris = [
    'ana'  => ['hash' => '$2y$10$jtyC89SwKBSRwUpjO.3Ot.Rql4v6QY05kZR7SFrkVLUyWHLNVnZK6', 'rol' => 'comercial'],
    'marc' => ['hash' => '$2y$10$74Dle4/UJiucMgWk8l4bMuOEhYAzKPGxIrGcDAkOmqivk5ocTez6C', 'rol' => 'administrador'],
];
