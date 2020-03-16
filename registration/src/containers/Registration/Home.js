import React, { useState, useEffect } from "react";
import moment from "moment";

// Redux
import { connect } from "react-redux";

// Components
import Alert from "../../components/Alert/CustomizedSnackbars";
import Wizard from "../../screens/Registration/Wizard";
import Wait from "../../screens/Registration/Wait";

// Material UI
import Grid from "@material-ui/core/Grid";

const Home = props => {
  const [loadDataStudent, setLoadDataStudent] = useState(false);
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

    if (loadPeriod) {
      props.dispatch({ type: "FETCH_PERIOD" });
      setLoadPeriod(false);
    }

    if (
      step - 1 === 0 &&
      props?.period &&
      props.period?.data?.internal === false &&
      props.period?.data?.newStudent === true
    ) {
      setStep(3);
    }

    if (
      step - 1 === 0 &&
      props?.period &&
      props.period?.data?.internal === true &&
      props.period?.data?.newStudent === false &&
      !props?.student?.status
    ) {
      setStep(2);
    }

    if (step === 2 && props.student && props.student.status === "1") {
      setStep(3);
    }

    if (props.registration && props.registration.status === "1") {
      setStep(6);
    }

    if (props?.student && props?.student?.status === "1") {
      setStep(5);
    }

    if (step === 2 && props.student && props.student.status === "0") {
      setOpen(true);
    }
  }, [loadDataStudent, loadPeriod, number, props, step]);

  const onSubmit = () => {
    dataValues.birthday = dataValues.birthday
      .split("/")
      .reverse()
      .join("-");

    props.dispatch({ type: "FETCH_SAVE_REGISTRATION", data: dataValues });
  };

  const next = (step, values) => {
    let data = Object.assign(dataValues, values);
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
        open={open}
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
      step === 6;
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
              next={next}
              step={step}
              registration={
                props?.registration && props?.registration?.data?.id
              }
              handleStudent={handleStudent}
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
      {wizard()}
      {alert()}
    </>
  );
};

const mapStateToProps = state => {
  return {
    student: state.registration.student,
    registration: state.registration.registration,
    period: state.registration.period
  };
};

export default connect(mapStateToProps)(Home);
