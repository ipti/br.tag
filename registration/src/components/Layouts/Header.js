import React from "react";
import AppBar from "@material-ui/core/AppBar";
import Toolbar from "@material-ui/core/Toolbar";
import Menu from "@material-ui/core/Menu";
import MenuItem from "@material-ui/core/MenuItem";
import IconButton from "@material-ui/core/IconButton";
import AccountCircle from "@material-ui/icons/AccountCircle";
import { useHistory } from "react-router-dom";
import { makeStyles } from "@material-ui/core/styles";
import styleBase from "../../styles";
import { isAuthenticated } from "../../services/auth";


const useStyles = makeStyles({
  root: {
    flexGrow: 1,
    boxShadow:
      "0px 0px 0px -1px rgba(0,0,0,0.2), 0px -1px 5px 0px rgba(0,0,0,0.14), 0px 1px 6px 0px rgba(0,0,0,0.12)",
    backgroundColor: styleBase.colors.white
  },
  tooBar: {
    minHeight: "unset"
  },
  menuButton: {
    marginRight: 15,
    display: "none"
  },
  title: {
    margin: '8px 15px',
    flexGrow: 1,
    color: styleBase.colors.purple,
    fontFamily: styleBase.typography.types.extraLight
  },
  accountButton: {
    color: styleBase.colors.grayClear
  },
  "@media(max-width: 600px)": {
    menuButton: {
      display: "block"
    }
  }
});

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
    <AppBar classes={{ root: classes.root }} position="static">
      <Toolbar className={classes.tooBar} disableGutters>
        <h2 className={classes.title}>Matrícula</h2>
        {isAuthenticated() && (
          <>
            <IconButton
              aria-label="account of current user"
              aria-controls="menu-appbar"
              aria-haspopup="true"
              onClick={handleMenu}
              className={classes.accountButton}
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
              <MenuItem onClick={handleLogout}>Sair</MenuItem>
            </Menu>
          </>
        )}
      </Toolbar>
    </AppBar>
  );
};

export default Header;
