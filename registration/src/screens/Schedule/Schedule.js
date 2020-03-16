import React from "react";

// Material UI
import Grid from "@material-ui/core/Grid";
import Fab from "@material-ui/core/Fab";
import AddIcon from "@material-ui/icons/Add";
import Alert from '@material-ui/lab/Alert';

import {
  makeStyles,
  createMuiTheme,
  ThemeProvider
} from "@material-ui/core/styles";

// Third party
import { Link } from "react-router-dom";

// Components
import { BoxBig, BoxDiscriptionSchedule } from "../../components/Boxes";
import { Paginator } from "../../components/Paginator";
import List from "../../components/List";

// Styles
import styleBase from "../../styles";
import styles from "./styles";


const useStyles = makeStyles(styles);

const theme = createMuiTheme({
  palette: {
    primary: {
      main: styleBase.colors.purple
    }
  }
});

const Schedule = ({ data, pagination, handlePage }) => {
  const classes = useStyles();

  const schedules = () => {
    const schedulesList = data ?? [];
  
    return schedulesList.map((schedule, index) => (
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
          <div className={`${classes.lineGrayClean} ${classes.floatLeft}`} ></div>
          <BoxDiscriptionSchedule
            title="Novos Alunos"
            subtitle={`${schedule.newStudentDateStart} - ${schedule.newStudentDateEnd}`}
          />
        </BoxBig>
      </Grid>
    ));
  };

  return (
    <div className={classes.contentSchedule}>
      <Grid container direction="row">
        <Grid className={classes.boxTitlePagination} item xs={12} >
          <h1 className={`${classes.title} ${classes.floatLeft}`}>
            Cronograma
          </h1>
          <div className={`${classes.floatRight}`}>
            <Paginator
              pagination={pagination}
              handlePage={handlePage}
            />
          </div>
        </Grid>
      </Grid>

      <Grid container direction="row" spacing={4}>
        <List items={schedules()} >
          <Grid item xs={12} >
            <Alert variant="outlined" severity="warning">
              Nenhum cronograma cadastrado
            </Alert>
          </Grid>
        </List>
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

export default Schedule;
