import React, { useState } from "react";
import * as Yup from "yup";
import { ScheduleCreate } from "../../screens/Schedule";
//import api from "../../services/api";

const Create = () => {
  const onSubmit = values => {};

  const validationSchema = Yup.object().shape({
    internalTransferDateStart: Yup.string().required("Campo é obrigatório!"),
    internalTransferDateEnd: Yup.string().required("Campo é obrigatório!"),
    newStudentDateStart: Yup.string().required("Campo é obrigatório!"),
    newStudentDateEnd: Yup.string().required("Campo é obrigatório!"),
    year: Yup.string().required("Campo é obrigatório!"),
    isActive: Yup.string().required("Campo é obrigatório!")
  });

  let initialValues = {
    internalTransferDateStart: "",
    internalTransferDateEnd: "",
    newStudentDateStart: "",
    newStudentDateEnd: "",
    year: "",
    isActive: true
  };

  return (
    <ScheduleCreate
      initialValues={initialValues}
      validationSchema={validationSchema}
      onSubmit={onSubmit}
    />
  );
};

export default Create;
