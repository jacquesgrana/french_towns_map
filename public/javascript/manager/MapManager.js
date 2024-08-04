class MapManager {
    mapVue;
    map;
    townsData;
    selectedTown;
    comments;
    favorites;
    townService;
    favoriteService;
    commentService;
    securityService;
    isNewComment;;
    //isLoggedIn;
    constructor() {
        this.mapVue = new MapVue();
        this.map = null;
        this.townsData = [];
        this.selectedTown = null;
        this.comments = [];
        this.favorites = [];
        this.townService = TownService.getInstance();
        this.favoriteService = FavoriteService.getInstance();
        this.commentService = CommentService.getInstance();
        this.securityService = SecurityService.getInstance();
        this.isNewComment = true;
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
            //this.manageButtonsWithLoggedIn();
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
        } 
        else {
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
        if(this.securityService.isLoggedIn) await this.updateFavorites();
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
        if(btnFavorite) btnFavorite.addEventListener('click', async () => {
            await this.toggleFavorite(this.selectedTown.id);
        });
        // btn-comment
        const btnComment = document.getElementById('btn-comment');
        if(btnComment) btnComment.addEventListener('click', async () => {
            //console.log('click comment');
            this.isNewComment = true;
            // TODO changer titre modale en 'Ajouter un commentaire'
            const modalTitle = document.getElementById('title-modal-comment');
            if(modalTitle) modalTitle.textContent = 'Ajouter un commentaire';
            this.mapVue.emptyCommentForm();
        });

        const formComment = document.getElementById('comment-form');
        if(formComment) formComment.addEventListener('submit', (event) => {
            event.preventDefault();
            if(this.isNewComment) {
                this.submitNewComment();
            } else {
                console.log('update comment');
                this.submitUpdatedComment();
            }
        });

    }

    manageButtonsWithLoggedIn() {
        const buttonFavorite = document.getElementById('btn-favorite');
        if(!this.securityService.isLoggedIn || !this.selectedTown) {buttonFavorite.classList.add('display-none');}
        else {buttonFavorite.classList.remove('display-none');}

        const buttonComment = document.getElementById('btn-comment');
        if(this.securityService.isLoggedIn && this.selectedTown) {buttonComment.classList.remove('display-none');}
        else {buttonComment.classList.add('display-none');}
    }

    async submitNewComment() {
        //console.log('submit new comment');
        const form = document.getElementById('comment-form');
        if(!form) return;
        const formData = new FormData(form);
        const title = formData.get('_title');
        const comment = formData.get('_comment');
        const score = formData.get('_score');
        const csrfToken = formData.get('_csrf_token');
        const townId = this.selectedTown.id;

        await this.commentService.submitNewComment(title, comment, score, townId, csrfToken);

        // fermer modale
        const modal = bootstrap.Modal.getInstance(document.getElementById('modal-comment'));
        if(modal) modal.hide();
        await this.displayTownDetails(this.selectedTown);
        await this.updateComments(this.selectedTown);
    }

    async submitUpdatedComment() {
        //console.log('submit updated comment');
        const form = document.getElementById('comment-form');
        if(!form) return;
        const formData = new FormData(form);
        const commentId = formData.get('_comment_id');
        const title = formData.get('_title');
        const comment = formData.get('_comment');
        const score = formData.get('_score');
        const csrfToken = formData.get('_csrf_token');
        const townId = this.selectedTown.id;

        await this.commentService.updateComment(commentId, title, comment, score, townId, csrfToken);

        const modal = bootstrap.Modal.getInstance(document.getElementById('modal-comment'));
        if(modal) modal.hide();
        await this.displayTownDetails(this.selectedTown);
        await this.updateComments(this.selectedTown);
    }

    updateMapFromBounds = async () => {
        var bounds = this.map.getBounds();
        var sw = bounds.getSouthWest();
        var ne = bounds.getNorthEast();
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
    // TODO créer des méthodes et en mettre dans la vue
    async displayTownDetails(town) {
        const averageScoreData = await this.townService.getTownAverageScore(town);
        // transformer en nombre a 1 chiffre apres la virgule
        //console.log('averageScore : ', averageScoreData.averageScore);

        const averageScore = Number(averageScoreData.averageScore.toFixed(1));
        //console.log('averageScore : ', averageScore);
        let starHtml = '';
        //console.log(town);
        if(this.securityService.isLoggedIn) {
            const isFavoriteData = await this.favoriteService.getIsFavorite(town);
            
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
        let html = "<p class='result-line'>" + town.townName + " <span class='text-secondary'>•</span> ";
        html += town.townZipCode;
        html += starHtml + "</p>";
        html += "<p class='result-line'>" + town.depName + " <span class='text-secondary'>•</span> " + town.regName + "</p>";
        if(infos !== '') html += "<p class='result-line'>Population : " + population + " <span class='text-secondary'>•</span> Altitude : " + altitude + "</p>";
        //html += "<p class='result-line'>" + town.regName + "</p>";
        if(averageScore > 0) html += "<p class='result-line'>Score moyen : " + averageScore + "/5</p>";
        textElement.innerHTML = html;
    }

    async updateSelectedTown(town) {
        //console.log('isLoggedIn : ', this.securityService.isLoggedIn);
        //console.log('town objet: ', town);
        this.selectedTown = town;
        await this.displayTownDetails(this.selectedTown);
        await this.updateComments(this.selectedTown);
        this.refreshMap(this.map, this.townsData);
        this.manageButtonsWithLoggedIn(this.securityService.isLoggedIn);
        // TODO ajouter vérification si deja favori : changer le bouton en '-'
        // TODO faire requete qui check si la ville est dans la liste des favoris de l'user
        if(this.securityService.isLoggedIn) {
            const isFavoriteData = await this.favoriteService.getIsFavorite(this.selectedTown);
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
                this.mapVue.fillSelectWithResults(townsToDisplay, this);
            }
        }
    }

    // fonction du clic sur ajouter/enlever favori
    // faire requete pour toggle le favori dans le townService
    // mettre a jour le bouton du favori
    
    toggleFavorite = async (townId) => {
        // attention : on modifie le favori par son id et on vérifie si la vlle affichée (this.selectedTown) est en favori ou pas
        if(!this.securityService.isLoggedIn) return;
        // appeler methode townService.toggleFavoriteForTown(townId)
        const resultToggle = await this.favoriteService.toggleFavoriteForTown(townId);

        
        if(this.selectedTown) {
            const resultFavorite = await this.favoriteService.getIsFavorite(this.selectedTown); 
            this.manageFavoriteButton(resultFavorite.isFavorite);
            await this.displayTownDetails(this.selectedTown);
        }
        
        //TODO : tester : this.manageFavoriteButton(resultFavorite.isFavorite);

        /*
        if(resultFavorite.isFavorite) {
            this.manageFavoriteButton(true);
        } 
        else {
            this.manageFavoriteButton(false);
        }
        */

        

        if(resultToggle.message === "done") {
            // afficher alerte 'favoris mis à jour'
            alert('Favori mis à jour');
            //console.log('resultToggle : ', resultToggle);
        }
        else {
            alert('Erreur lors de la mise à jour du favori :', resultToggle.message);
        }

        //await this.updateComments(this.selectedTown);
        await this.updateFavorites();
    }

    updateComments = async (selectedTown) => {
        //console.log('updateComments for : ', selectedTown);
        const commentsData = await this.commentService.getTownComments(selectedTown);
        this.comments = commentsData.comments;
        //console.log('comments : ', this.comments);
        this.mapVue.displayComments(this.comments, this);
    }

    updateFavorites = async () => {
        //console.log('updateFavorites');
        const favoritesData = await this.favoriteService.getUserFavorites();
        this.favorites = favoritesData.favorites;
        //console.log('favorites : ', this.favorites);
        this.mapVue.displayFavoriteTowns(this.favorites, this);
    }

    async manageClickFavoriteTown(town) {
        //console.log('clic liste sur town : ', town); 
        let realTown = null;
        // appeller méthode de townService qui fait requete pour recuperer la town à partir de son id
        realTown = await this.townService.getTownById(town.id);
        //console.log('realTown : ', realTown);
        if(realTown) {
            await this.updateSelectedTown(realTown);
            this.centerMapOnSelectedTown();
        }
    }

    async deleteComment(commentId) {
        //console.log('deleteComment : ', commentId);
        // afficher une confirmation avant de supprimer le commentaire
        if(confirm('Voulez-vous supprimer ce commentaire ?')) {
            await this.commentService.deleteComment(commentId);
            await this.displayTownDetails(this.selectedTown);
            await this.updateComments(this.selectedTown);
        }        
    }

    editComment = async (comment) => {
        console.log('editComment : ', comment);
        // afficher le formulaire d'edition du commentaire

        if(this.securityService.isLoggedIn && this.securityService.userDetails.pseudo === comment.userPseudo) {
            this.isNewComment = false;
            const modal = new bootstrap.Modal(document.getElementById('modal-comment'));
            //console.log('modal : ', modal);
            if(modal) modal.show();
            // TODO changer titre modale en 'Modifier un commentaire'
            const modalTitle = document.getElementById('title-modal-comment');
            if(modalTitle) modalTitle.textContent = 'Modifier un commentaire';
            // remplir le formulaire avec les infos du commentaire  
            const form = document.getElementById('comment-form');
            //console.log('form : ', form);
            if(form) {
                //form.reset();
                form._comment_id.value = comment.id;
                form._title.value = comment.title;
                form._comment.value = comment.comment;
                form._score.value = comment.score;
                form._town_id.value = comment.townId;
                //form._csrf_token.value = comment.csrfToken;
                // set le slider en fonction du score
                const scoreOutput = document.getElementById('comment-score-output');
                if(scoreOutput) {
                    scoreOutput.value = comment.score;
                }
            }
        }
    }
}
