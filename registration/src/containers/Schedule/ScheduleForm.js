import React, { useState, useEffect } from "react";
import * as Yup from "yup";
import { ScheduleForm } from "../../screens/Schedule";
import { useHistory } from "react-router-dom";
import { connect } from "react-redux";
import moment from "moment";
import Loading from "../../components/Loading/CircularLoading";
import Alert from "../../components/Alert/CustomizedSnackbars";
import { useFetchRequestSchools } from "../../query/Schedule";
import { Controller } from "../../controller/Schedule/index";

const Form = props => {
  const [active, setActive] = useState(true);
  const [loadData, setLoadData] = useState(true);
  const [loadingButtom, setLoadingButtom] = useState(false);
  const [isEdit, setIsEdit] = useState(false);
  const [open, setOpen] = useState(false);
  const [redirect, setRedirect] = useState(false);
  const { requestSaveEventPreMutation } = Controller()
  let history = useHistory();

  const {data} = useFetchRequestSchools()

  useEffect(() => {
    if (props.match.params.id && loadData) {
      props.dispatch({
        type: "FETCH_SCHEDULE",
        data: { id: props.match.params.id }
      });
      setIsEdit(true);
      setLoadData(false);
    }

    if (props.schedule?.schedule) {
      setActive(props.schedule?.schedule?.data?.isActive);
    }

    if (redirect && props?.fetchSchedule?.status === "1" && !props?.error) {
      history.push("/cronograma");
    } else {
      setOpen(true);
      setTimeout(function() {
        setOpen(false);
      }, 6000);
    }
  }, [history, isEdit, loadData, props, redirect]);

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
    let data = {
      start_date: values.internalTransferDateStart,
      end_date: values.internalTransferDateEnd,
      school_identificationArray: values.school_identificationArray,
      year: values.year,
    };
      console.log(data);
    requestSaveEventPreMutation.mutation(data)

    // if (isEdit) {
    //   props.dispatch({
    //     type: "FETCH_UPDATE_SCHEDULE",
    //     data,
    //     id: props.match.params.id
    //   });
    // } else {
    //   props.dispatch({ type: "FETCH_SAVE_SCHEDULE", data });
    // }
    // setRedirect(true);
    // setLoadingButtom(true);
  };

  const validationSchema = Yup.object().shape({
      start_date: Yup.date()
      .nullable()
      .required("Campo obrigatório!"),
      end_date: Yup.date()
      .when("newStudentDateStart", (newStudentDateStart, schema) => {
        if (newStudentDateStart !== null) {
          return schema.min(
            newStudentDateStart,
            "A Data Final deve ser maior do que a data inicial"
          );
        }
      })
      .nullable()
      .required("Campo obrigatório!"),
    year: Yup.number()
      .min(4, "Campo deve ter no mínimo 4 digitos. Ex: 2020")
      .required("Campo obrigatório!")
  });

  const initialValues = () => {
    let initialValues = {
      start_date: null,
      end_date: null,
      year: "",
      school_identificationArray: "",
    };
    return initialValues;
  };

  return (
    <>
      {props?.loading && !loadingButtom ? (
        <Loading />
      ) : (
        <>
          <ScheduleForm
            initialValues={initialValues()}
             validationSchema={validationSchema}
            schools={data}
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


export default Form;
