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
        } 
        if(filters !== '') {
            filters = filters.charAt(0) === ':' ? filters.slice(1) : filters;
        } 
        this.filters = filters;
    }

    updateFilters() {
        if(!this.filter_restauration && !this.filter_hebergement) {
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


}