import React from "react";
import { Link } from "react-router-dom";
import { makeStyles } from "@material-ui/core/styles";
import iconHome from "../../assets/images/home-icon.svg";
import iconClassroom from "../../assets/images/classroom-icon.svg";
import iconsChedule from "../../assets/images/schedule-icon.svg";
import iconsSchool from "../../assets/images/school-icon.svg";
import styles from "./styles";
const useStyles = makeStyles(styles);

const Sidebar = () => {
  const navItems = [
    { to: "/inicio", name: "Início", exact: true, Icon: iconHome },
    { to: "/cronograma", name: "Cronograma", exact: false, Icon: iconsChedule },
    { to: "/escolas", name: "Escolas", exact: false, Icon: iconsSchool },
    { to: "/turmas", name: "Turmas", exact: false, Icon: iconClassroom }
  ];

  const classes = useStyles();
  return (
    <div className={`${classes.root}`}>
      <ul className={`${classes.menu}`}>
        {navItems.map(({ to, name, Icon }, index) => (
          <li key={index}>
            <Link className={`${classes.linkMenu} ${classes.liMenu}`} to={to}>
              <img src={Icon} alt="" />
              <span className={classes.span}>{name}</span>
            </Link>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default Sidebar;
