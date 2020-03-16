import React, { useState, useEffect } from "react";
import * as Yup from "yup";
import { ScheduleForm } from "../../screens/Schedule";
import { connect } from "react-redux";
import moment from "moment";
import Alert from "../../components/Alert/CustomizedSnackbars";

const Form = props => {
  const [active, setActive] = useState(true);
  const [loadData, setLoadData] = useState(true);
  const [open, setOpen] = useState(false);
  const [isEdit, setIsEdit] = useState(false);

  useEffect(() => {
    if (props.match.params.id && loadData) {
      props.dispatch({
        type: "FETCH_SCHEDULE",
        data: { id: props.match.params.id }
      });
      setIsEdit(true);
      setLoadData(false);
    }

    if (Object.keys(props.schedule.schedule).length > 0) {
      setActive(props.schedule.schedule.data.isActive);
    }

    if (props?.error) {
      setOpen(true);
    }
  }, [isEdit, loadData, props]);

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
    } else {
      if (props.fetchSchedule?.status) {
        status = props.fetchSchedule.status;
        message = props.fetchSchedule.message;
      }
    }

    return (
      <Alert
        open={open}
        handleClose={handleClose}
        status={status}
        message={message}
      />
    );
  };

  const handleSubmit = values => {
    let data = {
      internalTransferDateStart: moment(
        values.internalTransferDateStart
      ).format("YYYY-MM-DD hh:mm:ss"),
      internalTransferDateEnd: moment(values.internalTransferDateEnd).format(
        "YYYY-MM-DD hh:mm:ss"
      ),
      newStudentDateStart: moment(values.newStudentDateStart).format(
        "YYYY-MM-DD hh:mm:ss"
      ),
      newStudentDateEnd: moment(values.newStudentDateEnd).format(
        "YYYY-MM-DD hh:mm:ss"
      ),
      year: values.year,
      isActive: values.isActive
    };

    if (isEdit) {
      props.dispatch({
        type: "FETCH_UPDATE_SCHEDULE",
        data,
        id: props.match.params.id
      });
    } else {
      props.dispatch({ type: "FETCH_SAVE_SCHEDULE", data });
    }
    setOpen(true);
  };

  const validationSchema = Yup.object().shape({
    internalTransferDateStart: Yup.date()
      .nullable()
      .required("Campo obrigatório!"),
    internalTransferDateEnd: Yup.date()
      .when(
        "internalTransferDateStart",
        (internalTransferDateStart, schema) => {
          if (internalTransferDateStart !== null) {
            return schema.min(
              internalTransferDateStart,
              "A Data Final deve ser maior do que a data inicial"
            );
          }
        }
      )
      .nullable()
      .required("Campo obrigatório!"),
    newStudentDateStart: Yup.date()
      .nullable()
      .required("Campo obrigatório!"),
    newStudentDateEnd: Yup.date()
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
      internalTransferDateStart: null,
      internalTransferDateEnd: null,
      newStudentDateStart: null,
      newStudentDateEnd: null,
      year: "",
      isActive: active
    };

    if (isEdit && Object.keys(props.schedule.schedule).length > 0) {
      initialValues = {
        internalTransferDateStart: moment(
          props.schedule.schedule.data.internalTransferDateStart
            .split("/")
            .reverse()
            .join("-")
        ),
        internalTransferDateEnd: moment(
          props.schedule.schedule.data.internalTransferDateEnd
            .split("/")
            .reverse()
            .join("-")
        ),
        newStudentDateStart: moment(
          props.schedule.schedule.data.newStudentDateStart
            .split("/")
            .reverse()
            .join("-")
        ),
        newStudentDateEnd: moment(
          props.schedule.schedule.data.newStudentDateEnd
            .split("/")
            .reverse()
            .join("-")
        ),
        year: props.schedule.schedule.data.year,
        isActive: active
      };
    }

    return initialValues;
  };

  return (
    <>
      <ScheduleForm
        initialValues={initialValues()}
        validationSchema={validationSchema}
        handleSubmit={handleSubmit}
        handleChangeActive={handleChangeActive}
        active={active}
        isEdit={isEdit}
      />
      {alert()}
    </>
  );
};

const mapStateToProps = state => {
  return {
    schedule: state.schedule,
    fetchSchedule: state.schedule.fetchSchedule,
    error: state.schedule.msgError
  };
};

export default connect(mapStateToProps)(Form);
