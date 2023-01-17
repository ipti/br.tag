import React from "react";

// Material UI
import Grid from "@material-ui/core/Grid";
import Alert from "@material-ui/lab/Alert";
import { makeStyles } from "@material-ui/core/styles";

// Components
import { BoxBig } from "../../components/Boxes";
import { Paginator } from "../../components/Paginator";
import List from "../../components/List";

// Styles
import styles from "./styles";

const useStyles = makeStyles(styles);

const Home = ({ data, pagination, handlePage, activePage }) => {
  const classes = useStyles();
  console.log(data)
  const schools = () => {
    const schoolList = data ?? [];

    console.log(schoolList)

    return schoolList.map((school, index) => (
      <Grid key={index} className={classes.box} item md={4} sm={3} xs={12}>
        <BoxBig link={`escolas/${school.inep_id}`} textRight="Ativa">
          <p title={school.name} className={classes.name}>
            {school.name}
          </p>
          <span title={school.city} className={classes.city}>
            {school.city}
          </span>
        </BoxBig>
      </Grid>
    ));
  };

  return (
    <>
      <Grid container direction="row">
        <Grid className={classes.boxTitlePagination} item xs={12}>
          <h1 className={`${classes.title} ${classes.floatLeft}`}>Escolas</h1>
          <div className={`${classes.floatRight}`}>
            <Paginator
              pagination={pagination}
              handlePage={handlePage}
              activePage={activePage}
            />
          </div>
        </Grid>
      </Grid>
      <Grid container direction="row" spacing={8}>
        <List items={schools()}>
          <Grid item xs={12}>
            <Alert variant="outlined" severity="warning">
              Nenhum escola cadastrada
            </Alert>
          </Grid>
        </List>
      </Grid>
    </>
  );
};

export default Home;
