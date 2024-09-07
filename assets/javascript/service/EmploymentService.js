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