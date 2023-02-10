import React, { useState, useEffect } from "react";
import { SchoolClassroom } from "../../screens/School";
import { connect } from "react-redux";
import Alert from "../../components/Alert/CustomizedSnackbars";
import Loading from "../../components/Loading/CircularLoading";
import { useParams } from "react-router";
import { useFetchRequestSchool } from "../../query/school";

const Home = props => {
  const [loadData, setLoadData] = useState(true);
  const { id } = useParams()

  const { data } = useFetchRequestSchool({id: id})

  console.log(data)

  // useEffect(() => {
  //   console.log("alÔ")
  //   if (loadData && id) {
  //     props.dispatch({
  //       type: "FETCH_SCHOOL",
  //       data: { id: id }
  //     });
  //     setLoadData(false);
  //   }

  //   if (props?.openAlert) {
  //     setTimeout(function() {
  //       props.dispatch({ type: "CLOSE_ALERT_SCHOOL" });
  //     }, 6000);
  //   }
  // }, [loadData, props, id]);

  // const handleClose = () => {
  //   props.dispatch({ type: "CLOSE_ALERT_SCHOOL" });
  // };

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
           // handleClose={handleClose}
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
      {!data ? (
        <Loading />
      ) : (
        <>
          <SchoolClassroom school={data} />
          {alert()}
        </>
      )}
    </>
  );
};


export default Home;
