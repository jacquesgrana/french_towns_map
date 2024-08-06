class CommentService {

    static instance = null;
    static getInstance() {
        if (CommentService.instance == null) {
            CommentService.instance = new CommentService();
        }
        return CommentService.instance;
    }

    // /get-comments-for-town
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

    // /get-comments-by-user
    async getUserComments() {
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

    async submitNewComment(title, comment, score, townId, csrfToken) {
        const result = await fetch('/submit-new-comment', {
            credentials: 'include',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                _title: title,
                _comment: comment,
                _score: score,
                _town_id: townId,
                _csrf_token: csrfToken
            })
        });
        //const data = await result.json();
    } 

    async updateComment(commentId, title, comment, score, townId, csrfToken) {
        const result = await fetch('/submit-updated-comment', {
            credentials: 'include',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                _comment_id: commentId,
                _title: title,
                _comment: comment,
                _score: score,
                //_town_id: townId,
                _csrf_token: csrfToken
            })
        });
        //const data = await result.json();
    }
    
    async deleteComment(commentId) {
        const result = await fetch('/delete-comment', {
            credentials: 'include',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                commentId: commentId
            })
        });
        //const data = await result.json();
    }

}