import styleBase from "../../styles";

const useStyles = {
  buttomWhite: {
    backgroundColor: styleBase.colors.white,
    border: "none",
    borderRadius: "5px",
    color: styleBase.colors.purple,
    fontSize: styleBase.typography.font.small,
    fontFamily: styleBase.typography.types.bold,
    padding: "10px 20px"
  },
  buttomPurple: {
    backgroundColor: styleBase.colors.purple,
    border: "none",
    borderRadius: "50px",
    color: styleBase.colors.white,
    fontSize: styleBase.typography.font.midium,
    fontFamily: styleBase.typography.types.light,
    padding: "6px 10px",
    width: "100%",
    textAlign: "center"
  },
  buttomLinePurple: {
    border: "1px solid",
    borderRadius: "50px",
    backgroundColor: styleBase.colors.white,
    borderColor: styleBase.colors.purple,
    color: styleBase.colors.purple,
    fontSize: styleBase.typography.font.midium,
    fontFamily: styleBase.typography.types.light,
    textAlign: "center",
    padding: "6px 10px",
    width: "100%"
  }
};

export default useStyles;
