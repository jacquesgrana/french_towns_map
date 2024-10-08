class FavoriteService {

    static instance = null;
    static getInstance() {
        if (FavoriteService.instance == null) {
            FavoriteService.instance = new FavoriteService();
        }
        return FavoriteService.instance;
    }

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

    async toggleFavoriteForTown(townId) {
        const result = await fetch('/toggle-favorite-by-town', {
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

    async getUserFavorites() {
        const result = await fetch('/get-favorites-by-user', {
            credentials: 'include',
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        const data = await result.json();
        return data;
    }

}