<?php
class Lead {
    // TODO 1: Defineix tres constants de classe per als estats:
    // ESTAT_NOU = 'nou', ESTAT_CONTACTAT = 'contactat' i ESTAT_TANCAT = 'tancat'
    const ESTAT_NOU       = 'nou';
    const ESTAT_CONTACTAT = 'contactat';
    const ESTAT_TANCAT    = 'tancat';

    // TODO 2: Declara una propietat estàtica privada $totalCreats de tipus int, amb valor inicial 0
    private static int $totalCreats = 0;

    // TODO 3: Declara la propietat pública readonly int $id (sense valor inicial: s'assignarà en el constructor)
    // i la propietat privada string $estat, amb el valor inicial self::ESTAT_NOU
    public readonly int $id;
    private string $estat = self::ESTAT_NOU;

    // TODO 4: Defineix el constructor amb una propietat promocionada public readonly string $nom.
    // Dins del constructor, incrementa $totalCreats (amb self::) i assigna el nou valor a $this->id
    public function __construct(public readonly string $nom) {
        self::$totalCreats++;
        $this->id = self::$totalCreats;
    }

    // TODO 5: Afig el mètode getEstat(): string, que retorne l'estat actual
    public function getEstat(): string {
        return $this->estat;
    }

    // TODO 6: Afig el mètode avancarEstat(): void, que faça passar l'estat de nou a contactat i de contactat a tancat.
    // Si l'estat ja és tancat, no ha de canviar (usa les constants, amb match o amb if/elseif)
    public function avancarEstat(): void {
        $this->estat = match ($this->estat) {
            self::ESTAT_NOU       => self::ESTAT_CONTACTAT,
            self::ESTAT_CONTACTAT => self::ESTAT_TANCAT,
            default               => self::ESTAT_TANCAT,
        };
    }

    // TODO 7: Afig el mètode estàtic getTotalCreats(): int, que retorne el nombre de leads creats fins ara
    public static function getTotalCreats(): int {
        return self::$totalCreats;
    }
}
