import { useQuery } from "react-query";
import api from "../../services/api";


// Request student identification
const requestStudent = async id => {
    return await api
        .get("/student-pre-identify/studentidentification/" + id,
            {
                params: {
                    include: {
                        edcenso_city: true,
                    }
                }
            })
        .then(response => response.data)
        .catch(err => {
            throw err;
        });
};


// request stages classroom 
export const requestSchoolStages = async id => {
  return await api
        .get("/school-pre-registration/" + id,
            {
                params: {
                    year: 2023
                }
            })
        .then(response => response.data)
        .catch(err => {
            throw err;
        });
};



// registred pre identification
export const requestSaveRegistration = data => {
    return api
        .post("/student-pre-identification", data)
        .then(response => response.data)
        .catch(err => {
            throw err;
        });
};


// request all school
const requestSchoolList = async () => {
    return await api
      .get("/student-pre-identify/school", {
        params: {
          include: {
            classroom: true,
            calendar_event: true,
            event_pre_registration: true
          }
        }
      })
      .then(response => response.data)
      .catch(err => {
        throw err;
      });
  };

  const requestSchool = async id => {
    return await api
      .get("/student-pre-identify/school/" + id, {
        params: {
          include: {
            classroom: true,
            calendar_event: true
          }
        }
      })
      .then(response => response.data)
      .catch(err => {
        throw err;
      });
  };

  export const useFetchRequestStudent = ({ id }) => {
    return useQuery(["useRequestsStudent", id], () => requestStudent(id));
  };

  export const useFetchRequestSchoolRegistration = ({ id }) => {
    return useQuery(["useRequestsSchoolRegistration", id], () => requestSchool(id));
  };

  export const useFetchRequestSchoolList = () => {
    return useQuery(["useRequestSchoolList"], () => requestSchoolList());
  };
  

  export const useFetchRequestSchoolStages = ({ id }) => {
    return useQuery(["useRequestSchoolStages", id], () => requestSchoolStages(id));
  };