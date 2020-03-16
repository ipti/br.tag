import styleBase from "../../styles";

const useStyles = {
  contentStart: {
    color: styleBase.colors.grayClear,
    fontFamily: styleBase.typography.types.light,
    textAlign: "center",
    fontSize: styleBase.typography.font.midium,
    "& img": {
      marginTop: 40
    },
    "& p": {
      margin: 0
    },
    "& h1": {
      color: styleBase.colors.gray,
      fontSize: styleBase.typography.font.large
    }
  }
};

export default useStyles;
