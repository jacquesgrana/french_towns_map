class MapManager {
    map;
    townsData;
    selectedTown;
    townService;
    securityService;
    //isLoggedIn;
    constructor() {
        this.map = null;
        this.townsData = [];
        this.selectedTown = null;
        this.comments = [];
        this.townService = TownService.getInstance();
        this.securityService = SecurityService.getInstance();
        //this.isLoggedIn = this.securityService.checkAuthStatus();
    }

    run() {
        /*
        document.addEventListener('DOMContentLoaded',() => {
            this.initListeners();
            const mapContainer = document.getElementById('map');
            if(mapContainer) this.init();
        });*/
        document.addEventListener('turbo:load',() => {
            this.initListeners();
            const mapContainer = document.getElementById('map');
            if(mapContainer) this.init();
        });
    }

     async init() {
        await this.securityService.checkAuthStatus();
        this.manageButtonsWithLoggedIn();
        //this.selectedTown = null;
        if (this.map) {
            this.map.remove();
            this.map = null;
        }
    
        // Récupérer l'élément conteneur de la carte
        let mapContainer = document.getElementById('map');
    
        // Si le conteneur existe, le supprimer et le recréer
        if (mapContainer) {
            const parent = mapContainer.parentNode;
            parent.removeChild(mapContainer);
            mapContainer = document.createElement('div');
            mapContainer.id = 'map';
            // placer au debut
            parent.insertBefore(mapContainer, parent.firstChild);
            //parent.appendChild(mapContainer);
        } else {
            console.error("L'élément 'map' n'existe pas dans le DOM");
            return;
        }
    
        // Créer une nouvelle carte
        const latitude = this.selectedTown ? this.selectedTown.latitude : 43.610769;
        const longitude = this.selectedTown ? this.selectedTown.longitude : 3.876716;
        this.map = L.map('map', {
            scrollWheelZoom: false  // Désactive le zoom avec la molette
        }).setView([latitude, longitude], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(this.map);
        
        if(this.selectedTown) {
            await this.displayTownDetails(this.selectedTown);
            await this.updateComments(this.selectedTown);
        }
        this.updateMapFromBounds();
        this.map.on('moveend', this.updateMapFromBounds);
    }

    initListeners() {
        const btnCenterOnTown = document.getElementById('btn-center-town');
            //console.log('this.selectedTown : ', this.selectedTown);
        if(btnCenterOnTown) btnCenterOnTown.addEventListener('click', () => {
            this.centerMapOnSelectedTown();
        });
        const btnCenterPosGps = document.getElementById('btn-center-position');
        if(btnCenterPosGps) btnCenterPosGps.addEventListener('click', () => {
            this.askGeoLocationAndCenterMap(this);
        });
        const btnSearch = document.getElementById('btn-search');
        if(btnSearch) btnSearch.addEventListener('click', () => {
            console.log('click search');
            this.toggleSearchDiv();
        });

        const btnLaunchSearch = document.getElementById('btn-launch-search');
        if(btnLaunchSearch) btnLaunchSearch.addEventListener('click', () => {
            this.launchTownSearch();
        });
        // btn-favorite
        const btnFavorite = document.getElementById('btn-favorite');
        if(btnFavorite) btnFavorite.addEventListener('click', () => {
            this.toggleFavorite();
        });
    }

    manageButtonsWithLoggedIn() {
        const buttonFavorite = document.getElementById('btn-favorite');
        //console.log('selectedTown : ', this.selectedTown);

        if(!this.securityService.isLoggedIn || !this.selectedTown) {buttonFavorite.classList.add('display-none');}
        else {buttonFavorite.classList.remove('display-none');}
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
            if(layer instanceof L.Circle || layer instanceof L.Marker) {
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
            marker.on('click', async () => {
                await this.updateSelectedTown(town);
            //marker.openPopup();
            });

        });
    }

    // TODO gérer les erreurs Api !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    async displayTownDetails(town) {
        const averageScoreData = await this.townService.getTownAverageScore(town);
        // transformer en nombre a 1 chiffre apres la virgule
        //console.log('averageScore : ', averageScoreData.averageScore);

        const averageScore = Number(averageScoreData.averageScore.toFixed(1));
        //console.log('averageScore : ', averageScore);
        let starHtml = '';
        //console.log(town);
        if(this.securityService.isLoggedIn) {
            const isFavoriteData = await this.townService.getIsFavorite(town);
            
            if(isFavoriteData.isFavorite) {
                //starHtml = '<i class="fas fa-star"></i>';
                starHtml = '<strong class="favorite-star">&nbsp;★</strong>';
            } 
             
        }
        //console.log('starHtml : ', starHtml);
        const infos = await this.townService.getInfosFromApis(town.townCode);
        const population = infos.population;
        const altitude = infos.altitude;
        //console.log('infos : ', infos);
        const textElement = document.getElementById('result-text');
        let html = "<p class='result-line'>" + town.townName + " • ";
        html += town.townZipCode;
        html += starHtml + "</p>";
        html += "<p class='result-line'>" + town.depName + " • " + town.regName + "</p>";
        if(infos !== '') html += "<p class='result-line'>Population : " + population + " • Altitude : " + altitude + "</p>";
        //html += "<p class='result-line'>" + town.regName + "</p>";
        if(averageScore > 0) html += "<p class='result-line'>Score moyen : " + averageScore + "/5</p>";
        textElement.innerHTML = html;
    }

    async updateSelectedTown(town) {
        //console.log('isLoggedIn : ', this.securityService.isLoggedIn);
        this.selectedTown = town;
        await this.displayTownDetails(town);
        await this.updateComments(this.selectedTown);
        this.refreshMap(this.map, this.townsData);
        this.manageButtonsWithLoggedIn(this.securityService.isLoggedIn);
        // TODO ajouter vérification si deja favori : changer le bouton en '-'
        // TODO faire requete qui check si la ville est dans la liste des favoris de l'user
        if(this.securityService.isLoggedIn) {
            const isFavoriteData = await this.townService.getIsFavorite(this.selectedTown);
            //console.log('isFavoriteData : ', isFavoriteData);
            const isFavorite = isFavoriteData.isFavorite;
            //console.log('isFavorite : ', isFavorite);
             // appeler fonction qui change le bouton selon isFavorite
            this.manageFavoriteButton(isFavorite);
            if(isFavorite) {
               
            }
        }
        
        //console.log('isFavorite : ', isFavorite);
    }

    manageFavoriteButton(isFavorite) {
        const buttonFavorite = document.getElementById('btn-favorite');
        if(buttonFavorite) {
            if(isFavorite) {
                buttonFavorite.textContent = "-";
            } 
            else {
                buttonFavorite.textContent = "+";
            }
        }
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

    // mettre dans la vue
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

    // mettre dans la vue
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
                /*
                option.onselect = () => {
                    console.log('option : ', option.value);
                    this.updateSelectedTown(town);                    
                    this.centerMapOnSelectedTown();
                }*/
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

    // fonction du clic sur ajouter/enlever favori
    // faire requete pour toggle le favori dans le townService
    // mettre a jour le bouton du favori
    
    toggleFavorite = async () => {
        if(!this.selectedTown || !this.securityService.isLoggedIn) return;
        // appeler methode townService.toggleFavoriteForTown(townId)
        const resultToggle = await this.townService.toggleFavoriteForTown(this.selectedTown);
        const resultFavorite = await this.townService.getIsFavorite(this.selectedTown);
        //TODO : tester : this.manageFavoriteButton(resultFavorite.isFavorite);

        if(resultFavorite.isFavorite) {
            this.manageFavoriteButton(true);
        } 
        else {
            this.manageFavoriteButton(false);
        }

        if(resultToggle.message === "done") {
            // afficher alerte 'favoris mis à jour'
            alert('Favori mis à jour');
            //console.log('resultToggle : ', resultToggle);
        }
        else {
            alert('Erreur lors de la mise à jour du favori :', resultToggle.message);
        }
        await this.displayTownDetails(this.selectedTown);
        await this.updateComments(this.selectedTown);
    }

    updateComments = async (selectedTown) => {
        //console.log('updateComments for : ', selectedTown);
        const commentsData = await this.townService.getTownComments(this.selectedTown);
        this.comments = commentsData.comments;
        //console.log('comments : ', this.comments);
        this.displayComments(this.comments);
    }

    displayComments = (comments) => {
        // trier comments par date de création
        comments.sort((a, b) => (a.createdAt < b.createdAt) ? 1 : -1);

        const commentNb = comments.length;
        const buttonTitle = document.getElementById('acc-button-comment');
        if(buttonTitle) buttonTitle.textContent = 'Commentaires (' + commentNb + ')';
        //console.log('comments : ', comments);
        const commentsDiv = document.getElementById('map-accordion-body-comment');
        if(!commentsDiv) return;
        commentsDiv.innerHTML = '';
        //console.log('commentsDiv : ', commentsDiv);
        if(comments.length === 0) {
            const noCommentsDiv = document.createElement('div');
            const paragraph = document.createElement('p');
            paragraph.classList.add('text-white');
            paragraph.textContent = 'Aucun commentaire(s) pour cette commune.';
            noCommentsDiv.appendChild(paragraph);
            commentsDiv.appendChild(noCommentsDiv);
        }
        else {
            comments.forEach(comment => {
                const card = document.createElement('div');
                card.classList.add('card');
                card.classList.add('card-comment');
                const cardBody  = document.createElement('div');
                cardBody.classList.add('card-body');

                const cardTitle = document.createElement('h5');
                cardTitle.classList.add('card-title');
                const titleComment = comment.title + '&nbsp;<span class="comment-pseudo">[' + comment.userPseudo + ']</span>';
                cardTitle.innerHTML = titleComment;


                const cardText = document.createElement('p');
                cardText.classList.add('card-text');
                let textComment  = '<span class="comment-date ">Créé le :' + comment.createdAt + ' • Modifié le :' + comment.modifiedAt + '</span></br>';
                textComment += comment.comment;
                cardText.innerHTML = textComment;

                const commentScore = document.createElement('div');
                commentScore.classList.add('comment-score');
                commentScore.innerHTML = comment.score;
        
                cardBody.appendChild(cardTitle);
                cardBody.appendChild(cardText);
                cardBody.appendChild(commentScore);

                card.appendChild(cardBody);
                commentsDiv.appendChild(card);
            });
        }
        
    }
}

/*
<div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
*/