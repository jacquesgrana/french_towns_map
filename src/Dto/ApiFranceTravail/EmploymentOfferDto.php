<?php

namespace App\Dto\ApiFranceTravail;

use App\Dto\ApiFranceTravail\LieuTravailDto;
use App\Dto\ApiFranceTravail\EntrepriseDto;
use App\Dto\ApiFranceTravail\SalaireDto;
use App\Dto\ApiFranceTravail\ContactDto;
use App\Dto\ApiFranceTravail\OrigineOffreDto;

class EmploymentOfferDto 
{
    private string $id;
    private string $intitule;
    private string $description;
    private string $dateCreation;
    private string $dateActualisation;
    private LieuTravailDto $lieuTravail;
    private string $romeCode;
    private string $romeLibelle;
    private string $appellationlibelle;
    private EntrepriseDto $entreprise;
    private string $typeContrat;
    private string $typeContratLibelle;
    private string $natureContrat;
    private string $experienceExige;
    private string $experienceLibelle;
    private array $formations;
    private SalaireDto $salaire;
    private string $dureeTravailLibelle;
    private string $dureeTravailLibelleConverti;
    private bool $alternance;
    private ContactDto $contact;
    private string $urlPostulation;
    private bool $accessibleTH;
    private string $qualificationCode;
    private string $qualificationLibelle;
    private string $codeNAF;
    private string $secteurActivite;
    private string $secteurActiviteLibelle;
    private OrigineOffreDto $origineOffre;
    private bool $offresManqueCandidats;


    public function __construct() {
    }

    public function hydrate(array $data) {
        $this->id = $data['id'];
        $this->intitule = isset($data['intitule']) ? $data['intitule'] : '';
        $this->description = isset($data['description']) ? $data['description'] : '';
        $this->dateCreation = isset($data['dateCreation']) ? $data['dateCreation'] : '';
        $this->dateActualisation = isset($data['dateActualisation']) ? $data['dateActualisation'] : '';
        if(isset($data['lieuTravail'])) {
            $this->lieuTravail = new LieuTravailDto();
            $this->lieuTravail->hydrate($data['lieuTravail']);
        }
        else {
            $this->lieuTravail = new LieuTravailDto();
        }
        $this->romeCode = isset($data['romeCode']) ? $data['romeCode'] : '';
        $this->romeLibelle = isset($data['romeLibelle']) ? $data['romeLibelle'] : '';
        $this->appellationlibelle = isset($data['appellationlibelle']) ? $data['appellationlibelle'] : '';
        if(isset($data['entreprise'])) {
            $this->entreprise = new EntrepriseDto();
            $this->entreprise->hydrate($data['entreprise']);
        }
        else {
            $this->entreprise = new EntrepriseDto();
        }
        $this->typeContrat = isset($data['typeContrat']) ? $data['typeContrat'] : '';
        $this->typeContratLibelle = isset($data['typeContratLibelle']) ? $data['typeContratLibelle'] : '';
        $this->natureContrat = isset($data['natureContrat']) ? $data['natureContrat'] : '';
        $this->experienceExige = isset($data['experienceExige']) ? $data['experienceExige'] : '';
        $this->experienceLibelle = isset($data['experienceLibelle']) ? $data['experienceLibelle'] : '';
        if (isset($data['formations'])) {
            foreach ($data['formations'] as $formation) {
                $newFormation = new FormationDto();
                $newFormation->hydrate($formation);
                $this->formations[] = $newFormation;
            }
        } 
        else {
            $this->formations = [];
        }
        if(isset($data['salaire'])) {
            $this->salaire = new SalaireDto();
            $this->salaire->hydrate($data['salaire']);
        }
        else {
            $this->salaire = [];
        }
        $this->dureeTravailLibelle = isset($data['dureeTravailLibelle']) ? $data['dureeTravailLibelle'] : '';
        $this->dureeTravailLibelleConverti = isset($data['dureeTravailLibelleConverti']) ? $data['dureeTravailLibelleConverti'] : '';
        $this->alternance = isset($data['alternance']) ? $data['alternance'] : '';
        if(isset($data['contact'])) {
            $this->contact = new ContactDto();
            $this->contact->hydrate($data['contact']);
        }
        else {
            $this->contact = new ContactDto();
        }
        $this->urlPostulation = isset($data['urlPostulation']) ? $data['urlPostulation'] : '';
        $this->accessibleTH = isset($data['accessibleTH']) ? $data['accessibleTH'] : '';
        $this->qualificationCode = isset($data['qualificationCode']) ? $data['qualificationCode'] : '';
        $this->qualificationLibelle = isset($data['qualificationLibelle']) ? $data['qualificationLibelle'] : '';
        $this->codeNAF = isset($data['codeNAF']) ? $data['codeNAF'] : '';
        $this->secteurActivite = isset($data['secteurActivite']) ? $data['secteurActivite'] : '';
        $this->secteurActiviteLibelle = isset($data['secteurActiviteLibelle']) ? $data['secteurActiviteLibelle'] : '';
        if(isset($data['origineOffre'])) {
            $this->origineOffre = new OrigineOffreDto();
            $this->origineOffre->hydrate($data['origineOffre']);
        }
        else {
            $this->origineOffre = new OrigineOffreDto();
        }
        $this->offresManqueCandidats = isset($data['offresManqueCandidats']) ? $data['offresManqueCandidats'] : '';
    }

    public function serialize() {
        $formations = [];
        foreach ($this->formations as $formation) {
            $formations[] = $formation->serialize();
        }
        return [
            'id' => $this->id,
            'intitule' => $this->intitule,
            'description' => $this->description,
            'dateCreation' => $this->dateCreation,
            'dateActualisation' => $this->dateActualisation,
            'lieuTravail' => $this->lieuTravail->serialize(),
            'romeCode' => $this->romeCode,
            'romeLibelle' => $this->romeLibelle,
            'appellationlibelle' => $this->appellationlibelle,
            'entreprise' => $this->entreprise->serialize(),
            'typeContrat' => $this->typeContrat,
            'typeContratLibelle' => $this->typeContratLibelle,
            'natureContrat' => $this->natureContrat,
            'experienceExige' => $this->experienceExige,
            'experienceLibelle' => $this->experienceLibelle,
            'formations' => $formations,
            'salaire' => $this->salaire->serialize(),
            'dureeTravailLibelle' => $this->dureeTravailLibelle,
            'dureeTravailLibelleConverti' => $this->dureeTravailLibelleConverti,
            'alternance' => $this->alternance,
            'contact' => $this->contact->serialize(),
            'urlPostulation' => $this->urlPostulation,
            'accessibleTH' => $this->accessibleTH,
            'qualificationCode' => $this->qualificationCode,
            'qualificationLibelle' => $this->qualificationLibelle,
            'codeNAF' => $this->codeNAF,
            'secteurActivite' => $this->secteurActivite,
            'secteurActiviteLibelle' => $this->secteurActiviteLibelle,
            'origineOffre' => $this->origineOffre->serialize(),
            'offresManqueCandidats' => $this->offresManqueCandidats
        ];
    }
}

/*

    {
    "id": "180HSFM",
    "intitule": "Gestionnaire back office (banque) (F/H)",
    "description": "Au sein de la Direction Crédits, plusieurs postes sont à pourvoir sur les services identification, étude, synthèse, téléconseil et avenant.\nEn fonction du service auquel vous serez affecté, vous serez chargé de l'analyse de la demande de crédit, la complétude des dossiers, la recherche d'informations, synthèse de données, rédaction des avenants, et rédaction des contrats.\nVous assurez la gestion et le traitement administratif des opérations de versements de fonds sur les crédits immobiliers.\nVous analysez et contrôlez les opérations comptables (de débit/crédit).\nSur le service conseil à distance, vos missions seront la réception des appels entrants, le traitement des demandes clients, être force de proposition, faire preuve de rebond commercial, conseiller et accompagner les clients.",
    "dateCreation": "2024-08-30T16:52:28.632Z",
    "dateActualisation": "2024-08-30T16:52:28.908Z",
    "lieuTravail": {
        "libelle": "34 - Montpellier",
        "latitude": 43.610476,
        "longitude": 3.87048,
        "codePostal": "34000",
        "commune": "34172"
    },
    "romeCode": "C1302",
    "romeLibelle": "Gestionnaire des opérations sur les marchés financiers",
    "appellationlibelle": "Agent / Agente de back-office",
    "entreprise": {
        "nom": "RANDSTAD ind.tert.transp. infor.",
        "description": "Randstad vous ouvre toutes les portes de l'emploi : intérim, CDD, CDI. Chaque année, 330 000 collaborateurs (f/h) travaillent dans nos 60 000 entreprises clientes. Rejoignez-nous !\nVotre agence, Pôle d'expertise Banque et Assurance, vous offre de nombreuses opportunités d'emploi et vous accompagne tout au long de votre carrière.",
        "logo": "https://entreprise.francetravail.fr/static/img/logos/ypH4zaqCb8bUGYXRBihFYscwGubIpsOO.png",
        "entrepriseAdaptee": false
    },
    "typeContrat": "MIS",
    "typeContratLibelle": "Intérim - 2 Mois",
    "natureContrat": "Contrat travail",
    "experienceExige": "S",
    "experienceLibelle": "6 Mois",
    "formations": [
        {
            "codeFormation": "41062",
            "domaineLibelle": "banque",
            "niveauLibelle": "Bac ou équivalent",
            "exigence": "S"
        }
    ],
    "salaire": {
        "libelle": "Horaire de 12.06 Euros sur 12.0 mois"
    },
    "dureeTravailLibelle": "38H",
    "dureeTravailLibelleConverti": "Temps plein",
    "alternance": false,
    "contact": {
        "nom": "RANDSTAD ind.tert.transp. infor. - M. Valentin SIMON",
        "coordonnees1": "https://www.randstad.fr/offre/001-TKV-0000857_02C/A?utm_source=pole-emploi&utm_medium=jobboard&utm_campaign=offres",
        "urlPostulation": "https://www.randstad.fr/offre/001-TKV-0000857_02C/A?utm_source=pole-emploi&utm_medium=jobboard&utm_campaign=offres"
    },
    "accessibleTH": false,
    "qualificationCode": "7",
    "qualificationLibelle": "Technicien",
    "codeNAF": "78.20Z",
    "secteurActivite": "78",
    "secteurActiviteLibelle": "Activités des agences de travail temporaire",
    "origineOffre": {
        "origine": "1",
        "urlOrigine": "https://candidat.francetravail.fr/offres/recherche/detail/180HSFM"
    },
    "offresManqueCandidats": false
}

par requete sur id :
{
    "id": "179PPHH",
    "intitule": "Gestionnaire de stock",
    "description": "Notre agence Adéquat de Montpellier recrute pour son client, un gestionnaire de stock (F/H)\nMissions : \n- Suivre et gérer le stock\n- Mise à jour de l'outil informatique\n- Assurer la propreté, sécurité et la qualité du suivi de stock \n\nProfil : \n- Formation BEP, CAP, BAC en logistique ou expérience similaire sur même poste\n- Connaissance des logiciels de stock\n- Expérience confirmée en gestion de stock \nRémunération et avantages :\n- Taux horaire fixe de 13.73 + 10% de fin de mission + 10% de congés payés ;\n- Primes collective et/ou individuelle + participation aux bénéfices + CET 5% ;\n- Possibilité d'intégration rapide, de formation et d'évolution ;\n- Bénéficier d'aides et de services dédiés (mutuelle, logement, garde enfant, déplacement...).\n\nPour toutes questions, vous pouvez nous joindre au ########## ",
    "dateCreation": "2024-08-19T11:01:06.407Z",
    "dateActualisation": "2024-09-04T22:45:54.742Z",
    "lieuTravail": {
        "libelle": "34 - Saint-Aunès",
        "latitude": 43.63586,
        "longitude": 3.962108,
        "codePostal": "34130",
        "commune": "34240"
    },
    "romeCode": "N1103",
    "romeLibelle": "Préparateur / Préparatrice de commandes",
    "appellationlibelle": "Agent / Agente logistique en magasinage",
    "entreprise": {
        "nom": "ADEQUAT INTERIM",
        "logo": "https://entreprise.francetravail.fr/static/img/logos/0VjbZ5tftY9ngnun66nzbaN0WtNGkLUr.png",
        "entrepriseAdaptee": false
    },
    "typeContrat": "MIS",
    "typeContratLibelle": "Intérim - 6 Mois",
    "natureContrat": "Contrat travail",
    "experienceExige": "S",
    "experienceLibelle": "1 An(s)",
    "salaire": {
        "libelle": "Mensuel de 1833.0 Euros à 2250.0 Euros sur 12.0 mois"
    },
    "dureeTravailLibelle": "35H",
    "dureeTravailLibelleConverti": "Temps plein",
    "alternance": false,
    "contact": {
        "nom": "ADEQUAT INTERIM - Mme Nelly BLAYA",
        "coordonnees1": "https://www.lejobadequat.com/?job=251887&origine=POLEEMPLOI-API&utm_campaign=pole-emploi&utm_medium=organic&utm_source=pole-emploi&utm_term=064",
        "urlPostulation": "https://www.lejobadequat.com/?job=251887&origine=POLEEMPLOI-API&utm_campaign=pole-emploi&utm_medium=organic&utm_source=pole-emploi&utm_term=064"
    },
    "agence": {},
    "accessibleTH": false,
    "qualificationCode": "5",
    "qualificationLibelle": "Employé non qualifié",
    "codeNAF": "78.20Z",
    "secteurActivite": "78",
    "secteurActiviteLibelle": "Activités des agences de travail temporaire",
    "origineOffre": {
        "origine": "1",
        "urlOrigine": "https://candidat.francetravail.fr/offres/recherche/detail/179PPHH"
    },
    "offresManqueCandidats": true
}

*/