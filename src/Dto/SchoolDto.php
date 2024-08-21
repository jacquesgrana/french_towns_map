<?php

namespace App\Dto;


class SchoolDto
{
    // 46
    private string $identifiant_de_l_etablissement;
    private string | null $nom_etablissement;
    private string | null $type_etablissement;
    private string | null $statut_public_prive;
    private string | null $adresse_1;
    private string | null $adresse_2;
    private string | null $adresse_3;
    //private string $code_postal;
    //private string $code_commune;
    //private string $nom_commune;
    //private string $code_departement;
    //private string $code_academie;
    //private string $code_region;
    private string | null $ecole_maternelle;
    private string | null $ecole_elementaire;
    private string | null $voie_generale;
    private string | null $voie_technologique;
    private string | null $voie_professionnelle;
    private string | null $telephone;
    private string | null $fax;
    private string | null $web;
    private string | null $mail;
    private string | null $restauration;
    private string | null $hebergement;
    //private string $ulis;
    private string | null $apprentissage;
    private string | null $segpa;
    private string | null $section_arts;
    private string | null $section_cinema;
    private string | null $section_theatre;
    private string | null $section_internationale;
    private string | null $section_europeenne;
    private string | null $lycee_agricole;
    private string | null $lycee_militaire;
    private string | null $lycee_des_metiers;
    private string | null $post_bac;
    private string | null $appartenance_education_prioritaire;
    private string | null $greta;
    private string | null $siren_siret;
    private int | null $nombre_d_eleves;
    private string | null $fiche_onisep;
    //private string $position;
    private string | null $type_contrat_prive;
    //private string $libelle_departement;
    private string $libelle_academie;
    //private string $libelle_region;
    //private string $coordx_origine;
    //private string $coordy_origine;
    //private string $epsg_origine;
    //private string $nom_circonscription;
    private float $latitude;
    private float $longitude;
    //private string $precision_localisation;
    private string $date_ouverture;
    private string $date_maj_ligne;
    private string | null $etat;
    private string | null $ministere_tutelle;
    //private string $multi_uai;
    //private string $rpi_concentre;
    //private string $rpi_disperse;
    private string $code_nature;
    private string $libelle_nature;
    private string | null $code_type_contrat_prive;
    //private string $pial;
    //private string $etablissement_mere;
    // private string $type_rattachement_etablissement_mere;
    //private string $code_circonscription;
    //private string $code_zone_animation_pedagogique;
    //private string $libelle_zone_animation_pedagogique;
    //private string $code_bassin_formation;
    //private string $libelle_bassin_formation;

    public function __construct() {
    }

    public function hydrate(array $data) {
        foreach ($data as $key => $value) {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function serialize() {
        return get_object_vars($this);
    }

    // setters

    public function setIdentifiant_de_l_etablissement(string $identifiant_de_l_etablissement): static {
        $this->identifiant_de_l_etablissement = $identifiant_de_l_etablissement;
        return $this;
    }

    public function setNom_etablissement(string | null $nom_etablissement): static {
        $this->nom_etablissement = $nom_etablissement ?? '';
        return $this;
    }

    public function setType_etablissement(string | null  $type_etablissement): static {
        $this->type_etablissement = $type_etablissement ?? '';
        return $this;
    }

    public function setStatut_public_prive(string | null  $statut_public_prive): static {
        $this->statut_public_prive = $statut_public_prive ?? '';
        return $this;
    }

    public function setAdresse_1(string | null $adresse_1): static {
        $this->adresse_1 = $adresse_1 ?? '';
        return $this;
    }

    public function setAdresse_2(string | null $adresse_2): static {
        $this->adresse_2 = $adresse_2 ?? '';
        return $this;
    }

    public function setAdresse_3(string | null $adresse_3): static {
        $this->adresse_3 = $adresse_3 ?? '';
        return $this;
    }

    public function setEcole_maternelle(string | null $ecole_maternelle): static {
        if($ecole_maternelle === null) {
            $ecole_maternelle = '0';
        }
        else {
            $this->ecole_maternelle = $ecole_maternelle;
        }
        return $this;
    }

    public function setEcole_elementaire(string | null  $ecole_elementaire): static {
        if($ecole_elementaire === null) {
            $ecole_elementaire = '0';
        }
        else {
            $this->ecole_elementaire = $ecole_elementaire;
        }
        return $this;
    }

    public function setVoie_generale(string | null $voie_generale): static {
        if($voie_generale === null) {
            $voie_generale = '0';
        }
        else {
            $this->voie_generale = $voie_generale;
        }
        return $this;
    }

    public function setVoie_technologique(string | null $voie_technologique): static {
        if($voie_technologique === null) {
            $voie_technologique = '0';
        }
        else {
            $this->voie_technologique = $voie_technologique;
        }
        return $this;
    }

    public function setVoie_professionnelle(string | null $voie_professionnelle): static {
        if($voie_professionnelle === null) {
            $voie_professionnelle = '0';
        }
        else {
            $this->voie_professionnelle = $voie_professionnelle;
        }
        return $this;
    }

    public function setTelephone(string | null $telephone): static {
        if($telephone === null) {
            $telephone = '';
        }
        else {
            $this->telephone = $telephone;
        }
        return $this;
    }

    public function setFax(string | null $fax): static {
        if($fax === null) {
            $fax = '';
        }
        else {
            $this->fax = $fax;
        }
        return $this;
    }

    public function setWeb(string | null $web): static {
        if($web === null) {
            $web = '';
        }
        else {
            $this->web = $web;
        }
        return $this;
    }

    public function setMail(string | null $mail): static {
        $this->mail = $mail === null ? '' : $mail;
        return $this;
    }

    public function setRestauration(string | null $restauration): static {
        $this->restauration = $restauration === null ? '0' : $restauration;
        return $this;
    }

    public function setHebergement(string | null $hebergement): static {
        $this->hebergement = $hebergement === null ? '0' : $hebergement;
        return $this;
    }

    public function setApprentissage(string | null $apprentissage): static {
        $this->apprentissage = $apprentissage === null ? '0' : $apprentissage;
        return $this;
    }

    public function setSegpa(string | null $segpa): static {
        $this->segpa = $segpa === null ? '0' : $segpa;
        return $this;
    }

    public function setSection_arts(string | null $section_arts): static {
        $this->section_arts = $section_arts === null ? '0' : $section_arts;
        return $this;
    }

    public function setSection_cinema(string | null $section_cinema): static {
        $this->section_cinema = $section_cinema === null ? '0' : $section_cinema;
        return $this;
    }

    public function setSection_theatre(string | null $section_theatre): static {
        $this->section_theatre = $section_theatre === null ? '0' : $section_theatre;
        return $this;
    }

    public function setSection_internationale(string | null $section_internationale): static {
        $this->section_internationale = $section_internationale === null ? '0' : $section_internationale;
        return $this;
    }

    public function setSection_europeenne(string | null $section_europeenne): static {
        $this->section_europeenne = $section_europeenne === null ? '0' : $section_europeenne;
        return $this;
    }

    public function setLycee_agricole(string | null $lycee_agricole): static {
        $this->lycee_agricole = $lycee_agricole === null ? '0' : $lycee_agricole;
        return $this;
    }

    public function setLycee_militaire(string | null $lycee_militaire): static {
        $this->lycee_militaire = $lycee_militaire === null ? '0' : $lycee_militaire;
        return $this;
    }

    public function setLycee_des_metiers(string | null $lycee_des_metiers): static {
        $this->lycee_des_metiers = $lycee_des_metiers === null ? '0' : $lycee_des_metiers;
        return $this;
    }

    public function setPost_bac(string | null $post_bac): static {
        $this->post_bac = $post_bac === null ? '0' : $post_bac;
        return $this;
    }

    public function setAppartenance_education_prioritaire(string | null $appartenance_education_prioritaire): static {
        $this->appartenance_education_prioritaire = $appartenance_education_prioritaire === null ? '0' : $appartenance_education_prioritaire;
        return $this;
    }

    public function setGreta(string | null $greta): static {
        $this->greta = $greta === null ? '0' : $greta;
        return $this;
    }

    public function setSiren_siret(string | null $siren_siret): static {
        $this->siren_siret = $siren_siret === null ? '' : $siren_siret;
        return $this;
    }

    public function setNombre_d_eleves(int | null $nombre_d_eleves): static {
        $this->nombre_d_eleves = $nombre_d_eleves === null ? -1 : $nombre_d_eleves;
        return $this;
    }

    public function setFiche_onisep(string | null $fiche_onisep): static {
        $this->fiche_onisep = $fiche_onisep === null ? '' : $fiche_onisep;
        return $this;
    }

    public function setType_contrat_prive(string | null $type_contrat_prive): static {
        $this->type_contrat_prive = $type_contrat_prive === null ? '' : $type_contrat_prive;
        return $this;
    }

    /*
    public function setLibelle_departement(string $libelle_departement): static {
        $this->libelle_departement = $libelle_departement;
        return $this;
    }
    */

    public function setLibelle_academie(string $libelle_academie): static {
        $this->libelle_academie = $libelle_academie;
        return $this;
    }

    public function setLatitude(float | null  $latitude): static {
        $this->latitude = $latitude ?? 0.0;
        return $this;
    }

    public function setLongitude(float | null  $longitude): static {
        $this->longitude = $longitude ?? 0.0;
        return $this;
    }

    public function setDate_ouvertude(string $date_ouverture): static {
        $this->date_ouverture = $date_ouverture;
        return $this;
    }

    public function setDate_maj_ligne(string $date_maj_ligne): static {
        $this->date_maj_ligne = $date_maj_ligne;
        return $this;
    }

    public function setEtat(string | null $etat): static {
        $this->etat = $etat;
        return $this;
    }

    public function setMinistere_tutelle(string | null $ministere_tutelle): static {
        $this->ministere_tutelle = $ministere_tutelle;
        return $this;
    }

    public function setCode_nature(string $code_nature): static {
        $this->code_nature = $code_nature;
        return $this;
    }

    public function setLibelle_nature(string $libelle_nature): static {
        $this->libelle_nature = $libelle_nature;
        return $this;
    }

    public function setCode_type_contrat_prive(string | null $code_type_contrat_prive): static {
        $this->code_type_contrat_prive = $code_type_contrat_prive;
        return $this;
    }
}

/*
{
            "identifiant_de_l_etablissement": "0770934X",
            "nom_etablissement": "Lycée polyvalent Léonard de Vinci",
            "type_etablissement": "Lycée",
            "statut_public_prive": "Public",
            "adresse_1": "2 bis rue Edouard Branly",
            "adresse_2": null,
            "adresse_3": null,
            "code_postal": "77000",
            "code_commune": "77288",
            "nom_commune": "Melun",
            "code_departement": "077",
            "code_academie": "24",
            "code_region": "11",
            "ecole_maternelle": null,
            "ecole_elementaire": null,
            "voie_generale": "1",
            "voie_technologique": "1",
            "voie_professionnelle": "1",
            "telephone": "01 60 56 60 60",
            "fax": "01 60 56 60 61",
            "web": "http://www.vinci-melun.org",
            "mail": "ce.0770934x@ac-creteil.fr",
            "restauration": 1,
            "hebergement": 1,
            "ulis": 0,
            "apprentissage": "1",
            "segpa": "0",
            "section_arts": "0",
            "section_cinema": "0",
            "section_theatre": "0",
            "section_sport": "1",
            "section_internationale": "0",
            "section_europeenne": "1",
            "lycee_agricole": "0",
            "lycee_militaire": "0",
            "lycee_des_metiers": "1",
            "post_bac": "1",
            "appartenance_education_prioritaire": null,
            "greta": "1",
            "siren_siret": "19770934800019",
            "nombre_d_eleves": 730,
            "fiche_onisep": "https://www.onisep.fr/http/redirection/etablissement/slug/ENS.8908",
            "position": {
                "lon": 2.659903143940967,
                "lat": 48.55108679994959
            },
            "type_contrat_prive": "SANS OBJET",
            "libelle_departement": "Seine-et-Marne",
            "libelle_academie": "Créteil",
            "libelle_region": "Ile-de-France",
            "coordx_origine": 674899.6,
            "coordy_origine": 6827927.3,
            "epsg_origine": "EPSG:2154",
            "nom_circonscription": null,
            "latitude": 48.55108679994959,
            "longitude": 2.659903143940967,
            "precision_localisation": "Numéro de rue",
            "date_ouverture": "1965-05-01",
            "date_maj_ligne": "2024-07-25",
            "etat": "OUVERT",
            "ministere_tutelle": "MINISTERE DE L'EDUCATION NATIONALE",
            "multi_uai": 0,
            "rpi_concentre": 0,
            "rpi_disperse": null,
            "code_nature": 306,
            "libelle_nature": "LYCEE POLYVALENT",
            "code_type_contrat_prive": "99",
            "pial": "0770033T",
            "etablissement_mere": null,
            "type_rattachement_etablissement_mere": null,
            "code_circonscription": null,
            "code_zone_animation_pedagogique": null,
            "libelle_zone_animation_pedagogique": null,
            "code_bassin_formation": "24773",
            "libelle_bassin_formation": "77 BASSIN 3 (DIST. 8, 9 ET 10)"
        }
*/