import styleBase from "../../styles";

const useStyles = {
  root: {
    width: "456px"
  },
  paginate: {
    listStyleType: "none",
    paddingLeft: 25,
    fontFamily: styleBase.typography.types.light,
    marginTop: 0,
    "& li": {
      float: "left",
      "& a": {
        textDecoration: "none",
        border: "1px solid",
        borderRadius: "6px",
        borderColor: styleBase.colors.purple,
        color: styleBase.colors.gray,
        padding: "3px 6px",
        fontSize: styleBase.typography.font.small,
        width: 20,
        height: 20,
        float: "left",
        textAlign: "center",
        marginLeft: 10,
        "&:hover": {
          background: styleBase.colors.purple,
          color: styleBase.colors.white
        }
      }
    }
  },
  activePage: {
    background: styleBase.colors.purple,
    color: `${styleBase.colors.white} !important`
  },
  arrows: {
    float: "left",
    fontSize: styleBase.typography.font.large,
    color: styleBase.colors.purple,
    marginTop: "-10px",
    cursor: "pointer"
  },
  arrowsRight: {
    marginLeft: 10
  }
};

export default useStyles;
