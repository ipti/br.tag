import React, { useState, useEffect } from "react";
import { SchoolClassroom } from "../../screens/School";
import { connect } from "react-redux";
import Alert from "../../components/Alert/CustomizedSnackbars";
import Loading from "../../components/Loading/CircularLoading";
import { useParams } from "react-router";

const Home = props => {
  const [loadData, setLoadData] = useState(true);
  const { id } = useParams()

  useEffect(() => {
    if (loadData) {
      props.dispatch({
        type: "FETCH_SCHOOL",
        data: { id: id }
      });
      setLoadData(false);
    }

    if (props?.openAlert) {
      setTimeout(function() {
        props.dispatch({ type: "CLOSE_ALERT_SCHOOL" });
      }, 6000);
    }
  }, [loadData, props]);

  const handleClose = () => {
    props.dispatch({ type: "CLOSE_ALERT_SCHOOL" });
  };

  const alert = () => {
    if (props.openAlert) {
      let status = null;
      let message = null;

      if (props?.error) {
        status = 0;
        message = props?.error;
      }

      if (message !== null && status !== null) {
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

  return (
    <>
      {props.loading ? (
        <Loading />
      ) : (
        <>
          <SchoolClassroom data={props.school} />
          {alert()}
        </>
      )}
    </>
  );
};

const mapStateToProps = state => {
  return {
    school: state.school,
    error: state.school.msgError,
    loading: state.school.loading,
    openAlert: state.school.openAlert
  };
};
export default connect(mapStateToProps)(Home);
