import { useQuery } from "react-query";
import api from "../../services/api";



// Requests
const requestSchools = () => {
    let path ="/school-identification";
  
    return api
      .get(path)
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
