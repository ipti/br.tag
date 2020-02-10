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
  }, [loadData, props]);

  const handleClose = () => {
    setOpen(false);
  };

  const alert = () => {
    if (props.fetchRegistration) {
      return (
        <Alert
          open={open}
          handleClose={handleClose}
          status={props.fetchRegistration.status}
          message={props.fetchRegistration.message}
        />
      );
    }
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
    fetchRegistration: state.classroom.fetchRegistration
  };
};

export default connect(mapStateToProps)(Registration);
