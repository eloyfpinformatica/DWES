<?php

function comptarLeadsNous(array $llistat): int {
    $total = 0;
    foreach ($llistat as $lead) {
        if ($lead['estat'] === 'nou') {
            $total++;
        }
    }
    return $total;
}
