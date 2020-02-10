import styleBase from "../../styles";

const useStyles = {
  grow: {
    flexGrow: 1
  },
  contentMain: {
    overflow: "hidden"
  },
  root: {
    display: "block",
    borderRight: "1px solid",
    borderRightColor: styleBase.colors.grayClearOne,
    height: "130vh",
    paddingTop: "25px"
  },
  menu: {
    listStyleType: "none",
    paddingLeft: 25,
    fontFamily: styleBase.typography.types.light,
    marginTop: 0
  },
  liMenu: {
    marginBottom: 20,
    float: "left",
    width: "100%"
  },
  linkMenu: {
    color: styleBase.colors.grayClear,
    textDecoration: "none",
    "& .iconActive": {
      display: "none"
    },
    "& .iconInactive": {
      display: "block"
    },
    "&:hover": {
      color: styleBase.colors.gray,
      fontWidth: "bold",
      "& .iconActive": {
        display: "block"
      },
      "& .iconInactive": {
        display: "none"
      }
    }
  },
  boxContentMain: {
    display: "block",
    margin: 40,
    fontFamily: styleBase.typography.types.regular,
    color: styleBase.colors.gray
  },
  iconActive: {
    display: "none"
  },
  span: {
    marginLeft: 10
  },
  activeLink: {
    color: styleBase.colors.gray,
    "& .iconActive": {
      display: "block"
    },
    "& .iconInactive": {
      display: "none"
    }
  },
  floatLeft: {
    float: "left"
  }
};

export default useStyles;
