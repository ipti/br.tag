import React, { useState, useEffect } from "react";
import { School } from "../../screens/School";
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
      props.dispatch({ type: "FETCH_SCHOOLS" });
      setLoadData(false);
    }

    if (loadDataPaginate) {
      props.dispatch({ type: "FETCH_SCHOOLS_PAGE", page });
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
      <School
        pagination={props.schools && props.schools.pagination}
        data={props.schools && props.schools.schools}
        handlePage={handlePage}
      />
      {alert()}
    </>
  );
};

const mapStateToProps = state => {
  return {
    schools: state.school.schools,
    error: state.school.msgError
  };
};
export default connect(mapStateToProps)(Home);
