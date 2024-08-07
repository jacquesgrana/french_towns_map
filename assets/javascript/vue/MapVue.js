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
}