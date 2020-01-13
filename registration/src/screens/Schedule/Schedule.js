import React from "react";
import Grid from "@material-ui/core/Grid";
import { BoxBig, BoxDiscriptionSchedule } from "../../components/Boxes";
import { Paginator } from "../../components/Paginator";
import { makeStyles } from "@material-ui/core/styles";
import styles from "./styles";

const useStyles = makeStyles(styles);

const Home = props => {
  const classes = useStyles();

  const shedules = () => {
    if (props.data) {
      return props.data.map((schedule, index) => (
        <Grid
          onClick={() => props.handleClick(schedule._id)}
          key={index}
          className={classes.box}
          item
          md={4}
          sm={3}
          xs={12}
        >
          <BoxBig
            subtitle="Turma"
            addCursor={true}
            title="Cronograma"
            textRight={schedule.year}
          >
            <BoxDiscriptionSchedule
              title="Transferência Interna"
              color="pink"
              subtitle={`${schedule.internalTransferDateStart} - ${schedule.internalTransferDateEnd}`}
            />
            <div
              className={`${classes.lineGrayClean} ${classes.floatLeft}`}
            ></div>
            <BoxDiscriptionSchedule
              title="Alunos Novos"
              subtitle={`${schedule.newStudentDateStart} - ${schedule.newStudentDateEnd}`}
            />
          </BoxBig>
        </Grid>
      ));
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
          <h1 className={`${classes.title} ${classes.floatLeft}`}>
            Cronograma
          </h1>
          <div className={`${classes.floatRight}`}>
            {props.pagination && (
              <Paginator
                handlePage={props.handlePage}
                pagination={props.pagination}
              />
            )}
          </div>
        </Grid>
      </Grid>
      <Grid container direction="row" spacing={8}>
        {shedules()}
      </Grid>
    </>
  );
};

export default Home;
