import React, { useState, useEffect } from "react";
import { Schedule } from "../../screens/Schedule";
import { connect } from "react-redux";
import Loading from "../../components/Loading/CircularLoading";
import Alert from "../../components/Alert/CustomizedSnackbars";
import { useFetchRequestSchecule } from "../../query/Schedule";

const Home = props => {
  const [loadData, setLoadData] = useState(true);
  const [loadDataPaginate, setLoadDataPaginate] = useState(false);
  const [page, setPage] = useState(1);

  const handlePage = (e, pageInfo) => {
    setPage(pageInfo.activePage);
    setLoadDataPaginate(true);
  };

  const {data} = useFetchRequestSchecule()

  // useEffect(() => {
  //   if (loadData) {
  //     props.dispatch({ type: "FETCH_SCHEDULES" });
  //     setLoadData(false);
  //   }

  //   if (loadDataPaginate) {
  //     props.dispatch({ type: "FETCH_SCHEDULES_PAGE", page });
  //     setLoadDataPaginate(false);
  //   }

  //   if (props?.openAlert) {
  //     setTimeout(function() {
  //       props.dispatch({ type: "CLOSE_ALERT_SCHEDULE" });
  //     }, 6000);
  //   }
  // }, [loadData, page, loadDataPaginate, props]);

  // const handleClose = () => {
  //   props.dispatch({ type: "CLOSE_ALERT_SCHEDULE" });
  // };

  const alert = () => {
    if (props?.openAlert) {
      let status = null;
      let message = null;

      if (props?.error) {
        status = 0;
        message = props.error;
      } else {
        status = props?.fetchSchedule.status;
        message = props?.fetchSchedule.message;
      }

      if (message !== null && status !== null) {
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
          <Schedule
            // pagination={props.schedule.pagination}
            // activePage={page}
             data={data}
            // handlePage={handlePage}
          />
          {alert()}
        </>
      )}
    </>
  );
};

export default Home;
