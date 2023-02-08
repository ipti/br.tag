export const isAuthenticated = () => localStorage.getItem("token") !== null;
export const getToken = () => localStorage.getItem("token");
export const getIdSchool = () => localStorage.getItem("id-school");
export const getIdStage = () => localStorage.getItem("id-stage");
export const login = token => {
  localStorage.setItem("token", token);
};
export const logout = () => {
  localStorage.removeItem("token");
};

export const idSchool = id => {
  localStorage.setItem("id-school", id)
}

export const idStage = id => {
  localStorage.setItem("id-stage", id)
}
