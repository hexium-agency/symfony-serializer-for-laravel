<?php

namespace Workbench\App\Enum;

use Illuminate\Support\Collection;
use Shared\Infrastructure\Laravel\View\Components\Combobox\ComboboxOption;

enum Region: string
{
    case AuvergneRhoneAlpes = 'auvergne_rhone_alpes';
    case BourgogneFrancheComte = 'bourgogne_franche_comte';
    case Bretagne = 'bretagne';
    case CentreValDeLoire = 'centre_val_de_loire';
    case Corse = 'corse';
    case GrandEst = 'grand_est';
    case HautsDeFrance = 'hauts_de_france';
    case IleDeFrance = 'ile_de_france';
    case Normandie = 'normandie';
    case NouvelleAquitaine = 'nouvelle_aquitaine';
    case Occitanie = 'occitanie';
    case PaysDeLaLoire = 'pays_de_la_loire';
    case ProvenceAlpesCoteDazur = 'provence_alpes_cote_dazur';
    case DomTom = 'dom_tom';

    public static function fromName(string $regionName): Region
    {
        $regionName = trim($regionName);

        return match ($regionName) {
            'Auvergne-Rhône-Alpes' => self::AuvergneRhoneAlpes,
            'Bourgogne-Franche-Comté' => self::BourgogneFrancheComte,
            'Bretagne' => self::Bretagne,
            'Centre-Val de Loire' => self::CentreValDeLoire,
            'Corse' => self::Corse,
            'Grand Est' => self::GrandEst,
            'Hauts-de-France' => self::HautsDeFrance,
            'Île-de-France' => self::IleDeFrance,
            'Normandie' => self::Normandie,
            'Nouvelle-Aquitaine' => self::NouvelleAquitaine,
            'Occitanie' => self::Occitanie,
            'Pays de la Loire' => self::PaysDeLaLoire,
            'Provence-Alpes-Côte d\'Azur' => self::ProvenceAlpesCoteDazur,
            'DOM-TOM' => self::DomTom,
            default => throw new \InvalidArgumentException("Unknown region name: $regionName"),
        };
    }

    /**
     * @return array<array-key, Department>
     */
    public function departments(): array
    {
        return match ($this) {
            self::AuvergneRhoneAlpes => [
                Department::Ain,
                Department::Allier,
                Department::Ardeche,
                Department::Cantal,
                Department::Drome,
                Department::Isere,
                Department::Loire,
                Department::HauteLoire,
                Department::PuyDeDome,
                Department::Rhone,
                Department::Savoie,
                Department::HauteSavoie,
            ],
            self::BourgogneFrancheComte => [
                Department::CoteDOr,
                Department::Doubs,
                Department::Jura,
                Department::Nievre,
                Department::HauteSaone,
                Department::SaoneEtLoire,
                Department::Yonne,
                Department::TerritoireDeBelfort,
            ],
            self::Bretagne => [
                Department::CotesDArmor,
                Department::Finistere,
                Department::IlleEtVilaine,
                Department::Morbihan,
            ],
            self::CentreValDeLoire => [
                Department::Cher,
                Department::EureEtLoir,
                Department::Indre,
                Department::IndreEtLoire,
                Department::LoirEtCher,
                Department::Loiret,
            ],
            self::Corse => [
                Department::CorseDuSud,
                Department::CorseDuNord,
            ],
            self::GrandEst => [
                Department::Ardennes,
                Department::Aube,
                Department::Marne,
                Department::HauteMarne,
                Department::MeurtheEtMoselle,
                Department::Meuse,
                Department::Moselle,
                Department::BasRhin,
                Department::HautRhin,
                Department::Vosges,
            ],
            self::HautsDeFrance => [
                Department::Aisne,
                Department::Nord,
                Department::Oise,
                Department::PasDeCalais,
                Department::Somme,
            ],
            self::IleDeFrance => [
                Department::Paris,
                Department::SeineEtMarne,
                Department::Yvelines,
                Department::Essonne,
                Department::HautsDeSeine,
                Department::SeineSaintDenis,
                Department::ValDeMarne,
                Department::ValDOise,
            ],
            self::Normandie => [
                Department::Calvados,
                Department::Eure,
                Department::Manche,
                Department::Orne,
                Department::SeineMaritime,
            ],
            self::NouvelleAquitaine => [
                Department::Charente,
                Department::CharenteMaritime,
                Department::Correze,
                Department::Creuse,
                Department::Dordogne,
                Department::Gironde,
                Department::Landes,
                Department::LotEtGaronne,
                Department::PyreneesAtlantiques,
                Department::DeuxSevres,
                Department::Vienne,
                Department::HauteVienne,
                Department::Charente,
                Department::CharenteMaritime,
                Department::Correze,
                Department::Creuse,
                Department::Dordogne,
                Department::Gironde,
                Department::Landes,
                Department::LotEtGaronne,
                Department::PyreneesAtlantiques,
                Department::DeuxSevres,
                Department::Vienne,
                Department::HauteVienne,
            ],
            self::Occitanie => [
                Department::Ariege,
                Department::Aude,
                Department::Aveyron,
                Department::Gard,
                Department::HauteGaronne,
                Department::Gers,
                Department::Herault,
                Department::Lot,
                Department::Lozere,
                Department::HautesPyrenees,
                Department::PyreneesOrientales,
                Department::Tarn,
                Department::TarnEtGaronne,
            ],
            self::PaysDeLaLoire => [
                Department::LoireAtlantique,
                Department::MaineEtLoire,
                Department::Mayenne,
                Department::Sarthe,
                Department::Vendee,
            ],
            self::ProvenceAlpesCoteDazur => [
                Department::AlpesDeHauteProvence,
                Department::HautesAlpes,
                Department::AlpesMaritimes,
                Department::BouchesDuRhone,
                Department::Var,
                Department::Vaucluse,
            ],
            self::DomTom => [
                Department::Guadeloupe,
                Department::Martinique,
                Department::Guyane,
                Department::Reunion,
                Department::Mayotte,
            ],
        };
    }

    public function translationKey(): string
    {
        return 'region.'.$this->value;
    }

    public function toCombobox(string $value, string $label): ComboboxOption
    {
        $labelKey = $this->translationKey();
        $labelValue = __($labelKey);

        return new ComboboxOption(
            value: $this->name,
            label: __($labelValue),
        );
    }

    /**
     * @return Collection<int, ComboboxOption>
     */
    public static function allComboboxOptions(): Collection
    {
        return collect(self::cases())->map(fn (Region $region) => $region->toCombobox($region->name, $region->translationKey()));
    }
}
