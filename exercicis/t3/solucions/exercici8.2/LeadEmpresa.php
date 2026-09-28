<?php
// Un lead d'empresa: una organització amb empleats i pressupost
class LeadEmpresa extends Lead implements Notificable {
    // TODO 1: Defineix el constructor amb els paràmetres string $nom, string $email
    // i dues propietats promocionades: private int $empleats i private float $pressupost.
    // Crida al constructor de la classe mare amb parent::__construct($nom, $email)
    public function __construct(
        string $nom,
        string $email,
        private int $empleats,
        private float $pressupost
    ) {
        parent::__construct($nom, $email);
    }

    // TODO 2: Implementa calcularPuntuacio(): int. La puntuació és el nombre d'empleats
    // més els milers d'euros del pressupost (la part entera de $pressupost / 1000)
    public function calcularPuntuacio(): int {
        return $this->empleats + (int) ($this->pressupost / 1000);
    }

    // TODO 3: Sobreescriu descriure(): string. Ha de retornar el que retorna descriure()
    // de la classe mare (parent::descriure()) seguit del text ' - empresa de X empleats'
    public function descriure(): string {
        return parent::descriure() . " - empresa de {$this->empleats} empleats";
    }

    // TODO 4: Implementa enviarNotificacio(string $missatge): string.
    // Ha de retornar el text 'Correu corporatiu a NOM (EMAIL): MISSATGE'
    public function enviarNotificacio(string $missatge): string {
        return "Correu corporatiu a {$this->nom} ({$this->email}): $missatge";
    }
}
