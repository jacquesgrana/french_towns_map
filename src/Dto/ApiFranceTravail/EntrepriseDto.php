<?php

namespace App\Dto\ApiFranceTravail;

class EntrepriseDto
{
    private string $nom;
    private string $description;
    private string $logo;
    private bool $entrepriseAdaptee;

    public function __construct() {
    }

    public function hydrate(array $data) {
        $this->nom = isset($data['nom']) ? $data['nom'] : '';
        $this->description = isset($data['description']) ? $data['description'] : '';
        $this->logo = isset($data['logo']) ? $data['logo'] : '';
        $this->entrepriseAdaptee = isset($data['entrepriseAdaptee']) ? $data['entrepriseAdaptee'] : '';
    }

    public function serialize() {
        return [
            'nom' => $this->nom,
            'description' => $this->description,
            'logo' => $this->logo,
            'entrepriseAdaptee' => $this->entrepriseAdaptee
        ];
    }

    public function getNom() {
        return $this->nom;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getLogo() {
        return $this->logo;
    }

    public function getEntrepriseAdaptee() {
        return $this->entrepriseAdaptee;
    }

    public function setNom($nom) {
        $this->nom = $nom;
        return $this;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function setLogo($logo) {
        $this->logo = $logo;
        return $this;
    }

    public function setEntrepriseAdaptee($entrepriseAdaptee) {
        $this->entrepriseAdaptee = $entrepriseAdaptee;
        return $this;
    }
}


/*

"entreprise": {
                "nom": "RANDSTAD ind.tert.transp. infor.",
                "description": "Randstad vous ouvre toutes les portes de l'emploi : intérim, CDD, CDI. Chaque année, 330 000 collaborateurs (f/h) travaillent dans nos 60 000 entreprises clientes. Rejoignez-nous !\nVotre agence, Pôle d'expertise Banque et Assurance, vous offre de nombreuses opportunités d'emploi et vous accompagne tout au long de votre carrière.",
                "logo": "https://entreprise.francetravail.fr/static/img/logos/ypH4zaqCb8bUGYXRBihFYscwGubIpsOO.png",
                "entrepriseAdaptee": false
            },

*/