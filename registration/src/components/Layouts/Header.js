import React from "react";
import AppBar from "@material-ui/core/AppBar";
import Toolbar from "@material-ui/core/Toolbar";
import Menu from "@material-ui/core/Menu";
import MenuItem from "@material-ui/core/MenuItem";
import IconButton from "@material-ui/core/IconButton";
import AccountCircle from "@material-ui/icons/AccountCircle";
import { useHistory } from "react-router-dom";
import {
  makeStyles,
  createMuiTheme,
  ThemeProvider
} from "@material-ui/core/styles";
import styleBase from "../../styles";
import { isAuthenticated } from "../../services/auth";

const theme = createMuiTheme({
  palette: {
    primary: {
      main: "#fff"
    }
  }
});

const useStyles = makeStyles(theme => ({
  root: {
    boxShadow:
      "0px 0px 0px -1px rgba(0,0,0,0.2), 0px -1px 5px 0px rgba(0,0,0,0.14), 0px 1px 6px 0px rgba(0,0,0,0.12)",
    flexGrow: 1
  },
  tooBar: {
    minHeight: "unset"
  },
  menuButton: {
    marginRight: theme.spacing(2)
  },
  title: {
    marginTop: 6,
    marginBottom: 6,
    flexGrow: 1,
    color: styleBase.colors.purple,
    fontFamily: styleBase.typography.types.extraLight
  },
  secundary: {
    color: styleBase.colors.purple
  }
}));

const Header = () => {
  const classes = useStyles();
  let history = useHistory();
  const [anchorEl, setAnchorEl] = React.useState(null);
  const open = Boolean(anchorEl);

  const handleMenu = event => {
    setAnchorEl(event.currentTarget);
  };

  const handleClose = () => {
    setAnchorEl(null);
  };

  const handleLogout = () => {
    localStorage.clear();
    history.push("/login");
  };

  return (
    <ThemeProvider theme={theme}>
      <div className={classes.grow}>
        <AppBar className={classes.root} theme={theme} position="static">
          <Toolbar className={classes.tooBar}>
            <h2 className={classes.title}>Matrícula</h2>
            {isAuthenticated() && (
              <>
                <IconButton
                  theme={theme}
                  aria-label="account of current user"
                  aria-controls="menu-appbar"
                  aria-haspopup="true"
                  onClick={handleMenu}
                  className={classes.secundary}
                >
                  <AccountCircle />
                </IconButton>
                <Menu
                  id="menu-appbar"
                  anchorEl={anchorEl}
                  anchorOrigin={{
                    vertical: "top",
                    horizontal: "left"
                  }}
                  keepMounted
                  transformOrigin={{
                    vertical: "top",
                    horizontal: "left"
                  }}
                  open={open}
                  onClose={handleClose}
                >
                  <MenuItem onClick={handleLogout}>Logout</MenuItem>
                </Menu>
              </>
            )}
          </Toolbar>
        </AppBar>
      </div>
    </ThemeProvider>
  );
};

export default Header;
