import React from "react";
import Alert from "../../components/Alert/CustomizedSnackbars";
import Loading from "../../components/Loading/CircularLoading";
import { useFetchRequestStagevsmodality } from "../../query/stage";
import { Stage } from "../../screens/Stage";

const Home = props => {
  const { data, isLoading } = useFetchRequestStagevsmodality();

  const stages = data;

  const alert = () => {
    if (props?.openAlert) {
      let status = null;
      let message = null;

      if (props?.error) {
        status = 0;
        message = props.error;
      } else {
        status = props.fetchClassroom.status;
        message = props.fetchClassroom.message;
      }

      if (status && message) {
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
      {isLoading ? (
        <Loading />
      ) : (
        <>
          <Stage
            stages={stages}
          />
          {alert()}
        </>
      )}
    </>
  );
};

export default Home;
