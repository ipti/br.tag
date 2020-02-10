import React, { useState, useEffect } from "react";
import { connect } from "react-redux";
import { Classroom } from "../../screens/Classroom";

const Home = props => {
  const [loadData, setLoadData] = useState(true);
  const [loadDataPaginate, setLoadDataPaginate] = useState(false);
  const [page, setPage] = useState(0);

  useEffect(() => {
    if (loadData) {
      props.dispatch({ type: "FETCH_CLASSROOMS" });
      setLoadData(false);
    }

    if (loadDataPaginate) {
      props.dispatch({ type: "FETCH_CLASSROOMS_PAGE", page });
      setLoadDataPaginate(false);
    }
  }, [loadData, page, loadDataPaginate, props]);

  const handlePage = (e, pageInfo) => {
    setLoadDataPaginate(true);
    setPage(pageInfo.activePage);
  };

  return (
    <Classroom
      data={props.classroom.classrooms}
      handlePage={handlePage}
      pagination={props.classroom.pagination}
    />
  );
};

const mapStateToProps = state => {
  return {
    classroom: state.classroom.classrooms
  };
};
export default connect(mapStateToProps)(Home);
