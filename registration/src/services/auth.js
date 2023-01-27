export const isAuthenticated = () => localStorage.getItem("token") !== null;
export const getToken = () => localStorage.getItem("token");
export const getIdSchool = () => localStorage.getItem("id-school");
export const login = token => {
  localStorage.setItem("token", token);
};
export const logout = () => {
  localStorage.removeItem("token");
};

export const idSchool = id => {
  localStorage.setItem("id-school", id)
}
