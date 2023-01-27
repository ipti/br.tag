import React from "react";
import Loading from "../../components/Loading/CircularLoading";
import { useParams } from "react-router-dom";
import Home from "../../screens/Registration/ReMatricula";
import { useFetchRequestStudent } from "../../query/registration";
import { Controller } from "../../controller/registration";

const ReMatricula = props => {
  const { requestSaveRegistrationMutation } = Controller();

  const { id } = useParams()

  // useEffect(() => {

  //   if (loadData) {
  //     props.dispatch({
  //       type: "FETCH_STUDENT",
  //       registration: id
  //     })
  //     setLoadData(false);
  //   }
  //   if (loadDataSchool) {
  //     props.dispatch({ type: "FETCH_SCHOOLS_LIST" });
  //     setLoadDataSchool(false);
  //   }

  // }, [loadData, props, loadDataSchool, id]);

  const { data } = useFetchRequestStudent({ id: id });

  const onSubmit = (value) => {

    if (value?.birthday) {
      value.birthday = value.birthday
        .split("/")
        .reverse()
        .join("-");
    }

    requestSaveRegistrationMutation.mutate(value)
  };


  return (
    <>
      {!data ? (
        <Loading />
      ) : (
        <>
          <Home
            registration={data}
            handleSubmit={onSubmit}
            loadingIcon={props.loading}
          />
        </>
      )}
    </>
  );
};


export default ReMatricula;
