import React from "react";
import Grid from "@material-ui/core/Grid";
import { BoxBig } from "../../components/Boxes";
import { Paginator } from "../../components/Paginator";
import { makeStyles } from "@material-ui/core/styles";
import styles from "./styles";

const useStyles = makeStyles(styles);

const Home = props => {
  const classes = useStyles();

  const schools = () => {
    if (props.data) {
      return props.data.map((school, index) => (
        <Grid
          onClick={() => props.handleClick(school._id)}
          key={index}
          className={classes.box}
          item
          md={4}
          sm={3}
          xs={12}
        >
          <BoxBig addCursor={true} textRight="Ativa">
            <p className={classes.name}>{school.name}</p>
            <span className={classes.city}>{school.city}</span>
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
          <h1 className={`${classes.title} ${classes.floatLeft}`}>Escolas</h1>
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
        {schools()}
      </Grid>
    </>
  );
};

export default Home;
