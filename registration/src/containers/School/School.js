import React, { useState, useEffect } from "react";
import { School } from "../../screens/School";
import { connect } from "react-redux";

const Home = props => {
  const [loadData, setLoadData] = useState(true);
  const [loadDataPaginate, setLoadDataPaginate] = useState(false);
  const [page, setPage] = useState(0);

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
  }, [loadData, page, loadDataPaginate, props]);

  return (
    <School
      pagination={props.schools && props.schools.pagination}
      data={props.schools && props.schools.schools}
      handlePage={handlePage}
    />
  );
};

const mapStateToProps = state => {
  return {
    schools: state.school.schools
  };
};
export default connect(mapStateToProps)(Home);
