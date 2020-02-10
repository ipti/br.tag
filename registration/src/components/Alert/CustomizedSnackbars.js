import React from "react";
import Snackbar from "@material-ui/core/Snackbar";
import MuiAlert from "@material-ui/lab/Alert";
import { makeStyles } from "@material-ui/core/styles";

function Alert(props) {
  return <MuiAlert elevation={6} variant="filled" {...props} />;
}

const useStyles = makeStyles(theme => ({
  root: {
    width: "100%",
    "& > * + *": {
      marginTop: theme.spacing(2)
    },
    left: "24%"
  }
}));

export default function CustomizedSnackbars(props) {
  const classes = useStyles();

  return (
    <div className={classes.root}>
      <Snackbar
        anchorOrigin={{
          vertical: "bottom",
          horizontal: "left"
        }}
        open={props.open}
        autoHideDuration={
          props.autoHideDuration ? props.autoHideDuration : 6000
        }
        onClose={props.handleOpen}
      >
        <Alert
          onClose={props.handleClose}
          severity={props.status === "1" ? "success" : "error"}
        >
          {props.message}
        </Alert>
      </Snackbar>
    </div>
  );
}
