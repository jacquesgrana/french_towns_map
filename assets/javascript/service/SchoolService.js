class SchoolService {

    limit = 10;
    offset = 0;
    totalCount = 0;
    order_by = 'nom_etablissement';
    order_by_type = 'ASC';
    filters = '';

    filter_without = true;
    filter_restauration = false;
    filter_hebergement = false;
    filter_maternelle = false;
    filter_elementaire = false;
    filter_voie_generale = false;
    filter_voie_technologique = false;
    filter_voie_professionnelle = false;
    filter_apprentissage = false;
    filter_segpa = false;
    filter_section_arts = false;
    filter_section_cinema = false;
    filter_section_theatre = false;
    filter_section_internationale = false;
    filter_section_europeenne = false;

    static instance = null;
    static getInstance() {
        if (SchoolService.instance == null) {
            SchoolService.instance = new SchoolService();
        }
        return SchoolService.instance;
    }

    async getSchoolsByTown(townCode) {
        // /get-schools-by-town-from-api
        const result = await fetch('/get-schools-by-town-from-api', {
            credentials: 'include',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                townCode: townCode,
                limit: this.limit,
                offset: this.offset,
                order_by: this.order_by,
                order_by_type: this.order_by_type,
                filters: this.filters
            })
        });
        return await result.json();
    } 

    generateFilters() {
        let filters = '';
        if(!this.filter_without) {
            if(this.filter_restauration) {
                filters += ':restauration';
            }
            if(this.filter_hebergement) {
                filters += ':hebergement';
            }
            if(this.filter_maternelle) {
                filters += ':ecole_maternelle';
            }
            if(this.filter_elementaire) {
                filters += ':ecole_elementaire';
            }
            if(this.filter_voie_generale) {
                filters += ':voie_generale';
            }
            if(this.filter_voie_technologique) {
                filters += ':voie_technologique';
            }
            if(this.filter_voie_professionnelle) {
                filters += ':voie_professionnelle';
            }
            if(this.filter_apprentissage) {
                filters += ':apprentissage';
            }
            if(this.filter_segpa) {
                filters += ':segpa';
            }
            if(this.filter_section_arts) {
                filters += ':section_arts';
            }
            if(this.filter_section_cinema) {
                filters += ':section_cinema';
            }
            if(this.filter_section_theatre) {
                filters += ':section_theatre';
            }
            if(this.filter_section_internationale) {
                filters += ':section_internationale';
            }
            if(this.filter_section_europeenne) {
                filters += ':section_europeenne';
            }
        } 
        if(filters !== '') {
            filters = filters.charAt(0) === ':' ? filters.slice(1) : filters;
        } 
        this.filters = filters;
    }

    updateFilters() {
        if(
            !this.filter_restauration 
            && !this.filter_hebergement 
            && !this.filter_maternelle
            && !this.filter_elementaire
            && !this.filter_voie_generale
            && !this.filter_voie_technologique
            && !this.filter_voie_professionnelle
            && !this.filter_apprentissage
            && !this.filter_segpa
            && !this.filter_section_arts
            && !this.filter_section_cinema
            && !this.filter_section_theatre
            && !this.filter_section_internationale
            && !this.filter_section_europeenne
        ) {
            this.filter_without = true;
        }
    }

    getLimit() {
        return this.limit;
    }

    getOffset() {
        return this.offset;
    }

    getTotalCount() {
        return this.totalCount;
    }

    getOrder_by() {
        return this.order_by;
    }

    getOrder_by_type() {
        return this.order_by_type;
    }

    getFilters() {
        return this.filters;
    }

    getFilter_without() {
        return this.filter_without;
    }

    getFilter_restauration() {
        return this.filter_restauration;
    }

    getFilter_hebergement() {
        return this.filter_hebergement;
    }

    getFilter_maternelle() {
        return this.filter_maternelle;
    }

    getFilter_elementaire() {
        return this.filter_elementaire;
    }

    getFilter_voie_generale() {
        return this.filter_voie_generale;
    }

    getFilter_voie_technologique() {
        return this.filter_voie_technologique;
    }

    getFilter_voie_professionnelle() {
        return this.filter_voie_professionnelle;
    }

    getFilter_apprentissage() {
        return this.filter_apprentissage;
    }

    getFilter_segpa() {
        return this.filter_segpa;
    }

    getFilter_section_arts() {
        return this.filter_section_arts;
    }

    getFilter_section_cinema() {
        return this.filter_section_cinema;
    }

    getFilter_section_theatre() {
        return this.filter_section_theatre;
    }

    getFilter_section_internationale() {
        return this.filter_section_internationale;
    }

    getFilter_section_europeenne() {
        return this.filter_section_europeenne;
    }

    /*

    setFilter_without(filter_without) {
        this.filter_without = filter_without;
    }

    setFilter_restauration(filter_restauration) {
        this.filter_restauration = filter_restauration;
    }

    setFilter_hebergement(filter_hebergement) {
        this.filter_hebergement = filter_hebergement;
    }*/

    setOrder_by(order_by) {
        this.order_by = order_by;
    }

    setOrder_by_type(order_by_type) {
        this.order_by_type = order_by_type;
    }

    setLimit(limit) {
        this.limit = limit > 100 ? 100 : limit < 1 ? 1 : limit;
    }

    setOffset(offset) {
        this.offset = offset < 0 ? 0 : offset;
    }

    setTotalCount(totalCount) {
        this.totalCount = totalCount;
    }

    setFilters(filters) {
        this.filters = filters;
    }

    setFilter_without(filter_without) {
        this.filter_without = filter_without;
        if(this.filter_without) {
            this.filters = '';
            this.filter_restauration = false;
            this.filter_hebergement = false;
            this.filter_maternelle = false;
            this.filter_elementaire = false;
            this.filter_voie_generale = false;
            this.filter_voie_technologique = false;
            this.filter_voie_professionnelle = false;
            this.filter_apprentissage = false;
            this.filter_segpa = false;
            this.filter_section_arts = false;
            this.filter_section_cinema = false;
            this.filter_section_theatre = false;
            this.filter_section_internationale = false;
            this.filter_section_europeenne = false;
        }
    }

    setFilter_restauration(filter_restauration) {
        this.filter_restauration = filter_restauration;
        /*
        if(filter_restauration) {
            this.filter_without = false;
        }*/
    }

    setFilter_hebergement(filter_hebergement) {
        this.filter_hebergement = filter_hebergement;
        /*
        if(filter_hebergement) {
            this.filter_without = false;
        }*/
    }

    setFilter_maternelle(filter_maternelle) {
        this.filter_maternelle = filter_maternelle;
    }

    setFilter_elementaire(filter_elementaire) {
        this.filter_elementaire = filter_elementaire;
    }

    setFilter_voie_generale(filter_voie_generale) {
        this.filter_voie_generale = filter_voie_generale;
    }

    setFilter_voie_technologique(filter_voie_technologique) {
        this.filter_voie_technologique = filter_voie_technologique;
    }

    setFilter_voie_professionnelle(filter_voie_professionnelle) {
        this.filter_voie_professionnelle = filter_voie_professionnelle;
    }

    setFilter_apprentissage(filter_apprentissage) {
        this.filter_apprentissage = filter_apprentissage;
    }

    setFilter_segpa(filter_segpa) {
        this.filter_segpa = filter_segpa;
    }

    setFilter_section_arts(filter_section_arts) {
        this.filter_section_arts = filter_section_arts;
    }

    setFilter_section_cinema(filter_section_cinema) {
        this.filter_section_cinema = filter_section_cinema;
    }

    setFilter_section_theatre(filter_section_theatre) {
        this.filter_section_theatre = filter_section_theatre;
    }

    setFilter_section_internationale(filter_section_internationale) {
        this.filter_section_internationale = filter_section_internationale;
    }

    setFilter_section_europeenne(filter_section_europeenne) {
        this.filter_section_europeenne = filter_section_europeenne;
    }
}