<?php

namespace App\Dto\ApiFranceTravail;

use App\Dto\ApiFranceTravail\LieuTravailDto;
use App\Dto\ApiFranceTravail\EntrepriseDto;
use App\Dto\ApiFranceTravail\SalaireDto;
use App\Dto\ApiFranceTravail\ContactDto;
use App\Dto\ApiFranceTravail\AgenceDto;
use App\Dto\ApiFranceTravail\OrigineOffreDto;

use App\Dto\ApiFranceTravail\FormationDto;
use App\Dto\ApiFranceTravail\LangueDto;
use App\Dto\ApiFranceTravail\PermiDto;
use App\Dto\ApiFranceTravail\CompetenceDto;
use App\Dto\ApiFranceTravail\QualiteProfessionnelleDto;
use App\Dto\ApiFranceTravail\PartenaireDto;


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
    private string $experienceCommentaire;
    private array $formations;

    private array $langues;
    private array $permis;
    private array $outilsBureautiques;
    private array $competences;

    private SalaireDto $salaire;
    private string $dureeTravailLibelle;
    private string $dureeTravailLibelleConverti;

    private string $complementExercice;
    private string $conditionExercice;


    private bool $alternance;
    private ContactDto $contact;

    private AgenceDto $agence;
    private int $nombrePostes;
    //private string $urlPostulation;

    private bool $accessibleTH;
    private string $deplacementCode;
    private string $deplacementLibelle;

    private string $qualificationCode;
    private string $qualificationLibelle;
    private string $codeNAF;
    private string $secteurActivite;
    private string $secteurActiviteLibelle;

    private array $qualitesProfessionnelles;
    private string $trancheEffectifEtab;

    private OrigineOffreDto $origineOffre;
    private array $partenaires;
    private bool $offresManqueCandidats;


    public function __construct() {
        $this->id = '';
        $this->intitule = '';
        $this->description = '';
        $this->dateCreation = '';
        $this->dateActualisation = '';
        $this->lieuTravail = new LieuTravailDto();
        $this->romeCode = '';
        $this->romeLibelle = '';
        $this->appellationlibelle = '';
        $this->entreprise = new EntrepriseDto();
        $this->typeContrat = '';
        $this->typeContratLibelle = '';
        $this->natureContrat = '';
        $this->experienceExige = '';
        $this->experienceLibelle = '';
        $this->experienceCommentaire = '';
        $this->formations = [];
        $this->langues = [];
        $this->permis = [];
        $this->outilsBureautiques = [];
        $this->salaire = new SalaireDto();
        $this->dureeTravailLibelle = '';
        $this->dureeTravailLibelleConverti = '';
        $this->complementExercice = '';
        $this->conditionExercice = '';
        $this->alternance = false;
        $this->contact = new ContactDto();
        $this->agence = new AgenceDto();
        $this->nombrePostes = 0;
        //$this->urlPostulation = '';
        $this->accessibleTH = false;
        $this->deplacementCode = '';
        $this->deplacementLibelle = '';
        $this->qualificationCode = '';
        $this->qualificationLibelle = '';
        $this->codeNAF = '';
        $this->secteurActivite = '';
        $this->secteurActiviteLibelle = '';
        $this->qualitesProfessionnelles = [];
        $this->trancheEffectifEtab = '';
        $this->origineOffre = new OrigineOffreDto();
        $this->partenaires = [];
        $this->offresManqueCandidats = false;
    }

    public function hydrate(array $data) {
        $this->id = isset($data['id']) ? $data['id'] : '';
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
        $this->experienceCommentaire = isset($data['experienceCommentaire']) ? $data['experienceCommentaire'] : '';
        if (isset($data['formations']) && !empty($data['formations'])) {
            foreach ($data['formations'] as $formation) {
                $newFormation = new FormationDto();
                $newFormation->hydrate($formation);
                $this->formations[] = $newFormation;
            }
        } 
        else {
            $this->formations = [];
        }
        if (isset($data['langues']) && !empty($data['langues'])) {
            foreach ($data['langues'] as $langue) {
                $newLangue = new LangueDto();
                $newLangue->hydrate($langue);
                $this->langues[] = $newLangue;
            }
        } 
        else {
            $this->langues = [];
        }

        if (isset($data['permis']) && !empty($data['permis'])) {
            foreach ($data['permis'] as $permis) {
                $newPermis = new PermiDto();
                $newPermis->hydrate($permis);
                $this->permis[] = $newPermis;
            }
        } 
        else {
            $this->permis = [];
        }

        if (isset($data['outilsBureautiques']) && !empty($data['outilsBureautiques'])) {
            foreach ($data['outilsBureautiques'] as $outilBureautique) {
                $this->outilsBureautiques[] = $outilBureautique;
            }
        }
        else {
            $this->outilsBureautiques = [];
        }
        if(isset($data['competences']) && !empty($data['competences'])) {
            foreach ($data['competences'] as $competence) {
                $newCompetence = new CompetenceDto();
                $newCompetence->hydrate($competence);
                $this->competences[] = $newCompetence;
            }
        }
        else {
            $this->competences = [];
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
        $this->complementExercice = isset($data['complementExercice']) ? $data['complementExercice'] : '';
        $this->conditionExercice = isset($data['conditionExercice']) ? $data['conditionExercice'] : '';
        $this->alternance = isset($data['alternance']) ? $data['alternance'] : '';
        if(isset($data['contact'])) {
            $this->contact = new ContactDto();
            $this->contact->hydrate($data['contact']);
        }
        else {
            $this->contact = new ContactDto();
        }
        //$this->urlPostulation = isset($data['urlPostulation']) ? $data['urlPostulation'] : '';


        if(isset($data['agence'])) {
            $this->agence = new AgenceDto();
            $this->agence->hydrate($data['agence']);
        }
        else {
            $this->agence = new AgenceDto();
        }

        $this->nombrePostes = isset($data['nombrePostes']) ? $data['nombrePostes'] : 0;

        $this->accessibleTH = isset($data['accessibleTH']) ? $data['accessibleTH'] : '';

        $this->deplacementCode = isset($data['deplacementCode']) ? $data['deplacementCode'] : '';
        $this->deplacementLibelle = isset($data['deplacementLibelle']) ? $data['deplacementLibelle'] : '';

        $this->qualificationCode = isset($data['qualificationCode']) ? $data['qualificationCode'] : '';
        $this->qualificationLibelle = isset($data['qualificationLibelle']) ? $data['qualificationLibelle'] : '';
        $this->codeNAF = isset($data['codeNAF']) ? $data['codeNAF'] : '';
        $this->secteurActivite = isset($data['secteurActivite']) ? $data['secteurActivite'] : '';
        $this->secteurActiviteLibelle = isset($data['secteurActiviteLibelle']) ? $data['secteurActiviteLibelle'] : '';

        //$this->qualitesProfessionnelles

        if(isset($data['qualitesProfessionnelles']) && !empty($data['qualitesProfessionnelles'])) {
            foreach ($data['qualitesProfessionnelles'] as $qualiteProfessionnelle) {
                $newQualiteProfessionnelle = new QualiteProfessionnelleDto();
                $newQualiteProfessionnelle->hydrate($qualiteProfessionnelle);
                $this->qualitesProfessionnelles[] = $newQualiteProfessionnelle;
            }
        }
        else {
            $this->qualitesProfessionnelles = [];
        }

        $this->trancheEffectifEtab = isset($data['trancheEffectifEtab']) ? $data['trancheEffectifEtab'] : '';

        if(isset($data['origineOffre'])) {
            $this->origineOffre = new OrigineOffreDto();
            $this->origineOffre->hydrate($data['origineOffre']);
        }
        else {
            $this->origineOffre = new OrigineOffreDto();
        }
        if(isset($data['partenaires']) && !empty($data['partenaires'])) {
            foreach ($data['partenaires'] as $partenaire) {
                $newPartenaire = new PartenaireDto();
                $newPartenaire->hydrate($partenaire);
                $this->partenaires[] = $newPartenaire;
            }
        }
        else {
            $this->partenaires = [];
        }

        $this->offresManqueCandidats = isset($data['offresManqueCandidats']) ? $data['offresManqueCandidats'] : '';
    }

    public function serialize() {

        $formations = [];
        foreach ($this->formations as $formation) {
            $formations[] = $formation->serialize();
        }

        $langues = [];
        foreach ($this->langues as $langue) {
            $langues[] = $langue->serialize();
        }

        $permis = [];
        foreach ($this->permis as $permi) {
            $permis[] = $permi->serialize();
        }

        $competences = [];
        foreach ($this->competences as $competence) {
            $competences[] = $competence->serialize();
        }

        $qualites = [];
        foreach ($this->qualitesProfessionnelles as $qualite) {
            $qualites[] = $qualite->serialize();
        }

        $partenaires = [];
        foreach ($this->partenaires as $partenaire) {
            $partenaires[] = $partenaire->serialize();
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
            'experienceCommentaire' => $this->experienceCommentaire,
            'formations' => $formations,
            'langues' => $langues,
            'permis' => $permis,
            'outilsBureautiques' => $this->outilsBureautiques,
            'competences' => $competences,


            'salaire' => $this->salaire->serialize(),
            'dureeTravailLibelle' => $this->dureeTravailLibelle,
            'dureeTravailLibelleConverti' => $this->dureeTravailLibelleConverti,
            'complementExercice' => $this->complementExercice,
            'conditionExercice' => $this->conditionExercice,

            'alternance' => $this->alternance,
            'contact' => $this->contact->serialize(),
            //'urlPostulation' => $this->urlPostulation,

            'agence' => $this->agence->serialize(),
            'nombrePostes' => $this->nombrePostes,

            'accessibleTH' => $this->accessibleTH,
            'deplacementCode' => $this->deplacementCode,
            'deplacementLibelle' => $this->deplacementLibelle,

            'qualificationCode' => $this->qualificationCode,
            'qualificationLibelle' => $this->qualificationLibelle,
            'codeNAF' => $this->codeNAF,
            'secteurActivite' => $this->secteurActivite,
            'secteurActiviteLibelle' => $this->secteurActiviteLibelle,

            'qualitesProfessionnelles' => $qualites,
            'trancheEffectifEtab' => $this->trancheEffectifEtab,
            'origineOffre' => $this->origineOffre->serialize(),
            'partenaires' => $partenaires,

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