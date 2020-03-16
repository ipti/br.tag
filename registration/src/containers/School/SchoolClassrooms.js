import React, { useState, useEffect } from "react";
import { SchoolClassroom } from "../../screens/School";
import { connect } from "react-redux";
import Alert from "../../components/Alert/CustomizedSnackbars";

const Home = props => {
  const [loadData, setLoadData] = useState(true);
  const [open, setOpen] = useState(false);

  useEffect(() => {
    if (loadData) {
      props.dispatch({
        type: "FETCH_SCHOOL",
        data: { id: props.match.params.id }
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
  return (
    <>
      <SchoolClassroom data={props.school && props.school.school} />
      {alert()}
    </>
  );
};

const mapStateToProps = state => {
  return {
    school: state.school.school,
    error: state.school.msgError
  };
};
export default connect(mapStateToProps)(Home);
