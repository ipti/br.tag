import React, { useState, useEffect } from "react";
import { RegistrationConfirmed } from "../../screens/Classroom";
import { connect } from "react-redux";
import Loading from "../../components/Loading/CircularLoading";
import { useHistory } from "react-router-dom";

const Registration = props => {
  const [loadData, setLoadData] = useState(true);
  const [loadingButtom, setLoadingButtom] = useState(false);
  let history = useHistory();

  useEffect(() => {
    if (props.match.params.idRegistration && loadData) {
      props.dispatch({
        type: "FETCH_REGISTRATION",
        data: { id: props.match.params.idRegistration }
      });
      setLoadData(false);
    }

    if (
      props?.fetchRegistration?.status === "1" &&
      props.isRedirectRegistration
    ) {
      history.go(-1);
    }
  }, [history, loadData, props]);

  const handleSubmit = value => {
    setLoadingButtom(true);
    props.dispatch({
      type: "FETCH_UPDATE_REGISTRATION",
      data: { confirmed: value },
      id: props.match.params.idRegistration
    });
  };

  return (
    <>
      {props.loading && !loadingButtom ? (
        <Loading />
      ) : (
        <>
          <RegistrationConfirmed
            registration={props.registration}
            handleSubmit={handleSubmit}
            loadingIcon={props.loading}
          />
        </>
      )}
    </>
  );
};

const mapStateToProps = state => {
  return {
    registration: state.classroom.registration,
    error: state.classroom.msgError,
    loading: state.classroom.loading,
    fetchRegistration: state.classroom.fetchRegistration,
    isRedirectRegistration: state.classroom.isRedirectRegistration
  };
};

export default connect(mapStateToProps)(Registration);
