import { useQuery } from "react-query";
import api from "../../services/api";
import { getIdSchool } from "../../services/auth";

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

export const requestCreateStage = (data) => {
  return api
    .post("/stages-vacancy-pre-registration", data)
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

export const requestUpdateRegistration = (data, id) => {
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

const requestStagevsmodality = () => {
  let path = "/stages-vacancy-pre-registration";
  return api
    .get(path, {

      params: {
        include: {
          edcenso_stage_vs_modality: true,
          student_pre_identification: true
        },
        school_inep_id_fk: getIdSchool()
      }
    })
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

const requestStagevsmodalityOne = id => {
  let path = "/stages-vacancy-pre-registration/" + id;
  return api
    .get(path, {
      params: {
        include: {
          edcenso_stage_vs_modality: true,
          student_pre_identification: true
        }
      }
    })
    .then(response => response.data)
    .catch(err => {
      throw err;
    });
};

export const useFetchRequestClassrooms = () => {
  return useQuery(["useRequestClassrooms"], () => requestClassrooms());
};

export const useFetchRequestStagevsmodality = () => {
  return useQuery("useRequestStagevsmodality", () => requestStagevsmodality());
};

export const useFetchRequestStagevsmodalityOne = ({ id }) => {
  return useQuery(["useRequestStagevsmodalityOne", id], () => requestStagevsmodalityOne(id));
};

export const useFetchRequestClassroom = ({ id }) => {
  return useQuery(["useRequestClassroom", id], () => requestClassroom(id));
};

export const useFetchRequestRegistration = ({ id }) => {
  return useQuery(["useRequestRegistration", id], () => requestRegistration(id));
};