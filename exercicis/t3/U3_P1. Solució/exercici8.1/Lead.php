<?php
class Lead {
    // TODO 1: Defineix el constructor amb propietats promocionades (PHP 8):
    // string $nom, string $empresa i float $pressupost, totes private
    public function __construct(
        private string $nom,
        private string $empresa,
        private float $pressupost
    ) {}

    // TODO 2: Afig els tres getters: getNom(), getEmpresa() i getPressupost() (cadascun amb el seu tipus de retorn)
    public function getNom(): string {
        return $this->nom;
    }

    public function getEmpresa(): string {
        return $this->empresa;
    }

    public function getPressupost(): float {
        return $this->pressupost;
    }

    // TODO 3: Afig el mètode aplicarDescompte(float $percentatge): void
    // Només ha de modificar el pressupost si $percentatge està entre 0 i 100 (tots dos inclosos):
    // en eixe cas, rebaixa $this->pressupost amb el percentatge indicat
    public function aplicarDescompte(float $percentatge): void {
        if ($percentatge >= 0 && $percentatge <= 100) {
            $this->pressupost -= $this->pressupost * $percentatge / 100;
        }
    }

    // TODO 4: Afig el mètode getCategoria(): string, que retorne:
    // - 'Gran' si el pressupost és de 10000 o més
    // - 'Mitjà' si és de 2000 o més (i menys de 10000)
    // - 'Xicotet' en qualsevol altre cas
    public function getCategoria(): string {
        if ($this->pressupost >= 10000) {
            return 'Gran';
        }
        if ($this->pressupost >= 2000) {
            return 'Mitjà';
        }
        return 'Xicotet';
    }

    // TODO 5: Afig el mètode màgic __toString(): string, que retorne el text 'Nom (Empresa)',
    // per exemple: Aina Soler (Tèxtils S.L.)
    public function __toString(): string {
        return "{$this->nom} ({$this->empresa})";
    }
}
