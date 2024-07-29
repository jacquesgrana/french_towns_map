class MapManager {
    map;
    townsData;
    selectedTown;
    townService;
    constructor() {
        this.map = null;
        this.townsData = [];
        this.selectedTown = null;
        this.townService = TownService.getInstance();
    }

    run() {
        document.addEventListener('DOMContentLoaded',() => {
            const mapContainer = document.getElementById('map');
            if(mapContainer) this.initMap();
            const btnCenterOnTown = document.getElementById('btn-center-town');
            //console.log('this.selectedTown : ', this.selectedTown);
            if(btnCenterOnTown) btnCenterOnTown.addEventListener('click', () => {
                this.centerMapOnSelectedTown();
            });
            const btnCenterPosGps = document.getElementById('btn-center-position');
            if(btnCenterPosGps) btnCenterPosGps.addEventListener('click', () => {
                this.askGeoLocationAndCenterMap(this);
            });
            //btn-search
            const btnSearch = document.getElementById('btn-search');
            if(btnSearch) btnSearch.addEventListener('click', () => {
                this.toggleSearchDiv();
            });

            //btn-search
            const btnLaunchSearch = document.getElementById('btn-launch-search');
            if(btnLaunchSearch) btnLaunchSearch.addEventListener('click', () => {
                this.launchTownSearch();
            });
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

    updateMapFromBounds = async () => {
        var bounds = this.map.getBounds();
        var sw = bounds.getSouthWest();
        var ne = bounds.getNorthEast();
        //console.log('Sud-Ouest:', sw.lat, sw.lng);
        //console.log('Nord-Est:', ne.lat, ne.lng);
/*
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
*/

        this.townsData = await this.townService.getTownsFromBounds(sw.lat, sw.lng, ne.lat, ne.lng);
        this.refreshMap(this.map, this.townsData);
    }
    
    refreshMap = (map, towns) => {
        map.eachLayer((layer) => {
            if(layer instanceof L.Circle) {
                layer.remove();
            }
        });
        towns.forEach((town) => {
            if(this.selectedTown && town.id === this.selectedTown.id) {
                // ajouter un cercle sur la ville selectionné
                const circle = L.circle([town.latitude, town.longitude], {
                    color: '#8d3302',
                    fillColor: '#f17735',
                    fillOpacity: 0.33,
                    radius: 200
                }).addTo(map);
            }
            const marker = L.marker([town.latitude, town.longitude])
                .addTo(map)
                .bindPopup(town.townName);
            marker.on('click', () => {
            this.updateSelectedTown(town);
            //marker.openPopup();
            });

        });
    }

    async displayTownDetails(town) {
        //console.log(town);
        const infos = await this.townService.getInfosFromApis(town.townCode);
        const population = infos.population;
        const altitude = infos.altitude;
        //console.log('infos : ', infos);
        const textElement = document.getElementById('result-text');
        let html = "<p class='result-line'>" + town.townName + " • ";
        html += town.townZipCode + "</p>";
        html += "<p class='result-line'>" + town.depName + " • " + town.regName + "</p>";
        if(infos !== '') html += "<p class='result-line'>Population : " + population + " • Altitude : " + altitude + "</p>";
        //html += "<p class='result-line'>" + town.regName + "</p>";
        textElement.innerHTML = html;
    }

    updateSelectedTown(town) {
        this.selectedTown = town;
        this.displayTownDetails(town);
        this.refreshMap(this.map, this.townsData);
    }

    centerMapOnSelectedTown() {
        if(!this.selectedTown) return;
        this.centerMapOnPosition(this.selectedTown.latitude, this.selectedTown.longitude);
    }

    async  askGeoLocationAndCenterMap(that) {
        function success(position) {
            //console.log("Latitude: " + position.coords.latitude + "°, Longitude: " + position.coords.longitude + "°");
            that.centerMapOnPosition(position.coords.latitude, position.coords.longitude);
        }

        function error() {
        alert("Pas de position accessible !");
        }
        const options = {
        enableHighAccuracy: true,
        maximumAge: 30000,
        timeout: 27000,
        };

        const watchID = navigator.geolocation.getCurrentPosition(
        success,
        error,
        options
        );
    }
    

    centerMapOnPosition(latitude, longitude) {
    //console.log('latitude : ', latitude, 'longitude : ', longitude);
        this.map.panTo([latitude, longitude]);
    }

    toggleSearchDiv() {
        const searchDiv = document.getElementById('search-div');
        searchDiv.classList.toggle('display-none');
        searchDiv.classList.toggle('display-flex');
        const input = document.getElementById('input-search-request');
        if(input) input.value = '';
        const select = document.getElementById('select-search-request');
        if(select) select.classList.add('display-none');
    }

    async launchTownSearch() {
        const input = document.getElementById('input-search-request');
        if(input) {
            const requestValue = input.value;
            // TODO valider si caracteres autorisés
            // avec regex a-z, A-Z, 0-9, - é è émaj èmaj â ê ô û î
            const isRegHexValid = /^[a-zA-ZÀ-ÿ\-]{4,20}$/.test(requestValue);
            //console.log('isRegHexValid : ', isRegHexValid);
            if(requestValue.length > 3 && isRegHexValid) {
                const townsToDisplay = await this.townService.getTownsFromSearch(requestValue);
                this.fillSelectWithResults(townsToDisplay, this);
            }
        }
    }

    fillSelectWithResults(towns, that) {
        towns.sort((a, b) => (a.townName > b.townName) ? 1 : -1);
        const selectElt = document.getElementById('select-search-request');
        if(selectElt && towns.length > 0) {
            selectElt.classList.remove('display-none');
            selectElt.innerHTML = ''
            const firstOption = document.createElement('option');
            firstOption.textContent = 'Choisir une commune';
            firstOption.value = '';
            ;
            selectElt.appendChild(firstOption);
            towns.forEach((town) => {
                const option = document.createElement('option');
                option.textContent = town.townName + ' - ' + town.townZipCode;
                option.value = town.townCode;
                option.onselect = () => {
                    console.log('option : ', option.value);
                    this.updateSelectedTown(town);                    
                    this.centerMapOnSelectedTown();
                }
                selectElt.appendChild(option);
            });

            selectElt.addEventListener('change', (event) => {
                const selectedTownCode = event.target.value;
                const selectedTown = towns.find(town => town.townCode === selectedTownCode);
                if (selectedTown) {
                    //console.log('Ville sélectionnée:', selectedTown);
                    this.updateSelectedTown(selectedTown);
                    //this.toggleSearchDiv();
                    
                    that.centerMapOnSelectedTown();
                }
            });
        }
    }
}