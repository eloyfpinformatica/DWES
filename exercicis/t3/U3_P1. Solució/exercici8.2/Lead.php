<?php
abstract class Lead {
    // TODO 1: Defineix el constructor amb propietats promocionades protected: string $nom i string $email
    public function __construct(
        protected string $nom,
        protected string $email
    ) {}

    // TODO 2: Declara el mètode abstracte public calcularPuntuacio(): int (sense cos)
    abstract public function calcularPuntuacio(): int;

    // TODO 3: Afig el mètode public descriure(): string, que retorne el text:
    // 'Nom <email> - puntuació: X', on X és el resultat de cridar a calcularPuntuacio()
    public function descriure(): string {
        return "{$this->nom} <{$this->email}> - puntuació: {$this->calcularPuntuacio()}";
    }
}
