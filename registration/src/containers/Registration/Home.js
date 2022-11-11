import React, { useState, useEffect } from "react";

// Redux
import { connect } from "react-redux";

// Components
import Alert from "../../components/Alert/CustomizedSnackbars";
import Wizard from "../../screens/Registration/Wizard";
import Wait from "../../screens/Registration/Wait";
import Loading from "../../components/Loading/CircularLoadingRegistration";

// Material UI
import Grid from "@material-ui/core/Grid";

const Home = props => {
  const [loadDataStudent, setLoadDataStudent] = useState(false);
  const [loadDataSchool, setLoadDataSchool] = useState(true);
  const [loadPeriod, setLoadPeriod] = useState(true);
  const [open, setOpen] = useState(false);
  const [number, setNumber] = useState("");
  const [step, setStep] = useState(0);
  const [dataValues, setDataValues] = useState({});

  useEffect(() => {
    setOpen(false);

    if (loadDataStudent) {
      props.dispatch({ type: "FETCH_STUDENT", registration: number });
      setLoadDataStudent(false);
    }

    if (loadDataSchool) {
      props.dispatch({ type: "FETCH_SCHOOLS_LIST" });
      setLoadDataSchool(false);
    }

    if (loadPeriod) {
      props.dispatch({ type: "FETCH_PERIOD" });
      setLoadPeriod(false);
    }

    if (step === 2 && props?.student?.status === "1") {
      setStep(3);
    }

    if (props?.registration?.status === "1" && !props.loading) {
      setStep(6);
    }

    if (props?.student?.status === "1" && !props.loading) {
      setStep(5);
    }

    if (props.openAlert) {
      setTimeout(function() {
        props.dispatch({ type: "CLOSE_ALERT_REGISTRATION" });
      }, 3000);
    }
  }, [loadDataSchool, loadDataStudent, loadPeriod, number, open, props, step]);

  const onSubmit = () => {
    if (dataValues?.birthday) {
      dataValues.birthday = dataValues.birthday
        .split("/")
        .reverse()
        .join("-");
    }

    props.dispatch({ type: "FETCH_SAVE_REGISTRATION", data: dataValues });
  };

  const next = (step, values) => {
    let data = Object.assign(dataValues, values);
    if (
      step === 1 &&
      props?.period &&
      !props.period?.data?.internal &&
      props.period?.data?.newStudent &&
      !props?.student?.status
    ) {
      setStep(3);
    } else if (
      step === 1 &&
      props?.period &&
      props.period?.data?.internal &&
      !props.period?.data?.newStudent
    ) {
      setStep(2);
    } else {
      if (step > 1) {
        setDataValues(data);
      }

      if (step === 3 && values && values.numRegistration !== "") {
        getDataStudent(values.numRegistration);
      } else {
        if (step === 6) {
          onSubmit();
        } else {
          setStep(step);
        }
      }
    }
  };

  const getDataStudent = number => {
    setNumber(number);
    setLoadDataStudent(true);
  };

  const handleClose = () => {
    setOpen(false);
  };

  const alert = () => {
    return (
      <Alert
        open={props.openAlert}
        handleClose={handleClose}
        status="0"
        message="Matrícula não encontrada!."
      />
    );
  };

  const handleStudent = isStudent => {
    if (!isStudent) {
      next(3);
    } else {
      next(2);
    }
  };

  const wizard = () => {
    const isActive =
      props.period?.data?.internal === true ||
      props.period?.data?.newStudent === true ||
      props?.student?.status ||
      step === 6 ||
      props.loading;
    return (
      <Grid
        container
        justify="center"
        alignItems="center"
        style={{ minWidth: "100%" }}
      >
        <Grid item lg={4} md={5} xs={10}>
          {isActive ? (
            <Wizard
              student={props.student && props.student.data}
              schools={props?.schoolList}
              next={next}
              step={step}
              registration={
                props?.registration && props?.registration?.data?.id
              }
              handleStudent={handleStudent}
              loadingButtom={props.loading}
            />
          ) : (
            <Wait />
          )}
        </Grid>
      </Grid>
    );
  };

  return (
    <>
      {props.loading && step === 0 ? (
        <Loading />
      ) : (
        <>
          {wizard()}
          {alert()}
        </>
      )}
    </>
  );
};

const mapStateToProps = state => {
  
  return {
    student: state.registration.student,
    registration: state.registration.registration,
    period: state.registration.period,
    schoolList: state.registration.schoolList,
    loading: state.registration.loading,
    openAlert: state.registration.openAlert
  };
};

export default connect(mapStateToProps)(Home);
