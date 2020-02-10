import styleBase from "../../styles";

const useStyles = {
  contentStart: {
    color: styleBase.colors.grayClear,
    fontFamily: styleBase.typography.types.light,
    textAlign: "center",
    fontSize: styleBase.typography.font.small,
    "& img": {
      marginTop: 40
    },
    "& p": {
      margin: 0
    },
    "& h1": {
      color: styleBase.colors.gray,
      fontSize: styleBase.typography.font.midium
    }
  },
  marginButtom: {
    marginBottom: 60
  },
  marginTop: {
    marginTop: 60
  },
  marginTop30: {
    marginTop: 30
  },
  marginLeftButton: {
    marginLeft: 20
  },
  selectField: {
    height: 40
  },
  contentMain: {
    width: "100%"
  },
  formGroup: {
    "& span": {
      color: styleBase.colors.gray
    }
  },
  formFieldError: {
    marginTop: 10,
    color: styleBase.colors.gray,
    fontSize: styleBase.typography.font.small,
    display: "block",
    fontFamily: styleBase.typography.types.light
  },
  textField: {
    width: "100%",
    marginTop: 20,
    "& label": {
      marginBottom: 10
    }
  },
  formControl: {
    width: "100%",
    marginTop: 20,
    "& label": {
      marginBottom: 10
    }
  },
  boxNumberRegistration: {
    background: styleBase.colors.grayClear,
    color: styleBase.colors.gray,
    borderRadius: "8px",
    fontFamily: styleBase.typography.types.semiBold,
    paddingTop: 10,
    paddingBottom: 10,
    marginTop: 80
  }
};

export default useStyles;
