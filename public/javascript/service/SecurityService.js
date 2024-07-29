class SecurityService {

    static instance = null;
    isLoggedIn = null;
    userDetails = null;
    static getInstance() {
        if (SecurityService.instance == null) {
            SecurityService.instance = new SecurityService();
        }
        return SecurityService.instance;
    }

    checkAuthStatus = async () => {
        const result = await fetch('/check-auth');
        const data = await result.json();
        this.isLoggedIn = data.isLoggedIn;
        if(this.isLoggedIn) {this.userDetails = await this.getUserDetails();}
        else {this.userDetails = null;}        
    }

    getUserDetails = async () => {
        const result = await fetch('/get-user-details');
        this.userDetails = await result.json();
        return this.userDetails;
    }


}