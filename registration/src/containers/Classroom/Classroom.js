import React, { useState, useEffect } from "react";
import { connect } from "react-redux";
import { Classroom } from "../../screens/Classroom";
import Alert from "../../components/Alert/CustomizedSnackbars";
import Loading from "../../components/Loading/CircularLoading";
import { useFetchRequestClassrooms } from "../../query/classroom";

const Home = props => {
  const [page, setPage] = useState(1);

  const {data} = useFetchRequestClassrooms();

  console.log(data)


   const classrooms =  data?.data ?? []
  // useEffect(() => {
  //   if (loadData) {
  //     props.dispatch({ type: "FETCH_CLASSROOMS" });
  //     setLoadData(false);
  //   }

  //   if (loadDataPaginate) {
  //     props.dispatch({ type: "FETCH_CLASSROOMS_PAGE", page });
  //     setLoadDataPaginate(false);
  //   }

  //   if (props?.openAlert) {
  //     setTimeout(function() {
  //       props.dispatch({ type: "CLOSE_ALERT_CLASSROOM" });
  //     }, 6000);
  //   }
  // }, [loadData, page, loadDataPaginate, props]);

  // const handlePage = (e, pageInfo) => {
  //   setLoadDataPaginate(true);
  //   setPage(pageInfo.activePage);
  // };

  // const handleClose = () => {
  //   props.dispatch({ type: "CLOSE_ALERT_CLASSROOM" });
  // };

  const alert = () => {
    if (props?.openAlert) {
      let status = null;
      let message = null;

      if (props?.error) {
        status = 0;
        message = props.error;
      } else {
        status = props.fetchClassroom.status;
        message = props.fetchClassroom.message;
      }

      if (status && message) {
        return (
          <Alert
            open={props?.openAlert}
          //  handleClose={handleClose}
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
          <Classroom
            classrooms={classrooms}
          />
          {alert()}
        </>
      )}
    </>
  );
};

export default Home;
