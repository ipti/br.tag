import Background from "../../assets/images/login-banckground.jpg";
import styleBase from "../../styles";

const useStyles = {
  root: {
    "& .MuiTextField-root": {
      width: "100%",
      marginBottom: "10px"
    },
    minHeight: "100%",
    background: `${styleBase.colors.blue} url(${Background})`,
    fontFamily: styleBase.typography.types.light
  },
  marginTopContentLeft: {
    marginTop: 264
  },
  titleLogin: {
    textAlign: "center",
    fontSize: "30px",
    marginTop: 80,
    marginBottom: 60,
    color: styleBase.colors.purple
  },
  imageLogin: {
    marginBottom: 40
  },
  textCenter: {
    textAlign: "center"
  },
  linkRegister: {
    marginTop: 30,
    backgroundColor: styleBase.colors.white,
    border: "none",
    borderRadius: "5px",
    color: styleBase.colors.purple,
    fontSize: styleBase.typography.font.small,
    fontFamily: styleBase.typography.types.bold,
    padding: "10px 20px",
    textDecoration: "none"
  },
  colorIcon: {
    color: styleBase.colors.grayClear
  },
  contentLeft: {
    fontSize: styleBase.typography.font.small,
    color: styleBase.colors.white
  },
  contentRight: { backgroundColor: styleBase.colors.white },
  titleBig: {
    fontSize: styleBase.typography.font.extraLarge,
    fontFamily: styleBase.typography.types.regular
  },
  resetPassword: {
    color: styleBase.colors.grayClear,
    fontSize: styleBase.typography.font.small,
    marginTop: 30,
    marginBottom: 30,
    width: "100%"
  },
  boxRegister: {
    marginTop: 30
  },
  boxError: {
    height: 48,
    color: styleBase.colors.red
  },
  link: {
    fontFamily: styleBase.typography.types.bold,
    color: styleBase.colors.gray,
    textDecoration: "none",
    marginLeft: 5
  },
  formFieldError: {
    color: styleBase.colors.red,
    display: "block",
    marginBottom: 5
  },
  "@media(max-width: 600px)": {
    marginTopContentLeft: {
      marginTop: 20
    },
    contentLeft: {
      marginLeft: 20
    },
    boxRegister: {
      marginBottom: 40
    }
  }
};

export default useStyles;
