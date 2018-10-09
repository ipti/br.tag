
let User = {
    id: sessionStorage.getItem('user'),
    name: sessionStorage.getItem('user_name'),
    email: sessionStorage.getItem('user_email'),
    access_token: sessionStorage.getItem('token'),
    institution: sessionStorage.getItem('institution'),
    institutionType: sessionStorage.getItem('institution_type'),
};

export default User;