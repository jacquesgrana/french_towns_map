class TownService {

    static instance = null;
    static getInstance() {
        if (TownService.instance == null) {
            TownService.instance = new TownService();
        }
        return TownService.instance;
    }


    // TODO gérer les erreurs 
    // TODO dispatcher certaines méthodes dans des services dédiés (par entité) !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    async getInfosFromApis(code) {
        //console.log('code : ', code);
        const result = await fetch('/get-town-infos-from-apis', {
            credentials: 'include',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                townCode: code
            })
        });
        //console.log('result : ', data);
        return await result.json();
    }

    async getForecastFromApis(code, day) {
        //console.log('code : ', code);
        const result = await fetch('/get-town-forecast-from-apis', {
            credentials: 'include',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                townCode: code,
                day: day
            })
        });
        //console.log('result : ', data);
        return await result.json();
    }

    async getTownsFromSearch(searchRequest) {
        //console.log('code : ', code);
        const result = await fetch('/get-towns-by-name', {
            credentials: 'include',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                searchRequest: searchRequest
            })
        });
        //console.log('result : ', data);
        return await result.json();
    }

    async getTownsFromBounds(swLat, swLng, neLat, neLng) {
        const result = await fetch('/get-towns-by-bounds', {
            credentials: 'include',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                sw_lat: swLat,
                sw_lng: swLng,
                ne_lat: neLat,
                ne_lng: neLng
            })
        });

        return await result.json();
    }

    /*
    async getIsFavorite(town) {
        //return {isFavorite: true}
        //console.log('town : ', town);
        const result = await fetch('/get-is-favorite', {
            credentials: 'include',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                townId: town.id
            })
        });
        const data = await result.json();
        return data;
    }
        */

    /*
    async toggleFavoriteForTown(town) {
        const result = await fetch('/toggle-favorite-by-town', {
            credentials: 'include',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                townId: town.id
            })
        });
        const data = await result.json();
        return data;
    }
    */

    /*
    //get-comments-for-town
    async getTownComments(town) {
        const result = await fetch('/get-comments-by-town', {
            credentials: 'include',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                townId: town.id
            })
        });
        const data = await result.json();
        return data;
    }

    ///get-comments-by-user
    async getCommentsForUser() {
        const result = await fetch('/get-comments-by-user', {
            credentials: 'include',
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        const data = await result.json();
        return data;
    }
    */

    async getTownAverageScore(town) {
        const result = await fetch('/get-average-score-by-town', {
            credentials: 'include',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                townId: town.id
            })
        });
        const data = await result.json();
        return data;
    }

    async getTownById(townId) {
        const result = await fetch('/get-town-by-id', {
            credentials: 'include',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                townId: townId
            })
        });
        const data = await result.json();
        return data;
    }
}