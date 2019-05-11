class User {
    constructor(){
        this.id = localStorage.getItem('user');
        this.name = localStorage.getItem('user_name');
        this.email = localStorage.getItem('user_email');
        this.access_token = localStorage.getItem('token');
        this.institution = localStorage.getItem('institution');
        this.institutionType = localStorage.getItem('institution_type');
    }
}

export default (new User);