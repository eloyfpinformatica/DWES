<?php
// Un lead particular: una persona que ha visitat la nostra web
class LeadParticular extends Lead implements Notificable {
    // TODO 1: Defineix el constructor amb els paràmetres string $nom, string $email
    // i una propietat promocionada private int $visitesWeb.
    // Crida al constructor de la classe mare amb parent::__construct($nom, $email)
    public function __construct(string $nom, string $email, private int $visitesWeb) {
        parent::__construct($nom, $email);
    }

    // TODO 2: Implementa calcularPuntuacio(): int. La puntuació són les visites a la web multiplicades per 2
    public function calcularPuntuacio(): int {
        return $this->visitesWeb * 2;
    }

    // TODO 3: Implementa enviarNotificacio(string $missatge): string.
    // Ha de retornar el text 'Correu personal a EMAIL: MISSATGE'
    public function enviarNotificacio(string $missatge): string {
        return "Correu personal a {$this->email}: $missatge";
    }
}
