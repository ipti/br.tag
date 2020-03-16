import React, { useState, useEffect } from "react";
import { connect } from "react-redux";
import { Classroom } from "../../screens/Classroom";
import Alert from "../../components/Alert/CustomizedSnackbars";

const Home = props => {
  const [loadData, setLoadData] = useState(true);
  const [loadDataPaginate, setLoadDataPaginate] = useState(false);
  const [page, setPage] = useState(0);
  const [open, setOpen] = useState(false);

  useEffect(() => {
    if (loadData) {
      props.dispatch({ type: "FETCH_CLASSROOMS" });
      setLoadData(false);
    }

    if (loadDataPaginate) {
      props.dispatch({ type: "FETCH_CLASSROOMS_PAGE", page });
      setLoadDataPaginate(false);
    }

    if (props?.error) {
      setOpen(true);
    }
  }, [loadData, page, loadDataPaginate, props]);

  const handlePage = (e, pageInfo) => {
    setLoadDataPaginate(true);
    setPage(pageInfo.activePage);
  };

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
      <Classroom
        data={props.classroom.classrooms}
        handlePage={handlePage}
        pagination={props.classroom.pagination}
      />
      {alert()}
    </>
  );
};

const mapStateToProps = state => {
  return {
    classroom: state.classroom.classrooms,
    error: state.classroom.msgError
  };
};
export default connect(mapStateToProps)(Home);
