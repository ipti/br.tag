import React from "react";
import { makeStyles } from "@material-ui/core/styles";
import AppBar from "@material-ui/core/AppBar";
import Toolbar from "@material-ui/core/Toolbar";
import { createMuiTheme, ThemeProvider } from "@material-ui/core/styles";
import styleBase from "../../styles";

const theme = createMuiTheme({
  palette: {
    primary: {
      main: "#fff"
    }
  }
});

const useStyles = makeStyles(theme => ({
  menuButton: {
    marginRight: theme.spacing(2)
  },
  title: {
    display: "none",
    [theme.breakpoints.up("sm")]: {
      display: "block"
    },
    color: styleBase.colors.purple,
    fontFamily: styleBase.typography.types.extraLight
  }
}));

const Header = () => {
  const classes = useStyles();

  return (
    <ThemeProvider theme={theme}>
      <div className={classes.grow}>
        <AppBar theme={theme} position="static">
          <Toolbar>
            <h2 className={classes.title}>Matrícula</h2>
          </Toolbar>
        </AppBar>
      </div>
    </ThemeProvider>
  );
};

export default Header;
