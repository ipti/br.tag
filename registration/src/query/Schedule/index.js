import { useQuery } from "react-query";
import api from "../../services/api";



// Requests
const requestSchools = () => {
  let path = "/school-identification";

  return api
    .get(path)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

const requestScheculeOne = (id) => {
  let path = "/event-pre-registration" + id;
  return api
    .get(path)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

// Requests
const requestSchecule = () => {
  let path = "/event-pre-registration";

  return api
    .get(path, {
      params: {
        include: {
          school_identification: true,
        }
      }
    })
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

export const requestSaveEventPre = data => {
  return api
    .post("/event-pre-registration", data)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};






export const useFetchRequestSchools = () => {
  return useQuery(["useRequestsSchoolsSchedule"], () => requestSchools());
};

export const useFetchRequestSchecule = () => {
  return useQuery(["useRequestsSchecule"], () => requestSchecule());
};

export const useFetchRequestScheculeOne = (id) => {
  return useQuery(["useRequestsScheculeOne", id], () => requestScheculeOne(id));
};
