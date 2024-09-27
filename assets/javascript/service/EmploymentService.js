class EmploymentService {

    offers = [];
    filters = [];
    totalCount = -1;
    sort = 2;

    static instance = null;
    static getInstance() {
        if (EmploymentService.instance == null) {
            EmploymentService.instance = new EmploymentService();
        }
        return EmploymentService.instance;
    }

    async getEmploymentOffersByTown(townCode) {
        const result = await fetch('/get-employment-by-town-from-api', {
            credentials: 'include',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                townCode: townCode
            })
        });
        return await result.json();
    }

    async getEmploymentOfferById(offerId) {
        const result = await fetch('/get-employment-offer-by-id', {
            credentials: 'include',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                offerId: offerId
            })
        });
        return await result.json();
    }

    calculateAndSetTotalCountFromFilters() {
        const filterTypeContrat = this.getFilters().filter(filter => filter.filtre === 'typeContrat')[0];
        //console.log('filterTypeContrat : ', filterTypeContrat);
        let totalOffers = 0;
        filterTypeContrat.agregation.forEach(filter => {
            totalOffers += parseInt(filter.nbResultats);
        });
        //console.log('totalOffers : ', totalOffers);

        this.setTotalCount(totalOffers);
    }

    async getTypesContratsFilters() {
        const result = await fetch('/get-types-contrats-filters', {
            credentials: 'include',
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        return await result.json();
    }

    async getDomainesFilters() {
        const result = await fetch('/get-domaines-filters', {
            credentials: 'include',
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        return await result.json();
    }

    async getMetiersRomeFilters() {
        const result = await fetch('/get-metiers-rome-filters', {
            credentials: 'include',
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        return await result.json();
    }
    

    getOffers() {
        return this.offers;
    }

    getFilters() {
        return this.filters;
    }

    getTotalCount() {
        return this.totalCount;
    }


    getSort() {
        return this.sort;
    }


    setOffers(offers) {
        this.offers = offers;
    }

    setFilters(filters) {
        this.filters = filters;
    }

    setTotalCount(totalCount) {
        this.totalCount = totalCount;
    }

    setSort(sort) {
        this.sort = sort;
    }

}