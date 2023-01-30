import React, { useState, useEffect } from "react";
import { RegistrationConfirmed } from "../../screens/Classroom";
import { connect } from "react-redux";
import Loading from "../../components/Loading/CircularLoading";
import { useHistory, useParams } from "react-router-dom";
import { useFetchRequestStudent } from "../../query/registration";
import { useFetchRequestRegistration } from "../../query/classroom";
import { Controller } from "../../controller/classroom";

const Registration = props => {
  const [loadData, setLoadData] = useState(true);
  const [loadClasroom, setLoadClassRoom] = useState(true);
  const [loadingButtom, setLoadingButtom] = useState(false);

   const {requestUpdateRegistrationMutation} = Controller();
  let history = useHistory();

  const { id, idRegistration } = useParams()

  const {data} = useFetchRequestRegistration({id: idRegistration});

  // useEffect(() => {

  //   if (id && loadClasroom) {
  //     props.dispatch({
  //       type: "FETCH_CLASSROOM",
  //       data: { id: id }
  //     })
  //     setLoadClassRoom(false);
  //   }

  //   if (idRegistration && loadData) {
  //     props.dispatch({
  //       type: "FETCH_REGISTRATION",
  //       data: { id: idRegistration}
  //     });
  //     setLoadData(false);
  //   }

  //   if (
  //     props?.fetchRegistration?.status === "1" &&
  //     props.isRedirectRegistration
  //   ) {
  //     history.go(-1);
  //   }
  // }, [history, loadData, props, id,loadClasroom, idRegistration]);

  const handleSubmit = value => {
    setLoadingButtom(true);

    requestUpdateRegistrationMutation.mutate({data: value, id: idRegistration})
    // props.dispatch({
    //   type: "FETCH_UPDATE_REGISTRATION",
    //   data: value,
    //   id: idRegistration
    // });
  };

  return (
    <>
      {props.loading && !loadingButtom ? (
        <Loading />
      ) : (
        <>
          <RegistrationConfirmed
            registration={data}
            classroom={id}
            handleSubmit={handleSubmit}
            loadingIcon={props.loading}
          />
        </>
      )}
    </>
  );
};

export default Registration;
