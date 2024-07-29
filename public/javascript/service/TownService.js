class TownService {

    static instance = null;
    static getInstance() {
        if (TownService.instance == null) {
            TownService.instance = new TownService();
        }
        return TownService.instance;
    }


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
}