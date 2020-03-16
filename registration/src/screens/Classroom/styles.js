import styleBase from "../../styles";

const useStyles = {
  title: {
    marginTop: 0,
    marginBottom: 3,
    fontFamily: styleBase.typography.types.light,
    fontWeight: "unset"
  },
  boxTitlePagination: {
    marginBottom: 40
  },
  formControl: {
    width: "80%",
    marginBottom: 60,
    "& label": {
      marginBottom: 10
    }
  },
  boxContent: {
    marginTop: 30
  },
  boxContentRegistration: {
    marginTop: 90,
    marginBottom: 30
  },
  floatLeft: {
    float: "left"
  },
  floatRight: {
    float: "right"
  },
  label: {
    color: styleBase.colors.grayClear,
    margin: 0
  },
  iconStudent: {
    borderRadius: "6px",
    backgroundColor: styleBase.colors.blueClear,
    width: "50px",
    marginRight: 15
  },
  iconResponsable: {
    borderRadius: "6px",
    backgroundColor: styleBase.colors.purple,
    width: "28px",
    padding: "5px 10px 0 10px",
    marginRight: 15
  },
  iconHouse: {
    borderRadius: "6px",
    backgroundColor: styleBase.colors.purple,
    width: "30px",
    padding: "6px 10px 6px 10px",
    marginRight: 15
  },
  iconClassroom: {
    borderRadius: "6px",
    backgroundColor: styleBase.colors.pink,
    width: "30px",
    padding: "10px 4px 10px 10px",
    marginRight: 15
  },
  lineGrayClean: {
    backgroundColor: styleBase.colors.grayClearOne,
    display: "block",
    width: "100%",
    height: 2,
    margin: "30px 0"
  },
  boxButtons: {
    marginTop: 100
  },
  "@media(max-width: 600px)": {
    title: {
      fontSize: "16px"
    }
  }
};

export default useStyles;
