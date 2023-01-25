import React, { useState, useEffect } from "react";
import * as Yup from "yup";
import { ClassroomForm } from "../../screens/Classroom";
import { connect } from "react-redux";
import { useHistory, useParams } from "react-router-dom";
import Loading from "../../components/Loading/CircularLoading";
import Alert from "../../components/Alert/CustomizedSnackbars";

const Form = props => {
  const [loadData, setLoadData] = useState(true);
  const [isEdit, setIsEdit] = useState(false);
  const [loadingButtom, setLoadingButtom] = useState(false);
  let history = useHistory();
  const { id } = useParams()

  useEffect(() => {
    if (id && loadData) {
      props.dispatch({
        type: "FETCH_CLASSROOM",
        data: { id: id }
      });
      setIsEdit(true);
      setLoadData(false);
    }

    if (props?.openAlert) {
      setTimeout(function() {
        props.dispatch({ type: "CLOSE_ALERT_CLASSROOM" });
      }, 6000);
    }

    if (props?.fetchClassroom?.status === "1" && props.isRedirectClassroom) {
      history.push("/turmas");
    }
  }, [history, isEdit, loadData, props]);

  const handleClose = () => {
    props.dispatch({ type: "CLOSE_ALERT_CLASSROOM" });
  };

  const alert = () => {
    if (props?.openAlert) {
      let status = null;
      let message = null;

      if (props?.fetchRegistration?.status) {
        status = props.fetchRegistration.status;
        message = props.fetchRegistration.message;
      } else if (props.fetchClassroom.status) {
        status = props.fetchClassroom.status;
        message = props.fetchClassroom.message;
      } else {
        status = 0;
        message = props.error;
      }

      if (status && message) {
        return (
          <Alert
            open={props?.openAlert}
            handleClose={handleClose}
            status={status}
            message={message}
          />
        );
      }
    }
    return <></>;
  };

  const handleSubmit = values => {
    setLoadingButtom(true);
    props.dispatch({
      type: "FETCH_UPDATE_CLASSROOM",
      data: values,
      id: props.match.params.id
    });
    setLoadData(true);
  };

  const validationSchema = Yup.object().shape({
    vacancies: Yup.number()
      .nullable()
      .required("Campo obrigatório!")
  });

  const initialValues = {
    vacancies: props.classroom?.classroom?.data
      ? props.classroom?.classroom?.data.vacancies
      : ""
  };


  return (
    <>
      {props?.loading && !loadingButtom ? (
        <Loading />
      ) : (
        <>
          <ClassroomForm
            initialValues={initialValues}
            validationSchema={validationSchema}
            handleSubmit={handleSubmit}
            baseLink={`/turmas/${props.match.params.id}/matricula`}
            isEdit={isEdit}
            loadingIcon={props?.loading}
            data={
              props.classroom.classroom
            }
          />
          {alert()}
        </>
      )}
    </>
  );
};

const mapStateToProps = state => {
  return {
    classroom: state.classroom,
    fetchClassroom: state.classroom.fetchClassroom,
    error: state.classroom.msgError,
    loading: state.classroom.loading,
    openAlert: state.classroom.openAlert,
    fetchRegistration: state.classroom.fetchRegistration,
    isRedirectClassroom: state.classroom.isRedirectClassroom
  };
};

export default connect(mapStateToProps)(Form);
