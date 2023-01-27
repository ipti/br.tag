import React, { useState, useEffect } from "react";
import { School } from "../../screens/School";
import { connect } from "react-redux";
import Alert from "../../components/Alert/CustomizedSnackbars";
import Loading from "../../components/Loading/CircularLoading";

const Home = props => {
  const [loadData, setLoadData] = useState(true);
  const [loadDataPaginate, setLoadDataPaginate] = useState(false);
  const [page, setPage] = useState(1);

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

    if (props?.openAlert) {
      setTimeout(function () {
        props.dispatch({ type: "CLOSE_ALERT_SCHOOL" });
      }, 6000);
    }
  }, [loadData, page, loadDataPaginate, props]);

  const handleClose = () => {
    props.dispatch({ type: "CLOSE_ALERT_SCHOOL" });
  };

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
            handleClose={handleClose}
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
          <School
            pagination={props.schools}
            data={props.schools}
            handlePage={handlePage}
            activePage={page}
          />
          {alert()}
        </>
      )}
    </>
  );
};

const mapStateToProps = state => {
  console.log(state)
  return {
    schools: state.school.schools,
    school: state.school,
    error: state.school.msgError,
    loading: state.school.loading,
    openAlert: state.school.openAlert
  };
};
export default connect(mapStateToProps)(Home);
