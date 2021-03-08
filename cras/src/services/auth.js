export const isAuthenticated = () => localStorage.getItem('token') !== null;

export const getToken = () => localStorage.getItem('token');

export const login = (data) => {
  localStorage.setItem('token', data.access_token);
  localStorage.setItem('user', JSON.stringify({ name: data.name, email: data.name }));
};

export const getUser = () => {
  const user = localStorage.getItem('user');
  return JSON.parse(user);
};

export const logout = () => {
  localStorage.removeItem('token');
  localStorage.removeItem('user');
};
