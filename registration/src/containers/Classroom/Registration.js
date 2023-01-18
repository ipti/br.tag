import React, { useState, useEffect } from "react";
import { RegistrationConfirmed } from "../../screens/Classroom";
import { connect } from "react-redux";
import Loading from "../../components/Loading/CircularLoading";
import { useHistory, useParams } from "react-router-dom";

const Registration = props => {
  const [loadData, setLoadData] = useState(true);
  const [loadClasroom, setLoadClassRoom] = useState(true);
  const [loadingButtom, setLoadingButtom] = useState(false);
  let history = useHistory();

  const { id, idRegistration } = useParams()

  useEffect(() => {

    if (id && loadClasroom) {
      props.dispatch({
        type: "FETCH_CLASSROOM",
        data: { id: id }
      })
      setLoadClassRoom(false);
    }

    if (idRegistration && loadData) {
      props.dispatch({
        type: "FETCH_REGISTRATION",
        data: { id: idRegistration}
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

  console.log(props)

  return (
    <>
      {props.loading && !loadingButtom ? (
        <Loading />
      ) : (
        <>
          <RegistrationConfirmed
            registration={props.registration}
            classroom={props.classroom}
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
    classroom: state.classroom.classroom,
    error: state.classroom.msgError,
    loading: state.classroom.loading,
    fetchRegistration: state.classroom.fetchRegistration,
    isRedirectRegistration: state.classroom.isRedirectRegistration
  };
};

export default connect(mapStateToProps)(Registration);
