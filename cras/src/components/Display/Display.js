import React from 'react';
import Alert from '../Alert';
import { connect } from 'react-redux';

const Display = (props) => {
  const handleCloseAlert = () => {
    props.dispatch({ type: 'CLOSE_ALERT' });
    if (props.alertHandleClose) {
      props.alertHandleClose();
    }
  };

  return (
    <>
      <Alert
        open={props.alertOpen}
        autoHideDuration={props.alertAutoHideDuration}
        type={props.alertType}
        message={props.alertMessage}
        handleClose={handleCloseAlert}
      />
    </>
  );
};

const mapStateToProps = (state) => {
  return {
    alertOpen: state.display.alertOpen,
    alertAutoHideDuration: state.display.alertAutoHideDuration,
    alertType: state.display.alertType,
    alertMessage: state.display.alertMessage,
    alertHandleClose: state.display.alertHandleClose
  };
};

export default connect(mapStateToProps)(Display);
