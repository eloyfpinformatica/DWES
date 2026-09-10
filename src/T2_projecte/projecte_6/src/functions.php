<?php
// Bloc 5 - Activitat 5.3
function etiquetes_estat(string $estat): string
{
    $color = match ($estat) {
        'nou'                  => "green",
        'contactat'            => "yellow",
        'convertit', 'guanyat' => "green",
        default                => "gray"
    };

    $etiqueta = "<span class=\"bg-{$color}-100 text-{$color}-800 text-xs font-medium px-1.5 py-0.5 rounded\">{$estat}</span>";

    return $etiqueta;
}

//Bloc 6 - Activitat 6.3 - Pendent finalitzar
function filtrar_leads(array $leads, array $filtres): array
{
    $leads_filtrats = [];

    foreach ($leads as $lead) {
        $coincideix = true;

        if(!empty($filtres['nom']) && $lead['nom'] !== $filtres['nom']) {
            $coincideix = false;
        }
        
        if(!empty($filtres['estat']) && $lead['estat'] !== $filtres['estat']) {
            $coincideix = false;
        }

        if(!empty($filtres['servei']) && $lead['servei'] !== $filtres['servei']) {
            $coincideix = false;
        }


        if ($coincideix) {
            $leads_filtrats[] = $lead;
        }
    }

    return $leads_filtrats;
}