import React from "react";
import Grid from "@material-ui/core/Grid";
import { makeStyles } from "@material-ui/core/styles";
import { Paginator } from "../../components/Paginator";
import { BoxBig, BoxDiscriptionClassroom } from "../../components/Boxes";
import styles from "./styles";
const useStyles = makeStyles(theme => styles);

const Home = props => {
  const classes = useStyles();

  const pagination = () => {
    if (props.pagination) {
      return (
        <Paginator
          pagination={props.pagination}
          handlePage={props.handlePage}
        />
      );
    }
  };

  const classrooms = () => {
    if (props.data) {
      return props.data.map((classroom, index) => {
        return (
          <Grid key={index} item md={4} sm={4} xs={12}>
            <BoxBig
              link={`turmas/${classroom._id}`}
              title={classroom.name}
              subtitle="Turma"
              addCursor={true}
              textRight=""
            >
              <BoxDiscriptionClassroom
                title="Preenchidas"
                registrationConfirmed={`${classroom.confirmed}`}
              />
              <BoxDiscriptionClassroom
                title="Restante"
                registrationRemaining={`${classroom.remaining}`}
              />
            </BoxBig>
          </Grid>
        );
      });
    }

    return [];
  };

  return (
    <>
      <Grid container direction="row">
        <Grid
          className={classes.boxTitlePagination}
          item
          md={12}
          sm={12}
          xs={12}
        >
          <h1 className={`${classes.title} ${classes.floatLeft}`}>Turmas</h1>
          <div className={`${classes.floatRight}`}>{pagination()}</div>
        </Grid>
      </Grid>
      <Grid container direction="row" spacing={3}>
        {classrooms()}
      </Grid>
    </>
  );
};

export default Home;
