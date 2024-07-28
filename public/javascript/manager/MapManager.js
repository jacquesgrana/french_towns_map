class MapManager {
    map;
    townsData;
    constructor() {
        this.map = null;
        this.townsData = [];
    }

    run() {
        document.addEventListener('DOMContentLoaded',() => {
            const mapContainer = document.getElementById('map');
            if(mapContainer) this.initMap();
        });
        document.addEventListener('turbo:load',() => {
            const mapContainer = document.getElementById('map');
            if(mapContainer) this.initMap();
        });
    }

     initMap() {
        
        if (this.map) {
            this.map.remove(); 
        }
        this.map = L.map('map').setView([43.610769, 3.876716], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(this.map);
        this.updateMapFromBounds();
        this.map.on('moveend', this.updateMapFromBounds);
    }
    
    
    /*
    updateBounds = () => {
        var bounds = map.getBounds();
        var sw = bounds.getSouthWest();
        var ne = bounds.getNorthEast();
        
        console.log('Sud-Ouest:', sw.lat, sw.lng);
        console.log('Nord-Est:', ne.lat, ne.lng);
    }
    */

    updateMapFromBounds = () => {
        var bounds = this.map.getBounds();
        var sw = bounds.getSouthWest();
        var ne = bounds.getNorthEast();
        //console.log('Sud-Ouest:', sw.lat, sw.lng);
        //console.log('Nord-Est:', ne.lat, ne.lng);
        fetch('/get-towns-by-bounds', {
            credentials: 'include',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                sw_lat: sw.lat,
                sw_lng: sw.lng,
                ne_lat: ne.lat,
                ne_lng: ne.lng
            })
        })
        .then(response => response.json())
        .then(data => {
            //console.log('Success:', data);
            this.townsData = data;
            this.refreshMap(this.map, data);
            })
        .catch((error) => console.error('Error:', error));
    }
    
    refreshMap = (map, towns) => {
        towns.forEach((town) => {
            const marker = L.marker([town.latitude, town.longitude])
                .addTo(map)
                .bindPopup(town.townName);
            marker.on('click', () => {
            this.displayTownDetails(town);
            //marker.openPopup();
            });
        });
    }

    async displayTownDetails(town) {
        //console.log(town);
        // appel fonction qui fait une requete sur /get-town-infos-from-apis avec ?townCode = ...
        const infos = await this.getInfosFromApis(town.townCode);
        const population = infos.population;
        const textElement = document.getElementById('result-text');
        let html = "<p class='result-line'>" + town.townName + "</p>";
        html += "<p class='result-line'>" + town.townZipCode + "</p>";
        html += "<p class='result-line'>" + town.depName + " / " + town.regName + "</p>";
        if(infos !== '') html += "<p class='result-line'>Population : " + population + "</p>";
        //html += "<p class='result-line'>" + town.regName + "</p>";
        textElement.innerHTML = html;
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

}