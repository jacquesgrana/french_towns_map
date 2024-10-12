<?php

namespace App\Dto\ApiDataCultureGouv;

use App\Dto\ApiDataCultureGouv\CoordonneesDto;

class MuseumDto
{

    private string $identifiant;
    private string $nomOfficiel;
    private string $adresse;
    private string $lieu;
    private string $codePostal;

    private string $ville;
    private string $region;
    private string $departement;
    private string $url;
    private string $telephone;

    private string $categorie;
    private array $domaine_thematique;
    private string $histoire;
    private string $atout;
    private string $themes;

    private string $artiste;
    private string $personnage_phare;
    private string $interet;
    private string $protection_batiment;
    private string $protection_espace;

    private string $refmer;
    private string $annee_creation;
    private string $date_de_mise_a_jour;
    private CoordonneesDto $coordonnees;


    public function __construct() {
        $this->identifiant = '';
        $this->nomOfficiel = '';
        $this->adresse = '';
        $this->lieu = '';
        $this->codePostal = '';

        $this->ville = '';
        $this->region = '';
        $this->departement = '';
        $this->url = '';
        $this->telephone = '';

        $this->categorie = '';
        $this->domaine_thematique = [];
        $this->histoire = '';
        $this->atout = '';
        $this->themes = '';

        $this->artiste = '';
        $this->personnage_phare = '';
        $this->interet = '';
        $this->protection_batiment = '';
        $this->protection_espace = '';

        $this->refmer = '';
        $this->annee_creation = '';
        $this->date_de_mise_a_jour = '';
        $this->coordonnees = new CoordonneesDto();
    }
    public function hydrate(array $data) {
        $this->identifiant = isset($data['identifiant']) ? $data['identifiant'] : '';
        $this->nomOfficiel = isset($data['nom_officiel']) ? $data['nom_officiel'] : '';
        $this->adresse = isset($data['adresse']) ? $data['adresse'] : '';
        $this->lieu = isset($data['lieu']) ? $data['lieu'] : '';
        $this->codePostal = isset($data['code_postal']) ? $data['code_postal'] : '';

        $this->ville = isset($data['ville']) ? $data['ville'] : '';
        $this->region = isset($data['region']) ? $data['region'] : '';
        $this->departement = isset($data['departement']) ? $data['departement'] : '';
        $this->url = isset($data['url']) ? $data['url'] : '';
        $this->telephone = isset($data['telephone']) ? $data['telephone'] : '';

        $this->categorie = isset($data['categorie']) ? $data['categorie'] : '';
        if (isset($data['domaine_thematique']) && !empty($data['domaine_thematique'])) {
            foreach ($data['domaine_thematique'] as $domaine) {
                $this->domaine_thematique[] = $domaine;
            }
        }
        else {
            $this->domaine_thematique = [];
        }
        $this->histoire = isset($data['histoire']) ? $data['histoire'] : '';
        $this->atout = isset($data['atout']) ? $data['atout'] : '';
        $this->themes = isset($data['themes']) ? $data['themes'] : '';

        $this->artiste = isset($data['artiste']) ? $data['artiste'] : '';
        $this->personnage_phare = isset($data['personnage_phare']) ? $data['personnage_phare'] : '';
        $this->interet = isset($data['interet']) ? $data['interet'] : '';
        $this->protection_batiment = isset($data['protection_batiment']) ? $data['protection_batiment'] : '';
        $this->protection_espace = isset($data['protection_espace']) ? $data['protection_espace'] : '';

        $this->refmer = isset($data['refmer']) ? $data['refmer'] : '';
        $this->annee_creation = isset($data['annee_creation']) ? $data['annee_creation'] : '';
        $this->date_de_mise_a_jour = isset($data['date_de_mise_a_jour']) ? $data['date_de_mise_a_jour'] : '';
        if(isset($data['coordonnees'])) {
            $this->coordonnees = new CoordonneesDto();
            $this->coordonnees->hydrate($data['coordonnees']);
        }
        else {
            $this->coordonnees = new CoordonneesDto();
        }
    }


    public function serialize(): array
    {
        $domaine_thematique = [];
        foreach ($this->domaine_thematique as $domaine) {
            $domaine_thematique[] = $domaine;
        }
        return [
            'identifiant' => $this->identifiant,
            'nomOfficiel' => $this->nomOfficiel,
            'adresse' => $this->adresse,
            'lieu' => $this->lieu,
            'codePostal' => $this->codePostal,

            'ville' => $this->ville,
            'region' => $this->region,
            'departement' => $this->departement,
            'url' => $this->url,
            'telephone' => $this->telephone,

            'categorie' => $this->categorie,
            'domaine_thematique' => $domaine_thematique,
            'histoire' => $this->histoire,
            'atout' => $this->atout,
            'themes' => $this->themes,

            'artiste' => $this->artiste,
            'personnage_phare' => $this->personnage_phare,
            'interet' => $this->interet,
            'protection_batiment' => $this->protection_batiment,
            'protection_espace' => $this->protection_espace,

            'refmer' => $this->refmer,
            'annee_creation' => $this->annee_creation,
            'date_de_mise_a_jour' => $this->date_de_mise_a_jour,
            'coordonnees' => $this->coordonnees->serialize()
        ];
    }
}


/*

"total_count": 4,
    "results": [
        {
            "identifiant": "M0477",
            "nom_officiel": "musée de l’hôtel d'Espeyran",
            "adresse": "6 bis rue Montpelliéret",
            "lieu": null,
            "code_postal": "34000",
            "ville": "Montpellier",
            "region": "Occitanie",
            "departement": "Hérault",
            "url": "www.montpellier3m.fr/equipement/musee-des-arts-decoratifs-sabatier-despeyran",
            "telephone": "04 67 66 83 00",
            "categorie": "Maison des Illustres.",
            "domaine_thematique": [
                "Arts décoratifs"
            ],
            "histoire": "Le bâtiment et les collections qu'il contient sont légués à la ville de Montpellier en 1967 par Renée de Cabrières, dernière descendante des Sabatier d' Espeyran et petite-nièce du Cardinal de Cabrières. L'hôtel Sabatier d'Espeyran a été transféré à la Communauté d'Agglomération de Montpellier le 1er janvier 2003. Montpellier Agglomération a programmé un chantier mobilisant 3,6 M€, pour la restauration et la réhabilitation de ce lieu historique, appelé à devenir le futur département des arts décoratifs du Musée Fabre. Cette demeure renfermera ainsi un ensemble de pièces d'apparat reconstituant l'atmosphère des XVIIIe et XIXe siècles.",
            "atout": "Dans le prolongement de la modernisation et de l'extension du musée Fabre, l'hôtel de Cabrières-Sabatier d'Espeyran accueille le département des arts décoratifs du musée. Cette demeure historique permet de découvrir les cadres de vie des sociétés bourgeoises et aristocratiques des XVIIIe et XIXe siècles. Dans les décors entièrement restaurés de ses salons, l'hôtel dévoile une collection de mobilier remarquable ainsi qu'un fonds de céramiques et de pièces d'orfèvrerie. L'ensemble de la collection est installé dans ces intérieurs fidèlement reconstitués.",
            "themes": "Mobilier, Orfèvrerie;Beaux-Arts : Peinture",
            "artiste": null,
            "personnage_phare": null,
            "interet": "Hôtel particulier construit en 1874-1875 pour le comte Charles Despous de Paul, membre de la haute société montpelliéraine.",
            "protection_batiment": null,
            "protection_espace": "Site patrimonial remarquable.",
            "refmer": "MI184;SPR7600054;SPR7600055;SPR7600056;SPR7600053",
            "annee_creation": null,
            "date_de_mise_a_jour": "2023-04-12",
            "coordonnees": {
                "lon": 3.880183,
                "lat": 43.611112
            }
        }
    ]

*/