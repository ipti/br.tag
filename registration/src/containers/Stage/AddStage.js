import React, { useState } from "react";
import * as Yup from "yup";
import Alert from "../../components/Alert/CustomizedSnackbars";
import Loading from "../../components/Loading/CircularLoading";
import { Controller } from "../../controller/classroom";
import { requestCreateStage } from "../../query/stage";
import { useFetchRequestStagevsmodality } from "../../query/Schedule";
import Create from "../../screens/Stage/AddStage";

const AddStage = props => {
  const [active, setActive] = useState(true);
  const [loadingButtom, setLoadingButtom] = useState(false);
  const [open, setOpen] = useState(false);
  const { requestCreateStageMutation } = Controller()
  const { data } = useFetchRequestStagevsmodality()


  const handleChangeActive = event => {
    setActive(event.target.checked);
  };

  const handleClose = () => {
    setOpen(false);
  };

  const alert = () => {
    let status = null;
    let message = null;

    if (props?.error) {
      status = 0;
      message = props.error;
    }

    if (props?.fetchSchedule?.status === "0") {
      status = props?.fetchSchedule.status;
      message = props?.fetchSchedule.message;
    }

    if (status !== null && message !== null) {
      return (
        <Alert
          open={open}
          handleClose={handleClose}
          status={status}
          message={message}
        />
      );
    }

    return <></>;
  };

  const handleSubmit = values => {
    console.log(values)
    let data = {
      edcenso_stage_vs_modality: parseInt(values.edcenso_stage_vs_modality),
      vacancy: parseInt(values.vacancy),
      school_identification: "28022041",
      year: parseInt(values.year),
    };
    requestCreateStageMutation.mutate(data)
  };

  const validationSchema = Yup.object().shape({
    edcenso_stage_vs_modality: Yup.number().required("Campo obrigatório!"),
    vacancy: Yup.number().required("Campo obrigatório!"),
    year: Yup.number()
      .min(4, "Campo deve ter no mínimo 4 digitos. Ex: 2020")
      .required("Campo obrigatório!")
  });

  const initialValues = () => {
    let initialValues = {
      edcenso_stage_vs_modality: "",
      vacancy: "",
      year: "",
      school_identification: "28022041",
    };
    return initialValues;
  };

  return (
    <>
      {props?.loading && !loadingButtom ? (
        <Loading />
      ) : (
        <>
          <Create
            initialValues={initialValues}
           // validationSchema={validationSchema}
            stages={data}
            handleSubmit={handleSubmit}
          // handleChangeActive={handleChangeActive}
          // active={active}
          // isEdit={isEdit}
          // loadingIcon={props?.loading}
          />
          {alert()}
        </>
      )}
    </>
  );
};


export default AddStage;
