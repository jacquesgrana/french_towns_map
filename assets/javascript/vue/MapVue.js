class MapVue {
    constructor() {
    }

    displayFavoriteTowns = (towns, that) => {
        //console.log('displayFavoriteTowns : ', towns);
        // trier les towns par townName
        towns.sort((a, b) => (a.townName > b.townName) ? 1 : -1);

        const favoriteTownNb = towns.length;
        //acc-button-favorite
        const buttonFavorite = document.getElementById('acc-button-favorite');
        if(buttonFavorite) buttonFavorite.textContent = 'Favoris (' + favoriteTownNb + ')';

        // map-accordion-body-favorite
        const favoritesDiv = document.getElementById('map-accordion-body-favorite');
        if(!favoritesDiv) return;
        favoritesDiv.innerHTML = '';
        if(towns.length === 0) {
            const noFavoriteDiv = document.createElement('div');
            const paragraph = document.createElement('p');
            paragraph.classList.add('text-white');
            paragraph.textContent = 'Vous n\'avez pas de favori(s).';
            noFavoriteDiv.appendChild(paragraph);
            favoritesDiv.appendChild(noFavoriteDiv);
        }
        else {
            favoritesDiv.classList.add('list-group');
            towns.forEach(town => {
                const button = document.createElement('div');
                button.classList.add('list-group-item');
                button.classList.add('list-group-item-custom');
                //button.classList.add('list-group-item-action');
                button.classList.add('favorite-item');
                const divInfo = document.createElement('div');
                divInfo.innerHTML = town.townName;
                divInfo.innerHTML += ' <span class="text-secondary">•</span> ' + town.townZipCode;
                divInfo.innerHTML += ' <span class="text-secondary">•</span> ' + town.townDptName;
                divInfo.innerHTML += ' <span class="text-secondary">•</span> ' + town.townRegName;
                button.appendChild(divInfo);
                const divButton = document.createElement('div');

                const buttonCenterFavorite = document.createElement('button');
                buttonCenterFavorite.classList.add('btn');
                buttonCenterFavorite.classList.add('btn-sm');
                buttonCenterFavorite.classList.add('btn-secondary-very-small');
                buttonCenterFavorite.classList.add('decal-down');
                buttonCenterFavorite.textContent = '◎';
                buttonCenterFavorite.setAttribute("title", "Aller à cette commune.");

                buttonCenterFavorite.onclick = async () => {
                    await that.manageClickFavoriteTown(town);
                }
                divButton.appendChild(buttonCenterFavorite);

                const buttonDeleteFavorite = document.createElement('button');
                buttonDeleteFavorite.classList.add('btn');
                buttonDeleteFavorite.classList.add('btn-sm');
                buttonDeleteFavorite.classList.add('btn-secondary-very-small');
                buttonDeleteFavorite.classList.add('ms-2');
                buttonDeleteFavorite.textContent = '-';
                buttonDeleteFavorite.setAttribute("title", "Supprimer cette commune de vos favoris.");
                buttonDeleteFavorite.onclick = async () => {
                    await that.toggleFavorite(town.id);
                }
                divButton.appendChild(buttonDeleteFavorite);
                button.appendChild(divButton);

                /*
                button.onclick = async () => {
                    await that.manageClickFavoriteTown(town);
                }
                */
                favoritesDiv.appendChild(button);
            });
        }
    }
    /*
<button id="btn-favorite" class="btn btn-sm btn-primary-dark-small display-none">+</button>
    */

    displayComments = (comments, that) => {
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

                let isUserOwnsComment = false;
                if(that.securityService.userDetails) {
                    isUserOwnsComment = comment.userPseudo === that.securityService.userDetails.pseudo;
                }
                const buttonsDiv = document.createElement('div');
                buttonsDiv.classList.add('div-comment-buttons');
                if(isUserOwnsComment) {
                    const buttonDelete = document.createElement('button');
                    buttonDelete.classList.add('btn');
                    buttonDelete.classList.add('btn-sm');
                    buttonDelete.classList.add('btn-secondary-very-small');
                    buttonDelete.textContent = 'X';
                    buttonDelete.setAttribute("title", "Supprimer ce commentaire.");
                    buttonDelete.onclick = async () => {
                        await that.deleteComment(comment.id);
                    }
                    buttonsDiv.appendChild(buttonDelete);

                    const buttonEdit = document.createElement('button');
                    buttonEdit.classList.add('btn');
                    buttonEdit.classList.add('btn-sm');
                    buttonEdit.classList.add('btn-secondary-very-small');
                    buttonEdit.classList.add('ms-2');
                    buttonEdit.textContent = '✎';
                    buttonEdit.setAttribute("title", "Modifier ce commentaire.");
                    buttonEdit.onclick = async () => {
                        //await that.editComment(comment.id);
                        that.editComment(comment);
                    }
                    buttonsDiv.appendChild(buttonEdit); 
                }

                cardBody.appendChild(cardTitle);
                cardBody.appendChild(cardText);
                cardBody.appendChild(commentScore);
                if (isUserOwnsComment) cardBody.appendChild(buttonsDiv);

                card.appendChild(cardBody);
                commentsDiv.appendChild(card);
            });
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
                selectElt.appendChild(option);
            });

            selectElt.addEventListener('change', async (event) => {
                const selectedTownCode = event.target.value;
                const selectedTown = towns.find(town => town.townCode === selectedTownCode);
                if (selectedTown) {
                    await that.updateSelectedTown(selectedTown);
                    that.centerMapOnSelectedTown();
                }
            });
        }
    }

    emptyCommentForm() {
        const commentForm = document.getElementById('comment-form');
        if(commentForm) commentForm.reset();
    }

    displayForecast(forecastInfos) {
        //map-accordion-body-forecast
        const forecastDiv = document.getElementById('map-accordion-body-forecast');
        //console.log('forecastInfos : ', forecastInfos.forecast);
        if(forecastInfos && forecastDiv) {
            forecastDiv.innerHTML = '';
            const dateTime = forecastInfos.forecast.datetime;

            const wind10m = forecastInfos.forecast.wind10m;
            const gust10m = forecastInfos.forecast.gust10m;
            const dirWind10m = forecastInfos.forecast.dirwind10m;

            const tMin = forecastInfos.forecast.tmin;
            const tMax = forecastInfos.forecast.tmax;
            const sunHours = forecastInfos.forecast.sun_hours;
            const weatherCode = forecastInfos.forecast.weather;

            const probaRain = forecastInfos.forecast.probarain;
            const probaFrost = forecastInfos.forecast.probafrost;
            const probaFog = forecastInfos.forecast.probafog;
            const probaWind70 = forecastInfos.forecast.probawind70;
            const probaWind100 = forecastInfos.forecast.probawind100;


            const card = document.createElement('card');

            const paragWeatherIcon = document.createElement('p');
            paragWeatherIcon.classList.add('text-white');
            paragWeatherIcon.innerHTML = 'Icone : <span class="text-secondary">' + weatherCode + '</span>';
            card.appendChild(paragWeatherIcon);

            const paragTemps = document.createElement('p');
            paragTemps.classList.add('text-white');
            paragTemps.innerHTML = 'Températures : <span class="text-secondary">min : ' + tMin + ' °C </span>•<span class="text-secondary"> max : ' + tMax + ' °C</span>';
            card.appendChild(paragTemps);

            const paragwind = document.createElement('p');
            paragwind.classList.add('text-white');
            paragwind.innerHTML = 'Vent : <span class="text-secondary">10m : ' + wind10m + ' km/h </span>•<span class="text-secondary"> Rafale : ' + gust10m + ' km/h</span>';
            card.appendChild(paragwind);

            const paragWindDir = document.createElement('p');
            paragWindDir.classList.add('text-white');
            paragWindDir.innerHTML = 'Direction du vent : <span class="text-secondary">' + dirWind10m + '°</span>';
            card.appendChild(paragWindDir);

            const paragSunHours = document.createElement('p');
            paragSunHours.classList.add('text-white');
            paragSunHours.innerHTML = 'Heures d\'eclairage : <span class="text-secondary">' + sunHours + 'h</span>';
            card.appendChild(paragSunHours);

            const paragRain = document.createElement('p');
            paragRain.classList.add('text-white');
            paragRain.innerHTML = 'Probabilité de pluie : <span class="text-secondary">' + probaRain + '%</span>';
            card.appendChild(paragRain);

            const paragFrost = document.createElement('p');
            paragFrost.classList.add('text-white');
            paragFrost.innerHTML = 'Probabilité de gel : <span class="text-secondary">' + probaFrost + '%</span>';
            card.appendChild(paragFrost);

            const paragFog = document.createElement('p');
            paragFog.classList.add('text-white');
            paragFog.innerHTML = 'Probabilité de brouillard : <span class="text-secondary">' + probaFog + '%</span>';
            card.appendChild(paragFog);

            const paragWind70 = document.createElement('p');
            paragWind70.classList.add('text-white');
            paragWind70.innerHTML = 'Probabilité de vent 70 km/h : <span class="text-secondary">' + probaWind70 + '%</span>';
            card.appendChild(paragWind70);

            const paragWind100 = document.createElement('p');
            paragWind100.classList.add('text-white');
            paragWind100.innerHTML = 'Probabilité de vent 100 km/h : <span class="text-secondary">' + probaWind100 + '%</span>';
            card.appendChild(paragWind100);

            forecastDiv.appendChild(card);

        }
        
    }
}

/**

{
    "city": {
        "insee": "35238",
        "cp": 35000,
        "name": "Rennes",
        "latitude": 48.112,
        "longitude": -1.6819,
        "altitude": 38
    },
    "update": "2020-10-29T12:40:08+0100",
    "forecast": {
        "insee": "35238",
        "cp": 35000,
        "latitude": 48.112,
        "longitude": -1.6819,
        "day": 0,
        "datetime": "2020-10-29T01:00:00+0100",
        "wind10m": 15, //Vent moyen à 10 mètres en km/h
        "gust10m": 49, //Rafales de vent à 10 mètres en km/h
        "dirwind10m": 230, //Direction du vent en degrés (0 à 360°)
        "rr10": 0.2,
        "rr1": 0.3,
        "probarain": 40,
        "weather": 4,
        "tmin": 11,
        "tmax": 17,
        "sun_hours": 1,
        "etp": 1,
        "probafrost": 0,
        "probafog": 0,
        "probawind70": 0,
        "probawind100": 0,
        "gustx": 49
    }
}
   */