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
    padding: "6px 70px",
    width: "100%"
  }
};

export default useStyles;
