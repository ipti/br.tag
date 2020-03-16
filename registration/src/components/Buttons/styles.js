import styleBase from "../../styles";

const useStyles = {
  buttomWhite: {
    backgroundColor: styleBase.colors.white,
    border: "none",
    borderRadius: "5px",
    color: styleBase.colors.purple,
    fontSize: styleBase.typography.font.small,
    fontFamily: styleBase.typography.types.bold,
    cursor: 'pointer',
    transition: 'ease-in',
    outline: 'none'
  },
  buttomPurple: {
    backgroundColor: styleBase.colors.purple,
    border: "none",
    borderRadius: "50px",
    color: styleBase.colors.white,
    fontSize: styleBase.typography.font.medium,
    fontFamily: styleBase.typography.types.light,
    width: "100%",
    textAlign: "center",
    padding: "8px 18px",
    cursor: 'pointer',
    transition: 'ease-in',
    outline: 'none',

    '&:hover': {
      backgroundColor: styleBase.colors.purpleDark
    }
  },
  buttomLinePurple: {
    border: "1px solid",
    borderRadius: "50px",
    backgroundColor: styleBase.colors.white,
    borderColor: styleBase.colors.purple,
    color: styleBase.colors.purple,
    fontSize: styleBase.typography.font.medium,
    fontFamily: styleBase.typography.types.light,
    textAlign: "center",
    padding: "6px 10px",
    cursor: 'pointer',
    transition: 'ease-in',
    outline: 'none',
    width: "100%",

    '&:hover': {
      boxShadow: `0 0 1px 1px ${styleBase.colors.purple};`
    }
  }
};

export default useStyles;
