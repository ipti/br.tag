class User {
    constructor(){
        this.id = sessionStorage.getItem('user');
        this.name = sessionStorage.getItem('user_name');
        this.email = sessionStorage.getItem('user_email');
        this.access_token = sessionStorage.getItem('token');
        this.institution = sessionStorage.getItem('institution');
        this.institutionType = sessionStorage.getItem('institution_type');
    }
}

export default (new User);