import styleBase from "../../styles";
import ArrowLeft from "../../assets/images/arrow-left.svg";
import ArrowRight from "../../assets/images/arrow-right.svg";

const useStyles = {
  root: {
    listStyleType: "none",
    paddingLeft: 25,
    fontFamily: styleBase.typography.types.light,
    marginTop: 0,
    "& a": {
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
      cursor: 'pointer',
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
      outline: 'none',
      transition: 'ease-in',
      "&:hover": {
        background: styleBase.colors.purple,
        color: styleBase.colors.white
      },
      "&.active": {
        background: styleBase.colors.purple,
        color: `${styleBase.colors.white} !important`,

        "&:hover": {
          background: styleBase.colors.purpleDark,
          color: styleBase.colors.white
        },
      },
      "&:first-child": {
        background: `url(${ArrowLeft}) center center no-repeat`
      },
      "&:last-child": {
        background: `url(${ArrowRight}) center center no-repeat`
      },
      "&:first-child, &:last-child": {
        borderColor: styleBase.colors.white,
        fontSize: styleBase.typography.font.large,
        color: styleBase.colors.purple,
        textIndent: "-99999em",
        padding: "unset",
        height: "26px",
        cursor: "pointer"
      }
    }
  },
  "@media(max-width: 600px)": {
    root: {
      "& a": {
        fontSize: "14px"
      }
    }
  }
};

export default useStyles;
