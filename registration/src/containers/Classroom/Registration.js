import React, { useState, useEffect } from "react";
import { RegistrationConfirmed } from "../../screens/Classroom";
import { connect } from "react-redux";
import Alert from "../../components/Alert/CustomizedSnackbars";

const Registration = props => {
  const [loadData, setLoadData] = useState(true);
  const [open, setOpen] = useState(false);

  useEffect(() => {
    if (props.match.params.idRegistration && loadData) {
      props.dispatch({
        type: "FETCH_REGISTRATION",
        data: { id: props.match.params.idRegistration }
      });
      setLoadData(false);
    }

    if (props?.error) {
      setOpen(true);
    }
  }, [loadData, props]);

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
      if (props.fetchRegistration) {
        status = props.fetchRegistration.status;
        message = props.fetchRegistration.message;
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

  const handleSubmit = value => {
    props.dispatch({
      type: "FETCH_UPDATE_REGISTRATION",
      data: { confirmed: value },
      id: props.match.params.idRegistration
    });
    setOpen(true);
  };

  return (
    <>
      <RegistrationConfirmed
        registration={props.registration}
        handleSubmit={handleSubmit}
      />
      {alert()}
    </>
  );
};

const mapStateToProps = state => {
  return {
    registration: state.classroom.registration,
    fetchRegistration: state.classroom.fetchRegistration,
    error: state.classroom.msgError
  };
};

export default connect(mapStateToProps)(Registration);
