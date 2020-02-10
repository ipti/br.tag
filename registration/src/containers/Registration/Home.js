import React, { useState, useEffect } from "react";
import { connect } from "react-redux";
import Alert from "../../components/Alert/CustomizedSnackbars";
import Wizard from "../../screens/Registration/Wizard";

const Home = props => {
  const [loadDataStudent, setLoadDataStudent] = useState(false);
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

    if (step === 2 && props.student && props.student.status === "1") {
      setStep(3);
    }

    if (props.registration && props.registration.status === "1") {
      setStep(6);
    }

    if (step === 2 && props.student && props.student.status === "0") {
      setOpen(true);
    }
  }, [loadDataStudent, number, props, step]);

  const onSubmit = () => {
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

  return (
    <>
      <Wizard
        student={props.student && props.student.data}
        next={next}
        step={step}
        registration={props.registration && props.registration.data}
        handleStudent={handleStudent}
      />
      {alert()}
    </>
  );
};

const mapStateToProps = state => {
  return {
    student: state.registration.student,
    registration: state.registration.registration
  };
};

export default connect(mapStateToProps)(Home);
