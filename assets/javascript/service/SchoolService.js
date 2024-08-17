class SchoolService {

    limit = 10;
    offset = 0;
    totalCount = 0;
    order_by = 'nom_etablissement';

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
                order_by: this.order_by
            })
        });
        return await result.json();
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

    setOrder_by(order_by) {
        this.order_by = order_by;
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
}