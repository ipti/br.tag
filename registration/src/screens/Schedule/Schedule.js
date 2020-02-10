import React from "react";
import Grid from "@material-ui/core/Grid";
import { BoxBig, BoxDiscriptionSchedule } from "../../components/Boxes";
import { Paginator } from "../../components/Paginator";
import { Link } from "react-router-dom";
import Fab from "@material-ui/core/Fab";
import AddIcon from "@material-ui/icons/Add";
import styleBase from "../../styles";

import {
  makeStyles,
  createMuiTheme,
  ThemeProvider
} from "@material-ui/core/styles";
import styles from "./styles";

const useStyles = makeStyles(styles);

const theme = createMuiTheme({
  palette: {
    primary: {
      main: styleBase.colors.purple
    }
  }
});

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

  const shedules = () => {
    if (props.data) {
      return props.data.map((schedule, index) => (
        <Grid key={index} className={classes.box} item md={4} sm={3} xs={12}>
          <BoxBig
            link={`cronograma/editar/${schedule._id}`}
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
              title="Novos Alunos"
              subtitle={`${schedule.newStudentDateStart} - ${schedule.newStudentDateEnd}`}
            />
          </BoxBig>
        </Grid>
      ));
    }
    return [];
  };

  return (
    <div className={classes.contentSchedule}>
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
          <div className={`${classes.floatRight}`}>{pagination()}</div>
        </Grid>
      </Grid>
      <Grid container direction="row" spacing={4}>
        {shedules()}
      </Grid>
      <Link to="/cronograma/adicionar" className={`${classes.addSchedule}`}>
        <ThemeProvider theme={theme}>
          <Fab color="primary" aria-label="add">
            <AddIcon />
          </Fab>
        </ThemeProvider>
      </Link>
    </div>
  );
};

export default Home;
