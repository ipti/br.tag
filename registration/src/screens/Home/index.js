import React from "react";
import Grid from "@material-ui/core/Grid";

const Wellcome = props => {
  return (
    <Grid container direction="row" spacing={8}>
      <Grid item md={12} sm={12} xs={12}>
        Bem vindo
      </Grid>
    </Grid>
  );
};

export default Wellcome;
