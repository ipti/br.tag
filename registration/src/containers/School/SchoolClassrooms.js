import React, { useState, useEffect } from "react";
import { SchoolClassroom } from "../../screens/School";
import { connect } from "react-redux";

const Home = props => {
  const [loadData, setLoadData] = useState(true);

  useEffect(() => {
    if (loadData) {
      props.dispatch({
        type: "FETCH_SCHOOL",
        data: { id: props.match.params.id }
      });
      setLoadData(false);
    }
  }, [loadData, props]);
  return <SchoolClassroom data={props.school.school} />;
};

const mapStateToProps = state => {
  return {
    school: state.school.school
  };
};
export default connect(mapStateToProps)(Home);
