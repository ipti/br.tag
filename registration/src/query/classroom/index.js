import { useQuery } from "react-query";
import api from "../../services/api";

// Requests
const requestClassrooms = () => {
  let path = "/classroom";
  return api
    .get(path)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

const requestClassroom = id => {
  return api
    .get("/classroom/" + id, {
      params: {
        include: {
          student_pre_identification: true
        }
      }
    })
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

const requestRegistration = id => {
  return api
    .get("/student-pre-identification/" + id, {
      params: {
        include: {
          edcenso_city: true,
          edcenso_uf: true
        }
      }
    })
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

export const requestSaveClassroom = data => {
  return api
    .post("/classroom", data)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

export const requestEditPreIdentification = (data, id) => {
  return api
    .put("/student-pre-identification/" + id, data)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};


export const requestUpdateRegistration = (data, id) => {
  console.log(data)
  if (data.student_identification) {
    return api
      .post("/student-pre-identifyregistered/registration/" + id, data)
      .then(response => response.data)
      .catch(err => {
        throw err;
      });
  }
  return api
    .post("/student-pre-identify/registration/" + id, data)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

export const requestUpdateClassroom = (data, id) => {
  return api
    .put("/classroom/" + id, data)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

export const useFetchRequestClassrooms = () => {
  return useQuery(["useRequestClassrooms"], () => requestClassrooms());
};

export const useFetchRequestClassroom = ({ id }) => {
  return useQuery(["useRequestClassroom", id], () => requestClassroom(id));
};

export const useFetchRequestRegistration = ({ id }) => {
  return useQuery(["useRequestRegistration", id], () => requestRegistration(id));
};