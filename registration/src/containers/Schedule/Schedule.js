import React, { useState, useEffect } from "react";
import { Schedule } from "../../screens/Schedule";
import { connect } from "react-redux";
import Alert from "../../components/Alert/CustomizedSnackbars";

const Home = props => {
  const [loadData, setLoadData] = useState(true);
  const [loadDataPaginate, setLoadDataPaginate] = useState(false);
  const [page, setPage] = useState(0);
  const [open, setOpen] = useState(false);

  const handlePage = (e, pageInfo) => {
    setLoadDataPaginate(true);
    setPage(pageInfo.activePage);
  };

  useEffect(() => {
    if (loadData) {
      props.dispatch({ type: "FETCH_SCHEDULES" });
      setLoadData(false);
    }

    if (loadDataPaginate) {
      props.dispatch({ type: "FETCH_SCHEDULES_PAGE", page });
      setLoadDataPaginate(false);
    }

    if (props?.error) {
      setOpen(true);
    }
  }, [loadData, page, loadDataPaginate, props]);

  const handleClose = () => {
    setOpen(false);
  };

  const alert = () => {
    let status = null;
    let message = null;

    if (props?.error) {
      status = 0;
      message = props.error;
    }

    return (
      <Alert
        open={open}
        handleClose={handleClose}
        status={status}
        message={message}
      />
    );
  };

  return (
    <>
      <Schedule
        pagination={props.schedule.pagination}
        data={props.schedule.schedules}
        handlePage={handlePage}
      />
      {alert()}
    </>
  );
};

const mapStateToProps = state => {
  return {
    schedule: state.schedule.schedules,
    error: state.schedule.msgError
  };
};
export default connect(mapStateToProps)(Home);
