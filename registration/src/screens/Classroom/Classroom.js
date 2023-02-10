import React from "react";

// Material UI
import Grid from "@material-ui/core/Grid";
import { createMuiTheme, makeStyles, ThemeProvider } from "@material-ui/core/styles";
import Alert from "@material-ui/lab/Alert";
import Fab from "@material-ui/core/Fab";
import AddIcon from "@material-ui/icons/Add";

import { Paginator } from "../../components/Paginator";
import { BoxBig, BoxDiscriptionClassroom } from "../../components/Boxes";
import List from "../../components/List";

// Styles
import styles from "./styles";
import styleBase from "../../styles";
import { Link } from "react-router-dom";

const theme = createMuiTheme({
  palette: {
    primary: {
      main: styleBase.colors.purple
    }
  }
});

const useStyles = makeStyles(theme => styles);


const Classroom = ({ classrooms, pagination, handlePage, activePage }) => {
  const classes = useStyles();

  console.log(classrooms)


  const classroom = () => {


    return classrooms.map((classroom, index) => {
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
    <div style={{position: "relative"}}>
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
        <List items={classroom()}>
          <Grid item xs={12}>
            <Alert variant="outlined" severity="warning">
              Nenhuma turma cadastrada
            </Alert>
          </Grid>
        </List>
      </Grid>
      <Link to="/turmas/adicionar" className={`${classes.addStage}`}>
        <ThemeProvider theme={theme}>
          <Fab color="primary" aria-label="add">
            <AddIcon />
          </Fab>
        </ThemeProvider>
      </Link>
    </div>
  );
};

export default Classroom;
