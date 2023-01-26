import React, { useState, useEffect } from "react";
import { connect } from "react-redux";
import Loading from "../../components/Loading/CircularLoading";
import { useParams } from "react-router-dom";
import Home from "../../screens/Registration/ReMatricula";

const ReMatricula = props => {
  const [loadData, setLoadData] = useState(true);
  const [loadDataSchool, setLoadDataSchool] = useState(true);

  const { id } = useParams()

  useEffect(() => {

    if (loadData) {
      props.dispatch({
        type: "FETCH_STUDENT",
        registration: id
      })
      setLoadData(false);
    }
    if (loadDataSchool) {
      props.dispatch({ type: "FETCH_SCHOOLS_LIST" });
      setLoadDataSchool(false);
    }

  }, [loadData, props, loadDataSchool, id]);


  const onSubmit = (value) => {
   
    if (value?.birthday) {
      value.birthday = value.birthday
        .split("/")
        .reverse()
        .join("-");
    }
    props.dispatch({ type: "FETCH_SAVE_REGISTRATION", data: value });
  };


  return (
    <>
      {loadData ? (
        <Loading />
      ) : (
        <>
          <Home
            registration={props.student}
            schools={props.schoolList}
            handleSubmit={onSubmit}
            loadingIcon={props.loading}
          />
        </>
      )}
    </>
  );
};

const mapStateToProps = state => {
  return {
    address: state.viaCep.addresses,
    student: state.registration.student,
    registration: state.registration.registration,
    period: state.registration.period,
    schoolList: state.registration.schoolList,
    loading: state.registration.loading,
    openAlert: state.registration.openAlert
  };
};

export default connect(mapStateToProps)(ReMatricula);
