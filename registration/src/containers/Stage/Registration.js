import React, { useState } from "react";
import { useParams } from "react-router-dom";
import Loading from "../../components/Loading/CircularLoading";
import { Controller } from "../../controller/classroom";
import { useFetchRequestRegistration } from "../../query/stage";
import { RegistrationConfirmed } from "../../screens/Stage";

const Registration = props => {

   const {requestUpdateRegistrationMutation, requestUpdatePreIdentificationMutation} = Controller();

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
    requestUpdateRegistrationMutation.mutate({data: value, id: idRegistration})
    // props.dispatch({
    //   type: "FETCH_UPDATE_REGISTRATION",
    //   data: value,
    //   id: idRegistration
    // });
  };

  const handleRefusePreIdentification = () => {
    requestUpdatePreIdentificationMutation.mutate({data: {student_pre_identification_status: 3,}, id: idRegistration})
  }

  return (
    <>
      {!data ? (
        <Loading />
      ) : (
        <>
          <RegistrationConfirmed
            registration={data}
            classroom={id}
            handleRefusePreIdentification={handleRefusePreIdentification}
            handleSubmit={handleSubmit}
            loadingIcon={props.loading}
          />
        </>
      )}
    </>
  );
};

export default Registration;
