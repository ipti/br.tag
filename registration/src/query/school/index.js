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

  const requestRegistrations = () => {
    return api
      .get("/student-pre-identification")
      .then(response => response.data)
      .catch(err => {
        throw err;
      });
  };
  
  const requestSchool = id => {
    return api
      .get("/school-identification/"+id, {
        params: {
          include: {
            classroom: true,
            edcenso_city: true
          }
        }
      })
      .then(response => response.data)
      .catch(err => {
        throw err;
      });
  };

  const requestStagevsmodality = id => {
    let path = "/stages-vacancy-pre-registration";
    return api
      .get(path, {
  
        params: {
          include: {
            edcenso_stage_vs_modality: true,
            student_pre_identification: true
          },
          school_inep_id_fk: id
        }
      })
      .then(response => response.data)
      .catch(err => {
        throw err;
      });
  };

  
  export const useFetchRequestSchools = () => {
    return useQuery(["useRequestsSchools"], () => requestSchools());
  };

  export const useFetchRequestSchool = ({ id }) => {
    return useQuery(["useRequestsSchool", id], () => requestSchool(id));
  };

  export const useFetchRequestRegistrations = () => {
    return useQuery(["useRequestRegistrations"], () => requestRegistrations());
  };

  export const useFetchRequestStagevsmodalitySchool = ({id}) => {
    return useQuery(["useRequestStagevsmodalitySchool"], () => requestStagevsmodality(id));
  };