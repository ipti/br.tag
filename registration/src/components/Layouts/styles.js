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
    borderRightColor: styleBase.colors.grayClear,
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
    "&:hover": {
      color: styleBase.colors.gray,
      fontWidth: "bold",
      "& svg": {
        stroke: `${styleBase.colors.pink} !important`
      }
    },
    marginBottom: 20,
    float: "left",
    width: "100%"
  },
  linkMenu: {
    color: styleBase.colors.grayClear,
    textDecoration: "none"
  },
  boxContentMain: {
    display: "block",
    margin: 40,
    fontFamily: styleBase.typography.types.regular,
    color: styleBase.colors.gray
  },
  span: {
    marginLeft: 10
  }
};

export default useStyles;
