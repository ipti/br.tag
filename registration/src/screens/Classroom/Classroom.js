import React from "react";

// Material UI
import Grid from "@material-ui/core/Grid";
import { makeStyles } from "@material-ui/core/styles";
import Alert from "@material-ui/lab/Alert";

import { Paginator } from "../../components/Paginator";
import { BoxBig, BoxDiscriptionClassroom } from "../../components/Boxes";
import List from "../../components/List";

// Styles
import styles from "./styles";

const useStyles = makeStyles(theme => styles);

const Classroom = ({ data, pagination, handlePage, activePage }) => {
  const classes = useStyles();

  const classrooms = () => {

    console.log(data)
    const classroomList = data ?? [];

    console.log(classroomList)

    return classroomList?.data.map((classroom, index) => {
      return (
        <Grid key={index} item md={4} sm={4} xs={12}>
          <BoxBig
            link={`turmas/${classroom.id}`}
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
  };

  return (
    <>
      <Grid container direction="row">
        <Grid className={classes.boxTitlePagination} item xs={12}>
          <h1 className={`${classes.title} ${classes.floatLeft}`}>Turmas</h1>
          <div className={`${classes.floatRight}`}>
            <Paginator
              pagination={pagination}
              handlePage={handlePage}
              activePage={activePage}
            />
          </div>
        </Grid>
      </Grid>
      <Grid container direction="row" spacing={3}>
        <List items={classrooms()}>
          <Grid item xs={12}>
            <Alert variant="outlined" severity="warning">
              Nenhuma turma cadastrada
            </Alert>
          </Grid>
        </List>
      </Grid>
    </>
  );
};

export default Classroom;
