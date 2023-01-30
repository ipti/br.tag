import React, { useState } from "react";
import Alert from "../../components/Alert/CustomizedSnackbars";
import Loading from "../../components/Loading/CircularLoading";
import { useFetchRequestSchools } from "../../query/school";
import { School } from "../../screens/School";

const Home = props => {
  const [page, setPage] = useState(1);

  const handlePage = (e, pageInfo) => {
    // setLoadDataPaginate(true);
    setPage(pageInfo.activePage);
  };

  const { data } = useFetchRequestSchools()

  // useEffect(() => {
  //   if (loadData) {
  //     props.dispatch({ type: "FETCH_SCHOOLS" });
  //     setLoadData(false);
  //   }

  //   if (loadDataPaginate) {
  //     props.dispatch({ type: "FETCH_SCHOOLS_PAGE", page });
  //     setLoadDataPaginate(false);
  //   }

  //   if (props?.openAlert) {
  //     setTimeout(function () {
  //       props.dispatch({ type: "CLOSE_ALERT_SCHOOL" });
  //     }, 6000);
  //   }
  // }, [loadData, page, loadDataPaginate, props]);

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
            //handleClose={handleClose}
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
          <School
            pagination={props.schools}
            data={data}
            handlePage={handlePage}
            activePage={page}
          />
          {alert()}
        </>
      )}
    </>
  );
};

export default Home;