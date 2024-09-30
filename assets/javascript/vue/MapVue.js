class MapVue {

    sort = 2;
    filtersTypesContrats = '';
    filtersDomaines = '';
    filtersMetiersRome = '';
    filtersCodesNaf = '';

    employmentService = EmploymentService.getInstance();
    constructor() {
        this.sort = 2;
        this.filtersTypesContrats = '';
        this.filtersDomaines = '';
        this.filtersMetiersRome = '';
        this.filtersCodesNaf = '';
    }

    initVars() {
        this.sort = 2;
        this.filtersTypesContrats = '';
        this.filtersDomaines = '';
        this.filtersMetiersRome = '';
        this.filtersCodesNaf = '';
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
                buttonCenterFavorite.classList.add('btn-tooltip');
                buttonCenterFavorite.classList.add('btn-sm');
                buttonCenterFavorite.classList.add('btn-secondary-very-small');
                buttonCenterFavorite.classList.add('decal-down');
                buttonCenterFavorite.textContent = '◎';
                buttonCenterFavorite.setAttribute("data-tooltip", "Aller à cette commune.");

                buttonCenterFavorite.onclick = async () => {
                    await that.manageClickFavoriteTown(town);
                }
                divButton.appendChild(buttonCenterFavorite);

                const buttonDeleteFavorite = document.createElement('button');
                buttonDeleteFavorite.classList.add('btn');
                buttonDeleteFavorite.classList.add('btn-tooltip');
                buttonDeleteFavorite.classList.add('btn-sm');
                buttonDeleteFavorite.classList.add('btn-secondary-very-small');
                buttonDeleteFavorite.classList.add('ms-2');
                buttonDeleteFavorite.textContent = '-';
                buttonDeleteFavorite.setAttribute("data-tooltip", "Supprimer cette commune de vos favoris.");
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
                    buttonDelete.classList.add('btn-tooltip');
                    buttonDelete.classList.add('btn-sm');
                    buttonDelete.classList.add('btn-secondary-very-small');
                    buttonDelete.textContent = 'X';
                    buttonDelete.setAttribute("data-tooltip", "Supprimer ce commentaire.");
                    buttonDelete.onclick = async () => {
                        await that.deleteComment(comment.id);
                    }
                    buttonsDiv.appendChild(buttonDelete);

                    const buttonEdit = document.createElement('button');
                    buttonEdit.classList.add('btn');
                    buttonEdit.classList.add('btn-tooltip');
                    buttonEdit.classList.add('btn-sm');
                    buttonEdit.classList.add('btn-secondary-very-small');
                    buttonEdit.classList.add('ms-2');
                    buttonEdit.textContent = '✎';
                    buttonEdit.setAttribute("data-tooltip", "Modifier ce commentaire.");
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

    displaySchoolInModal(school) {
        const modalTitle = document.getElementById('title-modal-school');
        modalTitle.classList.add('text-secondary');
        modalTitle.textContent = school.nom_etablissement;
        const divModal1 = document.getElementById('div-modal-school-1');
        divModal1.innerHTML = school.libelle_nature !== '' ? this.formatTextToCamelCase(school.libelle_nature) + '<br/>' : '';
        divModal1.innerHTML += school.type_etablissement !== '' ? school.type_etablissement : '';
        divModal1.innerHTML += school.statut_public_prive !== '' ? '&nbsp;' + '<span class="badge rounded-pill text-bg-secondary">' + school.statut_public_prive + '</span>': '';
        divModal1.innerHTML += school.nombre_d_eleves !== -1 ? `<br/>${school.nombre_d_eleves} élèves` : '';
        divModal1.innerHTML += school.etat !== '' ? `<br/>${school.etat}` : '';
        divModal1.innerHTML += school.libelle_academie !== '' ? '<br/> <span class="text-secondary">Académie :</span> ' + school.libelle_academie : '';
        divModal1.innerHTML += school.ministere_tutelle !== '' ? '<br/> <span class="text-secondary">Ministère :</span> ' + this.formatTextToCamelCase(school.ministere_tutelle) : '';

        const divModal2 = document.getElementById('div-modal-school-2');
        divModal2.innerHTML = school.ecole_maternelle === '1' ? '<span class="badge rounded-pill text-bg-primary">Ecole Maternelle</span>': '';
        divModal2.innerHTML += school.ecole_elementaire === '1' ? '<span class="badge rounded-pill text-bg-primary">Ecole Elémentaire</span>': '';
        divModal2.innerHTML += school.libelle_nature === 'COLLEGE' ? '<span class="badge rounded-pill text-bg-primary">Collège</span>': '';
        divModal2.innerHTML += school.libelle_nature.startsWith("LYC") ? '<span class="badge rounded-pill text-bg-primary">Lycée</span>': '';
        divModal2.innerHTML += school.voie_generale === '1' ? '<span class="badge rounded-pill text-bg-primary">Voie Générale</span>': '';
        divModal2.innerHTML += school.voie_technologique === '1' ? '<span class="badge rounded-pill text-bg-primary">Voie Technologique</span>': '';
        divModal2.innerHTML += school.voie_professionnelle === '1' ? '<span class="badge rounded-pill text-bg-primary">Voie Professionnelle</span>': '';
        divModal2.innerHTML += school.apprentissage === '1' ? '<span class="badge rounded-pill text-bg-primary">Apprentissage</span>': '';
        divModal2.innerHTML += school.segpa === '1' ? '<span class="badge rounded-pill text-bg-primary">Segpa</span>': '';

        divModal2.innerHTML += school.section_arts === '1' ? '<span class="badge rounded-pill text-bg-secondary-dark">Section Arts</span>': '';
        divModal2.innerHTML += school.section_cinema === '1' ? '<span class="badge rounded-pill text-bg-secondary-dark">Section Cinema</span>': '';
        divModal2.innerHTML += school.section_theatre === '1' ? '<span class="badge rounded-pill text-bg-secondary-dark">Section Théâtre</span>': '';
        divModal2.innerHTML += school.section_sports === '1' ? '<span class="badge rounded-pill text-bg-secondary-dark">Section Sports</span>': '';
        divModal2.innerHTML += school.section_internationale === '1' ? '<span class="badge rounded-pill text-bg-secondary-dark">Section Internationale</span>': '';
        divModal2.innerHTML += school.section_europeenne === '1' ? '<span class="badge rounded-pill text-bg-secondary-dark">Section Européenne</span>': '';
        divModal2.innerHTML += school.lycee_agricole === '1' ? '<span class="badge rounded-pill text-bg-secondary-dark">Lycée Agricole</span>': '';
        divModal2.innerHTML += school.lycee_militaire === '1' ? '<span class="badge rounded-pill text-bg-secondary-dark">Lycée Militaire</span>': '';
        divModal2.innerHTML += school.lycee_des_metiers === '1' ? '<span class="badge rounded-pill text-bg-secondary-dark">Lycée des Métiers</span>': '';

        divModal2.innerHTML += school.restauration === '1' ? '<span class="badge rounded-pill text-bg-secondary">Restauration</span>': '';
        divModal2.innerHTML += school.hebergement === '1' ? '<span class="badge rounded-pill text-bg-secondary">Hébergement</span>': '';
        divModal2.innerHTML += school.greta === '1' ? '<span class="badge rounded-pill text-bg-secondary">Greta</span>': '';

        const divModal3 = document.getElementById('div-modal-school-3');
        divModal3.innerHTML = '<span class="text-secondary">Adresse :</span> ';
        divModal3.innerHTML += school.adresse_1 !== '' ? this.formatTextToCamelCase(school.adresse_1) + ' ' : '';
        divModal3.innerHTML += school.adresse_2 !== '' ? this.formatTextToCamelCase(school.adresse_2) + ' ' : '';
        divModal3.innerHTML += school.adresse_3 !== '' ? this.formatTextToCamelCase(school.adresse_3) + ' ' : '';
        divModal3.innerHTML += (school.telephone !== '' && school.telephone !== undefined) ? '</br><span class="text-secondary">Téléphone :</span> ' + school.telephone : '';
        divModal3.innerHTML += (school.fax !== '' && school.fax !== undefined) ? '</br><span class="text-secondary">Fax :</span> ' + school.fax : '';
        divModal3.innerHTML += (school.mail !== '' && school.mail !== undefined) ? '</br><span class="text-secondary">Courriel :</span> ' + school.mail : '';
        divModal3.innerHTML += (school.fiche_onisep !== '' && school.fiche_onisep !== undefined) ? `</br><a class="link-02" target="_blank" href="${school.fiche_onisep}">Fiche ONISEP</a>` : '';
        divModal3.innerHTML += (school.web !== '' && school.web !== undefined) ? `</br><a class="link-02" target="_blank" href="${school.web}">Page web</a>` : '';
    }

    displayOfferInModal(offer) {
        console.log('displayOfferInModal : ', offer);
        const modalTitle = document.getElementById('title-modal-offer');
        modalTitle.classList.add('text-secondary');
        modalTitle.innerHTML = 'Voir l\'offre d\'emploi : <span class="text-white">' + offer.id + '</span>';

        const divModal = document.getElementById('modal-offer-body');
        divModal.innerHTML = '';
        const divModal1 = document.createElement('div');
        divModal1.classList.add('div-modal-offer');
        const divModal2 = document.createElement('div');
        divModal2.classList.add('div-modal-offer');
        const divModal3 = document.createElement('div');
        divModal3.classList.add('div-modal-offer');
        const divModal4 = document.createElement('div');
        divModal4.classList.add('div-modal-offer');

        divModal1.innerHTML = offer.intitule !== '' ? '<span class="text-secondary">Intitule :</span> ' + offer.intitule : '';
        divModal1.innerHTML += offer.dateCreation !== '' ? '</br><span class="text-secondary">Date d\'ajout :</span> ' + this.formatDateFR(offer.dateCreation) : '';
        divModal1.innerHTML += offer.dateActualisation !== '' ? '</br><span class="text-secondary">Date d\'actualisation :</span> ' + this.formatDateFR(offer.dateActualisation) : '';

        if (offer.description !== '') {
            divModal1.innerHTML += `
                <div class="mt-1">
                    <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#descriptionCollapse" aria-expanded="false" aria-controls="descriptionCollapse">
                        Description
                    </button>
                    <div class="collapse mt-2" id="descriptionCollapse">
                        <div class="card card-body div-modal-offer-description">
                            ${this.formatLongText(offer.description)}
                        </div>
                    </div>
                </div>
            `;
        }
        //console.log('offer.formations : ', offer.formations);
        //console.log('offer.formations.length : ', offer.formations.length);

        //divModal2.innerHTML = offer.formations.length > 0 ? '<span class="text-secondary">Formations :</span> ' : '';
        divModal2.innerHTML = '';

        divModal2.innerHTML += offer.typeContratLibelle !== '' ? '<span class="badge rounded-pill text-bg-primary">Contrat : ' + offer.typeContratLibelle + '</span>': '';

        divModal2.innerHTML += offer.experienceLibelle !== '' ? '<span class="badge rounded-pill text-bg-primary">Expérience : ' + offer.experienceLibelle + '</span></br>': '';


        if(offer.formations.length > 0) {
            for(let i = 0; i < offer.formations.length; i++) {
                const codeFormation = offer.formations[i].codeFormation !== '' ? offer.formations[i].codeFormation + ' • ' : '';
                const domaineLibelle = offer.formations[i].domaineLibelle !== '' ? offer.formations[i].domaineLibelle + ' • ' : '';
                const niveauLibelle = offer.formations[i].niveauLibelle !== '' ? offer.formations[i].niveauLibelle + ' • ' : '';
                const exigence = offer.formations[i].exigence !== '' ? offer.formations[i].exigence : '';
                divModal2.innerHTML += '<span class="badge rounded-pill text-bg-secondary">' +
                    codeFormation +
                    domaineLibelle +
                    niveauLibelle +
                    exigence +
                     '</span></br>';
            }
        }

        divModal2.innerHTML += offer.dureeTravailLibelle !== '' ? '<span class="badge rounded-pill text-bg-secondary-dark">' + offer.dureeTravailLibelle + '</span>': '';
        divModal2.innerHTML += offer.dureeTravailLibelleConverti !== '' ? '<span class="badge rounded-pill text-bg-secondary-dark">' + offer.dureeTravailLibelleConverti + '</span>' : '';

        divModal2.innerHTML += offer.salaire.libelle !== '' ? '<span class="badge rounded-pill text-bg-secondary-dark">Salaire : ' + offer.salaire.libelle + '</span></br>' : '';

        divModal2.innerHTML += offer.romeCode !== '' ? '<span class="badge rounded-pill text-bg-primary">Rome : ' + offer.romeCode + '</span> ' : '';
        divModal2.innerHTML += offer.romeLibelle !== '' ? '<span class="badge rounded-pill text-bg-secondary">Rome : ' + offer.romeLibelle + '</span> ' : '';
        divModal2.innerHTML += offer.appellationlibelle !== '' ? '<span class="badge rounded-pill text-bg-secondary">' + offer.appellationlibelle + '</span> ' : '';

        divModal3.innerHTML = '';
        divModal3.innerHTML += offer.lieuTravail.libelle !== '' ? '<span class="text-secondary">Lieu :</span> ' + offer.lieuTravail.libelle + '</br>': '';
        divModal3.innerHTML += offer.entreprise.nom !== '' ? '<span class="text-secondary">Entreprise : </span>' + offer.entreprise.nom  + '</br>': '';
        divModal3.innerHTML += offer.entreprise.entrepriseAdaptee === true ? '<span class="badge rounded-pill text-bg-secondary">Entreprise adaptee</span></br>' : '';
        divModal3.innerHTML += '<a target="_blank" href="' + offer.origineOffre.urlOrigine + '" class="link-02">Lien offre France Travail</a> ';

        divModal.appendChild(divModal1);
        divModal.appendChild(divModal2);
        divModal.appendChild(divModal3);

        offer.contact.nom = offer.contact.nom !== null ? offer.contact.nom : '';
        offer.contact.coordonnees1 = offer.contact.coordonnees1 !== null ? offer.contact.coordonnees1 : '';
        offer.contact.urlPostulation = offer.contact.urlPostulation !== null ? offer.contact.urlPostulation : '';

        if(offer.contact.nom !== '' || offer.contact.coordonnees1 !== '' || offer.contact.urlPostulation !== '') {

            divModal4.innerHTML = '';
            if(offer.contact.nom !== '') {
                divModal4.innerHTML += '<span class="text-secondary">Contact :</span> ' + offer.contact.nom + '</br>';
            }
            if(offer.contact.coordonnees1 !== '') {
                if(offer.contact.coordonnees1.startsWith('https://') || offer.contact.coordonnees1.startsWith('http://'))
                {
                    divModal4.innerHTML += '<a target="_blank" href="' + offer.contact.coordonnees1 + '" class="link-02">Lien coordonnées</a></br>';
                }
                else {
                    let link = '';
                    const coordonneesTab = offer.contact.coordonnees1.split(' ');
                    coordonneesTab.forEach(word => {
                        if (word.startsWith('https://') || word.startsWith('http://')) {
                            link = word;
                        }
                    })
                    if(link !== '')divModal4.innerHTML += '<a target="_blank" href="' + link + '" class="link-02">Lien coordonnées</a>';
                }
            }
            if(offer.contact.urlPostulation !== '') {
                divModal4.innerHTML += '<a target="_blank" href="' + offer.contact.urlPostulation + '" class="link-02">Postuler</a>';
            }

            divModal.appendChild(divModal4);

        }

        
    }

    updateOffersSortButtons() {
        const btnDistance = document.getElementById('btn-sort-offers-distance');
        const btnDate = document.getElementById('btn-sort-offers-date');
        const btnPertinence = document.getElementById('btn-sort-offers-pertinence');
        //console.log('updateOffersSortButtons : ', this.sort);
        if(btnDistance && btnDate && btnPertinence) {
            switch (this.sort)
            {
                case 2:
                    btnDistance.classList.add('btn-active');
                    btnDate.classList.remove('btn-active');
                    btnPertinence.classList.remove('btn-active');
                    break;
                case 1:
                    btnDistance.classList.remove('btn-active');
                    btnDate.classList.add('btn-active');
                    btnPertinence.classList.remove('btn-active');
                    break;
                case 0:
                    btnDistance.classList.remove('btn-active');
                    btnDate.classList.remove('btn-active');
                    btnPertinence.classList.add('btn-active');
                    break;
            }
        }
    }

    displayOffers(townCode, typesContrats, domaines, metiers, codesNaf, viewOfferCB) {
        //console.log('display offers : offers : ', offers);
        //console.log('filters : ', filters);
        //console.log('typesContrats : ', typesContrats);
        //console.log('metiers : ', metiers);
        const divEmployment = document.getElementById('map-accordion-body-employment');
        if(divEmployment) {
            divEmployment.innerHTML = '';
            const title = document.createElement('h6');
            title.classList.add('text-white', 'text-center', 'mb-2');
            title.textContent = 'Offres d\'emploi de France Travail';
            divEmployment.appendChild(title);

            const table = document.createElement('table');
            table.id = 'table-offers';
            table.classList.add('display', 'mb-2', 'table', 'table-responsive', 'table-offers');
            //table.style.width = '100%';
            divEmployment.appendChild(table);
            //console.log('sort : ', this.employmentService.getSort());
            
            
            //let sort = this.employmentService.getSort();
            //let service = this.employmentService;
            let self = this;
            //console.log('townCode : ', townCode);
            new DataTable('#table-offers', {
                //dom: 'lBStip',
                dom: '<"row mb-3 align-items-center"<"col-sm-3"l><"col-sm-9"<"d-flex justify-content-center gap-1"B>><"custom-selects-container">>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row justify-content-center gap-2 mb-3"<"col-sm-auto"i><"col-sm-auto custom-pagination"p>>',
                language: {
                    lengthMenu: "_MENU_ &nbsp;&nbsp;résultats par page",
                    info: "Affichage de _START_ à _END_ sur _TOTAL_ résultats",
                    infoEmpty: "Aucun résultat disponible",
                    infoFiltered: "(filtré de _MAX_ résultats au total)",
                    zeroRecords: "Aucun résultat trouvé",
                    paginate: {
                        first: "&laquo;",
                        last: "&raquo;",
                        next: "&rsaquo;",
                        previous: "&lsaquo;"
                    }

                },
                ordering: false,
                buttons: [
                    {
                        text: 'Distance',
                        attr: {
                            id: 'btn-sort-offers-distance'
                        },
                        action: function (e, dt, node, config) {
                            //console.log('btn-sort-offers-distance');
                            //sort = 2;
                            //self.sortValue = 2;
                            self.sort = 2;
                            self.updateOffersSortButtons();
                            dt.ajax.reload();
                        }
                    },
                    {
                        text: 'Date',
                        attr: {
                            id: 'btn-sort-offers-date'
                        },
                        action: function (e, dt, node, config) {
                            //console.log('btn-sort-offers-date');
                            //sort = 1;
                            //self.sortValue = 1;
                            self.sort = 1;
                            self.updateOffersSortButtons();
                            dt.ajax.reload();
                        }
                    },
                    {
                        text: 'Pertinence',
                        attr: {
                            id: 'btn-sort-offers-pertinence'
                        },
                        action: function (e, dt, node, config) {
                            //console.log('btn-sort-offers-pertinence');
                            //sort = 0;
                            //self.sortValue = 0;
                            self.sort = 0;
                            self.updateOffersSortButtons();
                            dt.ajax.reload();
                        }
                    }
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/get-employment-by-town-for-datatable',
                    type: 'POST',
                    contentType: 'application/x-www-form-urlencoded',
                    data: function(d) {
                        return $.param({
                            townCode: townCode,
                            start: d.start,
                            length: d.length,
                            draw: d.draw,
                            sort: self.sort,
                            filters: self.filtersTypesContrats + self.filtersDomaines + self.filtersMetiersRome + self.filtersCodesNaf
                        });
                    }
                },
                columns: [
                    { title: 'Id' },
                    { title: 'Intitulé' },
                    { title: 'Lieu' },
                    { title: 'Rome' },
                    { title: 'Contrat' },
                    { title: 'Secteur' },
                ],
                initComplete: function(settings, json) {
                    var selectTypesHtml = '<select id="custom-select-types" class="form-select me-2">' +
                    '<option value="" disabled>Filtrer par type de contrat</option>' + 
                    '<option value="">Tous les types</option>';
                    typesContrats.forEach(typeContrat => {
                        selectTypesHtml += '<option value="' + typeContrat.code + '">'  + typeContrat.code + ' - ' + typeContrat.libelle + '</option>';
                    });               
                    selectTypesHtml += '</select>';
            
                    var selectDomainsHtml = '<select id="custom-select-domaines" class="form-select">' +
                    '<option value="" disabled>Filtrer par domaine</option>' + 
                    '<option value="">Tous les domaines</option>';
                    domaines.forEach(domaine => {
                        selectDomainsHtml += '<option value="' + domaine.code + '">' + domaine.libelle + '</option>';
                    });               
                    selectDomainsHtml += '</select>';

                    var selectMetiersRomeHtml = '<select id="custom-select-metiers-rome" class="form-select">' +
                    '<option value="" disabled>Filtrer par métier Rome</option>' + 
                    '<option value="">Tous les métiers</option>';
                    metiers.forEach(metier => {
                    selectMetiersRomeHtml += '<option value="' + metier.code + '">' + metier.code + ' - ' + metier.libelle + '</option>';
                    });               
                    selectMetiersRomeHtml += '</select>';
            


                    var selectCodesNafHtml = '<select id="custom-select-codes-naf" class="form-select">' +
                    '<option value="" disabled>Filtrer par code NAF</option>' + 
                    '<option value="">Tous les codes</option>';
                    codesNaf.forEach(codeNaf => {
                        selectCodesNafHtml += '<option value="' + codeNaf.code + '">' + codeNaf.code + ' - ' + codeNaf.libelle + '</option>';
                    });               
                    selectCodesNafHtml += '</select>';

                    $('.custom-selects-container').append(selectTypesHtml + selectMetiersRomeHtml + selectCodesNafHtml + selectDomainsHtml);

                    /*

                    $('#custom-select-types').select2({
                        placeholder: "Filtrer par type de contrat",
                        allowClear: true,
                        width: 'resolve', // Cela permettra au select de s'adapter à la largeur de son conteneur
                        theme: "classic", // Vous pouvez changer le thème si nécessaire
                        language: {
                            noResults: function() {
                                return "Aucun résultat trouvé";
                            }
                        },
                        escapeMarkup: function (markup) {
                            return markup;
                        }
                    });

                    $('#custom-select-domaines').select2({
                        placeholder: "Filtrer par domaine pro",
                        allowClear: true,
                        width: 'resolve', // Cela permettra au select de s'adapter à la largeur de son conteneur
                        theme: "classic", // Vous pouvez changer le thème si nécessaire
                        language: {
                            noResults: function() {
                                return "Aucun résultat trouvé";
                            }
                        },
                        escapeMarkup: function (markup) {
                            return markup;
                        }
                    });

                    $('#custom-select-metiers-rome').select2({
                        placeholder: "Filtrer par métier Rome",
                        allowClear: true,
                        width: 'resolve', // Cela permettra au select de s'adapter à la largeur de son conteneur
                        theme: "dark", // Vous pouvez changer le thème si nécessaire
                        language: {
                            noResults: function() {
                                return "Aucun résultat trouvé";
                            }
                        },
                        escapeMarkup: function (markup) {
                            return markup;
                        }
                    });*/

                    
                    $('#custom-select-types').select2({
                        placeholder: "Type de Contrat",
                        allowClear: true,
                        width: 'style',
                        language: {
                            noResults: function() {
                                return "Aucun résultat trouvé";
                            }
                        },
                        escapeMarkup: function (markup) {
                            return markup;
                        }
                    });

                    $('#custom-select-domaines').select2({
                        placeholder: "Domaine Pro",
                        allowClear: true,
                        width: 'style',
                        language: {
                            noResults: function() {
                                return "Aucun résultat trouvé";
                            }
                        },
                        escapeMarkup: function (markup) {
                            return markup;
                        }
                    });

                    $('#custom-select-metiers-rome').select2({
                        placeholder: "Métier Rome",
                        allowClear: true,
                        width: 'style',
                        language: {
                            noResults: function() {
                                return "Aucun résultat trouvé";
                            }
                        },
                        escapeMarkup: function (markup) {
                            return markup;
                        }
                    });


                    $('#custom-select-codes-naf').select2({
                        placeholder: "Code NAF",
                        allowClear: true,
                        width: 'style',
                        language: {
                            noResults: function() {
                                return "Aucun résultat";
                            }
                        },
                        escapeMarkup: function (markup) {
                            return markup;
                        }
                    });
                    

                    $('#custom-select-metiers-rome').on('change', function() {
                        //console.log('custom-select-metiers-rome');
                        var selectedValue = $(this).val();
                        self.filtersMetiersRome = selectedValue ? '&codeROME=' + selectedValue : '';
                        //console.log(self.filtersMetiersRome);
                        settings.oInstance.api().ajax.reload();
                    });
            
                    $('#custom-select-types').on('change', function() {
                        var selectedValue = $(this).val();
                        self.filtersTypesContrats = selectedValue ? '&typeContrat=' + selectedValue : '';
                        settings.oInstance.api().ajax.reload();
                    });
            
                    $('#custom-select-domaines').on('change', function() {
                        var selectedValue = $(this).val();
                        self.filtersDomaines = selectedValue ? '&domaine=' + selectedValue : '';
                        settings.oInstance.api().ajax.reload();
                    });

                    $('#custom-select-codes-naf').on('change', function() {
                        //console.log('test');
                        var selectedValue = $(this).val();
                        self.filtersCodesNaf = selectedValue ? '&codeNAF=' + selectedValue : '';
                        settings.oInstance.api().ajax.reload();
                    });
                },

                createdRow: function(row, data, dataIndex) {
                    $(row).on('click', function() {
                        viewOfferCB(data[0]); // Supposons que l'ID est dans la première colonne
                    });
                },
            });
            //initOffersSortButtonsCB();
        }
    }

    refreshOffersDataTable() {
        const table = document.getElementById('table-offers');
        if(table) {
            table.ajax.reload();
        }
    }

    /*

    setSort(sort) {
        this.sortValue = sort;
    }

    getSort() {
        return this.sortValue;
    }
    */

    /*

sort
string

Il est possible de trier les résultats de 3 façons :

    Pertinence décroissante , distance croissante, date de création horodatée décroissante, origine de l’offre : sort=0
    Date de création horodatée décroissante, pertinence décroissante, distance croissante, origine de l’offre : sort=1
    Distance croissante, pertinence décroissante, date de création horodatée décroissante, origine de l’offre : sort=2 

*/

    displaySchools(schools, schoolsNb, limit, offset, callBackPrev, callBackNext, callBackFirst, callBackLast, callBackViewSchool, callBackCenterMapOnSchool) {
        //map-accordion-body-schools
        const pageMaxNb = Math.floor(schoolsNb / limit) + 1;
        const pageNb = Math.floor(offset / limit) + 1;
        const schoolsDiv = document.getElementById('map-accordion-body-school');
        if(schoolsDiv) {
            schoolsDiv.innerHTML = '';
            const paragraph = document.createElement('p');
            paragraph.classList.add('text-white');
            paragraph.textContent = 'Etablissements scolaires (' + schoolsNb + ')';
            schoolsDiv.appendChild(paragraph);
            if(schools.length === 0) {
                const noSchoolsDiv = document.createElement('div');
                const paragraph = document.createElement('p');
                paragraph.classList.add('text-white');
                paragraph.textContent = 'Aucun établissement scolaire.';
                noSchoolsDiv.appendChild(paragraph);
                schoolsDiv.appendChild(noSchoolsDiv);
            }
            else {
                let cpt = 0;
                schools.forEach(school => {
                    cpt++;
                    const schoolDiv = document.createElement('div');
                    schoolDiv.classList.add('div-school');
                    schoolDiv.innerHTML = '<strong class="text-in-circle">' + cpt + '</strong>&nbsp;&nbsp;';
                    schoolDiv.innerHTML += `<strong class="text-secondary text-medium title-div-school">${school.nom_etablissement}</strong>`;
                    schoolDiv.innerHTML += '</br>' + school.type_etablissement;
                    schoolDiv.innerHTML += school.statut_public_prive !== '' ? '&nbsp;<span class="text-secondary">•</span>&nbsp;' + school.statut_public_prive : '';
                    //const libelleNature = school.libelle_nature
                    //.toLowerCase() // Met toute la chaîne en minuscules
                    //.split(' ') // Divise la chaîne en mots
                    //.map(word => word.charAt(0).toUpperCase() + word.slice(1)) // Met la première lettre de chaque mot en majuscule
                    //.join(' ');
                    schoolDiv.innerHTML += '</br><em class="badge rounded-pill text-bg-secondary">' + this.formatTextToCamelCase(school.libelle_nature) + '</em>';
                    
                    const buttonViewSchool = document.createElement('button');
                    buttonViewSchool.classList.add('btn');
                    buttonViewSchool.classList.add('btn-tooltip');
                    buttonViewSchool.classList.add('btn-secondary-very-small');
                    buttonViewSchool.classList.add('btn-text-small');
                    buttonViewSchool.classList.add('btn-view-school');
                    buttonViewSchool.textContent = '📄';
                    buttonViewSchool.setAttribute("data-tooltip", "Voir les informations de l'établissement.");
                    buttonViewSchool.onclick = () => {
                        //console.log('viewSchool : ', school);
                        callBackViewSchool(school);
                    }
                    schoolDiv.appendChild(buttonViewSchool);

                    const buttonCenterSchool = document.createElement('button');
                    buttonCenterSchool.classList.add('btn');
                    buttonCenterSchool.classList.add('btn-tooltip');
                    buttonCenterSchool.classList.add('btn-secondary-very-small');
                    buttonCenterSchool.classList.add('btn-text-small');
                    buttonCenterSchool.classList.add('btn-center-school');
                    buttonCenterSchool.textContent = '⊕';
                    buttonCenterSchool.setAttribute("data-tooltip", "Centrer la carte sur l'établissement.");
                    buttonCenterSchool.onclick = () => {
                        //console.log('viewSchool : ', school);
                        callBackCenterMapOnSchool(school);
                    }
                    schoolDiv.appendChild(buttonCenterSchool);
                    schoolsDiv.appendChild(schoolDiv);
                });
            }

            const divButtons = document.createElement('div');
            divButtons.classList.add('div-school-buttons');

            const divButtonsStart = document.createElement('div');
            divButtonsStart.classList.add('div-school-buttons-start');

            const buttonFirst = document.createElement('button');
            buttonFirst.classList.add('btn');
            buttonFirst.classList.add('btn-primary-small');
            buttonFirst.classList.add('btn-tooltip');
            buttonFirst.setAttribute("data-tooltip", "Première page.");
            buttonFirst.innerHTML = '&laquo;';
            buttonFirst.onclick = () => {
                //this.displaySchools(schools, 0);
                callBackFirst();
            }
            divButtonsStart.appendChild(buttonFirst);
            divButtons.appendChild(divButtonsStart);

            const buttonPrev = document.createElement('button');
            buttonPrev.classList.add('btn');
            buttonPrev.classList.add('btn-primary-small');
            buttonPrev.classList.add('btn-tooltip');
            buttonPrev.setAttribute("data-tooltip", "Page précédente.");
            buttonPrev.innerHTML = '&lsaquo;';
            buttonPrev.onclick = () => {
                //this.displaySchools(schools, schoolNb + 1);
                callBackPrev();
            }

            divButtonsStart.appendChild(buttonPrev);
            divButtons.appendChild(divButtonsStart);

            const paraPageNb = document.createElement('div');
            paraPageNb.classList.add('text-secondary');
            paraPageNb.textContent = 'Page : ' + pageNb + '/' + pageMaxNb;
            divButtons.appendChild(paraPageNb);

            const divButtonsEnd = document.createElement('div');
            divButtonsEnd.classList.add('div-school-buttons-end');
            const buttonNext = document.createElement('button');
            buttonNext.classList.add('btn');
            buttonNext.classList.add('btn-primary-small');
            buttonNext.classList.add('btn-tooltip');
            buttonNext.setAttribute("data-tooltip", "Page suivante.");
            buttonNext.innerHTML = '&rsaquo;';
            buttonNext.onclick = () => {
                //this.displaySchools(schools, schoolNb + 1);
                callBackNext();
            }
            divButtonsEnd.appendChild(buttonNext);

            const buttonLast = document.createElement('button');
            buttonLast.classList.add('btn');
            buttonLast.classList.add('btn-primary-small');
            buttonLast.classList.add('btn-tooltip');
            buttonLast.setAttribute("data-tooltip", "Dernière page.");
            buttonLast.innerHTML = '&raquo;';
            buttonLast.onclick = () => {
                //this.displaySchools(schools, 0);
                callBackLast();
            }
            divButtonsEnd.appendChild(buttonLast);

            divButtons.appendChild(divButtonsEnd);

            
            schoolsDiv.appendChild(divButtons);
        }
    }

    displayForecast(forecastInfos, day) {
        //map-accordion-body-forecast
        const forecastDiv = document.getElementById(`forecast-div-${day}`);
        //console.log('forecastInfos : ', forecastInfos.forecast);
        if(forecastInfos && forecastDiv) {
            forecastDiv.innerHTML = '';
            const dateTime = forecastInfos.datetime;

            const wind10m = forecastInfos.wind10m;
            const gust10m = forecastInfos.gust10m;
            const dirWind10m = forecastInfos.dirwind10m;

            const tMin = forecastInfos.tmin;
            const tMax = forecastInfos.tmax;
            const sunHours = forecastInfos.sun_hours;
            const weatherCode = forecastInfos.weather;

            const probaRain = forecastInfos.probarain;
            const probaFrost = forecastInfos.probafrost;
            const probaFog = forecastInfos.probafog;
            const probaWind70 = forecastInfos.probawind70;
            const probaWind100 = forecastInfos.probawind100;


            const rr10 = forecastInfos.rr10;
            const rr1 = forecastInfos.rr1;
            const gustx = forecastInfos.gustx;
            const etp = forecastInfos.etp;
            const day = forecastInfos.day;

            //console.log('weatherIcon class : ', WeatherLibrary.getWeatherIconClassByCode(weatherCode));
            const divDate = document.createElement('p');
            divDate.classList.add('div-forecast-date');
            divDate.innerHTML = `Jour : <span class="text-secondary">${day}</span> • Date : <span class="text-secondary">${dateTime}</span>`;
            forecastDiv.appendChild(divDate);

            const cardContainer = document.createElement('div');
            cardContainer.classList.add('card-forecast-container');
            //cardContainer.classList.add('d-flex');
            //cardContainer.classList.add('justify-content-between');
            
            //cardContainer.classList.add('flex-wrap');
            //cardContainer.classList.add('row');
            //cardContainer.classList.add('gap-3');
            //cardContainer.classList.add('text-center');

            const cardWeather = document.createElement('div');
            cardWeather.classList.add('card-forecast');
            
            const weatherIcon = document.createElement('i');
            weatherIcon.classList.add('wi');
            weatherIcon.classList.add('icon-weather');
            weatherIcon.classList.add(WeatherLibrary.getWeatherIconClassByCode(weatherCode));
            cardWeather.appendChild(weatherIcon);

            const paragWeatherCategory = document.createElement('p');
            paragWeatherCategory.classList.add('text-white');
            paragWeatherCategory.classList.add('text-small');
            paragWeatherCategory.innerHTML = '<span class="text-secondary">' + WeatherLibrary.getWeatherNameByCode(weatherCode) + '</span>';
            cardWeather.appendChild(paragWeatherCategory);

            cardContainer.appendChild(cardWeather);

            const cardWind = document.createElement('div');
            cardWind.classList.add('card-forecast');

            const windIcon = document.createElement('i');
            windIcon.classList.add('wi');
            windIcon.classList.add('wi-wind');
            windIcon.classList.add('icon-wind');
            windIcon.classList.add(WeatherLibrary.getWindDirectionClassByDir(dirWind10m));
            cardWind.appendChild(windIcon);

            const paragWind = document.createElement('p');
            paragWind.classList.add('text-small');
            paragWind.classList.add('text-white');
            paragWind.innerHTML = '10m : <span class="text-secondary">' + wind10m + ' km/h </span><br />Rafale : <span class="text-secondary">' + gust10m + ' km/h</span>';
            cardWind.appendChild(paragWind);

            cardContainer.appendChild(cardWind);

            /*
            const iconWind = createMarkup("i", "", titleMeteo, [{class:"wi wi-wind text-white ms-2"}, {id: "icon-wind-dir"}]);
            iconWind.classList.add(WeatherLib.getWindDirectionClassByDir(meteoDatas.forecast.dirwind10m));
            */
            
            
            const classTempMin = WeatherLibrary.getColorClassByTemp(tMin);
            const classTempMax = WeatherLibrary.getColorClassByTemp(tMax);

            const cardTemp = document.createElement('div');
            cardTemp.classList.add('card-forecast');

            //wi-thermometer
            const tempIcon = document.createElement('i');
            tempIcon.classList.add('wi');
            tempIcon.classList.add('wi-thermometer');
            tempIcon.classList.add('icon-temp');
            cardTemp.appendChild(tempIcon);
            const paragTemp = document.createElement('p');
            paragTemp.classList.add('text-small');
            paragTemp.classList.add('text-small-interligne');
            paragTemp.innerHTML = `<span class="text-white">min : <span class="${classTempMin} text-big">${tMin}</span> °C</span></br><span class="text-white">max : <span class="${classTempMax} text-big">${tMax}</span> °C</span>`;
            cardTemp.appendChild(paragTemp);
            cardContainer.appendChild(cardTemp);

            const cardSunHours = document.createElement('div');
            cardSunHours.classList.add('card-forecast');
            //wi-day-sunny
            const sunHoursIcon = document.createElement('i');
            sunHoursIcon.classList.add('wi');
            sunHoursIcon.classList.add('wi-day-sunny');
            sunHoursIcon.classList.add('icon-sun-hours');
            cardSunHours.appendChild(sunHoursIcon);
            const paragSunHours = document.createElement('p');
            paragSunHours.classList.add('text-white');
            paragSunHours.classList.add('text-small');
            paragSunHours.innerHTML = 'Ensoleillement : <span class="text-secondary text-big">' + sunHours + 'h</span>';
            cardSunHours.appendChild(paragSunHours);
            cardContainer.appendChild(cardSunHours);

            /** */

            const cardRainCumul = document.createElement('div');
            cardRainCumul.classList.add('card-forecast');
            const rainCumulIcon = document.createElement('i');
            rainCumulIcon.classList.add('wi');
            rainCumulIcon.classList.add('wi-raindrop');
            rainCumulIcon.classList.add('icon-rain-cumul');
            cardRainCumul.appendChild(rainCumulIcon);
            const paragRainCumul = document.createElement('p');
            paragRainCumul.classList.add('text-small');
            paragRainCumul.innerHTML = '<span class="text-white"> Pluie :</br><span class="text-secondary text-big">' + rr10 + ' mm</span></span>';
            cardRainCumul.appendChild(paragRainCumul);
            cardContainer.appendChild(cardRainCumul);

            const cardRainCumulMax = document.createElement('div');
            cardRainCumulMax.classList.add('card-forecast');
            const rainCumulMaxIcon = document.createElement('i');
            rainCumulMaxIcon.classList.add('wi');
            rainCumulMaxIcon.classList.add('wi-raindrop');
            rainCumulMaxIcon.classList.add('icon-rain-cumul');
            cardRainCumulMax.appendChild(rainCumulMaxIcon);
            const paragRainCumulMax = document.createElement('p');
            paragRainCumulMax.classList.add('text-small');
            paragRainCumulMax.innerHTML = '<span class="text-white"> Pluie max. :</br><span class="text-secondary text-big">' + rr1 + ' mm</span></span>';
            cardRainCumulMax.appendChild(paragRainCumulMax);
            cardContainer.appendChild(cardRainCumulMax);

            const cardProbaRain = document.createElement('div');
            cardProbaRain.classList.add('card-forecast');
            const probaRainIcon = document.createElement('i');
            probaRainIcon.classList.add('wi');
            probaRainIcon.classList.add('wi-raindrops');
            probaRainIcon.classList.add('icon-proba-rain');
            cardProbaRain.appendChild(probaRainIcon);
            const paragProbaRain = document.createElement('p');
            paragProbaRain.classList.add('text-small');
            paragProbaRain.innerHTML = '<span class="text-white"> Proba. Pluie :</br><span class="text-secondary text-big">' + probaRain + '%</span></span>';
            cardProbaRain.appendChild(paragProbaRain);
            cardContainer.appendChild(cardProbaRain);

            const cardProbaFrost = document.createElement('div');
            cardProbaFrost.classList.add('card-forecast');
            const probaFrostIcon = document.createElement('i');
            probaFrostIcon.classList.add('wi');
            probaFrostIcon.classList.add('wi-snowflake-cold');
            probaFrostIcon.classList.add('icon-proba-frost');
            cardProbaFrost.appendChild(probaFrostIcon);
            const paragProbaFrost = document.createElement('p');
            paragProbaFrost.classList.add('text-small');
            paragProbaFrost.innerHTML = '<span class="text-white"> Proba. Gel :</br><span class="text-secondary text-big">' + probaFrost + '%</span></span>';
            cardProbaFrost.appendChild(paragProbaFrost);
            cardContainer.appendChild(cardProbaFrost);

            const cardProbaFog = document.createElement('div');
            cardProbaFog.classList.add('card-forecast');
            const probaFogIcon = document.createElement('i');
            probaFogIcon.classList.add('wi');
            probaFogIcon.classList.add('wi-fog');
            probaFogIcon.classList.add('icon-proba-fog');
            cardProbaFog.appendChild(probaFogIcon);
            const paragProbaFog = document.createElement('p');
            paragProbaFog.classList.add('text-small');
            paragProbaFog.innerHTML = '<span class="text-white"> Proba. Brume :</br><span class="text-secondary text-big">' + probaFog + '%</span></span>';
            cardProbaFog.appendChild(paragProbaFog);
            cardContainer.appendChild(cardProbaFog);

            const cardProbaWind70 = document.createElement('div');
            cardProbaWind70.classList.add('card-forecast');
            const probaWind70Icon = document.createElement('i');
            probaWind70Icon.classList.add('wi');
            probaWind70Icon.classList.add('wi-strong-wind');
            probaWind70Icon.classList.add('icon-proba-wind70');
            cardProbaWind70.appendChild(probaWind70Icon);   
            const paragProbaWind70 = document.createElement('p');
            paragProbaWind70.classList.add('text-small');
            paragProbaWind70.innerHTML = '<span class="text-white"> Proba. Vent >= 70 km/h :</br><span class="text-secondary text-big">' + probaWind70 + '%</span></span>';
            cardProbaWind70.appendChild(paragProbaWind70);
            cardContainer.appendChild(cardProbaWind70);

            const cardProbaWind100 = document.createElement('div');
            cardProbaWind100.classList.add('card-forecast');
            const probaWind100Icon = document.createElement('i');
            probaWind100Icon.classList.add('wi');
            probaWind100Icon.classList.add('wi-strong-wind');
            probaWind100Icon.classList.add('icon-proba-wind100');
            cardProbaWind100.appendChild(probaWind100Icon);
            const paragProbaWind100 = document.createElement('p');
            paragProbaWind100.classList.add('text-small');
            paragProbaWind100.innerHTML = '<span class="text-white"> Proba. Vent >= 100 km/h :</br><span class="text-secondary text-big">' + probaWind100 + '%</span></span>';
            cardProbaWind100.appendChild(paragProbaWind100);
            cardContainer.appendChild(cardProbaWind100);

            const cardWindTempest = document.createElement('div');
            cardWindTempest.classList.add('card-forecast');
            const windTempestIcon = document.createElement('i');
            windTempestIcon.classList.add('wi');
            windTempestIcon.classList.add('wi-hurricane');
            windTempestIcon.classList.add('icon-wind-tempest');
            cardWindTempest.appendChild(windTempestIcon);
            const paragWindTempest = document.createElement('p');
            paragWindTempest.classList.add('text-small');
            paragWindTempest.innerHTML = '<span class="text-white">Tempête :</br><span class="text-secondary text-big">' + gustx + ' km/h</span></span>';
            cardWindTempest.appendChild(paragWindTempest);
            cardContainer.appendChild(cardWindTempest);

            forecastDiv.appendChild(cardContainer);

        }
        
    }

    formatTextToCamelCase(text) {
        return text
        .toLowerCase()
        .split(' ')
        //.filter(word => word !== '' && word !== 'de' && word !== 'des' && word !== 'd\'' && word !== 'd' && word !== 'la' && word !== 'le' && word !== 'les' && word !== 'l\'' && word !== 'l' && word !== 'en' && word !== 'et' && word !== 'un' && word !== 'une' && word !== 'pour')
        .map(word => {
            if(word !== '' && word !== 'de' && word !== 'des' && word !== 'd\'' && word !== 'd' && word !== 'la' && word !== 'le' && word !== 'les' && word !== 'l\'' && word !== 'l' && word !== 'en' && word !== 'et' && word !== 'un' && word !== 'une' && word !== 'pour' && word !== 'ou' && word !== 'du') {
                if(word.startsWith('l\'') || word.startsWith('d\'')) {
                    return word.charAt(0) + word.charAt(1) + word.charAt(2).toUpperCase() + word.slice(3)
                    //return word.charAt(0).toUpperCase() + word.slice(1)
                }
                else {
                    return word.charAt(0).toUpperCase() + word.slice(1)
                }
                
                
            }
            else {
                return word
            }
            //return word.charAt(0).toUpperCase() + word.slice(1);
        })
        .join(' ');
    }

    formatDateFR(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    formatLongText(text) {
        const textWords = text.split(' ');
        let newText = '';
        for(let i = 0; i < textWords.length; i++) {
            // remplacer '\n' par '</br>'
            let newWord = '';
            for(let j = 0; j < textWords[i].length; j++) {
                if((j < textWords[i].length - 1) && textWords[i].charAt(j) === '\\' && textWords[i].charAt(j + 1) === 'n') {
                    newWord += '</br>';
                }
                else {
                    newWord += textWords[i].charAt(j);
                }
            }
            

            textWords[i] = newWord;

            if(textWords[i].charAt(textWords[i].length - 1) === '.'
            || textWords[i].charAt(textWords[i].length - 1) === '!'
            || textWords[i].charAt(textWords[i].length - 1) === '?') {
                newText += textWords[i] + '</br></br>';
            }
            else if(textWords[i].charAt(0) === '-') {
                newText += '</br>' + textWords[i] ;
            }
            else {
                newText += textWords[i] + ' ';
            }
        }
        return newText;
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
        "day": 0, //Jour entre 0 et 13 (Pour le jour même : 0, pour le lendemain : 1, etc.)
        "datetime": "2020-10-29T01:00:00+0100",
        "wind10m": 15, //Vent moyen à 10 mètres en km/h
        "gust10m": 49, //Rafales de vent à 10 mètres en km/h
        "dirwind10m": 230, //Direction du vent en degrés (0 à 360°)
        "rr10": 0.2, //Cumul de pluie sur la journée en mm
        "rr1": 0.3, //Cumul de pluie maximal sur la journée en mm
        "probarain": 40,
        "weather": 4,
        "tmin": 11,
        "tmax": 17,
        "sun_hours": 1, //Ensoleillement en heures
        "etp": 1, //Cumul d'évapotranspiration en mm
        "probafrost": 0,
        "probafog": 0,
        "probawind70": 0,
        "probawind100": 0,
        "gustx": 49 //Rafale de vent potentielle sous orage ou grain en km/h
    }
}
   */