import React, { useState, useEffect } from "react";
import { useHistory } from "react-router-dom";
import { School } from "../../screens/School";
import { connect } from "react-redux";

const Home = props => {
  const [loadData, setLoadData] = useState(true);
  const [loadDataPaginate, setLoadDataPaginate] = useState(false);
  const [page, setPage] = useState(0);
  let history = useHistory();

  const handleClick = id => {
    history.push("/escolas/" + id);
  };

  const handlePage = page => {
    setLoadDataPaginate(true);
    setPage(page);
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
      handleClick={handleClick}
      pagination={props.schools.pagination}
      data={props.schools.schools}
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
