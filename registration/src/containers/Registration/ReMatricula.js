import React, { useState, useEffect } from "react";
import { RegistrationConfirmed } from "../../screens/Classroom";
import { connect } from "react-redux";
import Loading from "../../components/Loading/CircularLoading";
import { useHistory, useParams } from "react-router-dom";
import Home from "../../screens/Registration/ReMatricula";

const ReMatricula = props => {
  const [loadData, setLoadData] = useState(true);
  const [loadDataSchool, setLoadDataSchool] = useState(true);
  let history = useHistory();

  const { id } = useParams()

  useEffect(() => {

    if (id && loadData) {
      props.dispatch({
        type: "FETCH_STUDENT",
        registration: id 
      })
      setLoadData(false);
      console.log(props)
    }
    if (loadDataSchool) {
      props.dispatch({ type: "FETCH_SCHOOLS_LIST" });
      setLoadDataSchool(false);
    }

  }, [loadData, props]);


  const onSubmit = value => {
    console.log(value)
    props.dispatch({ type: "FETCH_SAVE_REGISTRATION", data: value });
  };

  console.log(props)

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
console.log(state)
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
