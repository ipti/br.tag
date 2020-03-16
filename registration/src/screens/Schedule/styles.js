import styleBase from "../../styles";

const useStyles = {
  textField: {
    width: "100%",
    marginTop: 8
  },
  marginButtom: {
    marginTop: 20
  },
  switch: {
    marginTop: 30
  },
  title: {
    marginTop: 0,
    marginBottom: 3,
    fontFamily: styleBase.typography.types.light,
    fontWeight: "unset"
  },
  boxTitlePagination: {
    marginBottom: 40
  },
  floatLeft: {
    float: "left"
  },
  floatRight: {
    float: "right"
  },
  lineGrayClean: {
    backgroundColor: styleBase.colors.grayClearOne,
    width: "100%",
    height: 1,
    margin: "10px 0"
  },
  contentSchedule: {
    position: "relative"
  },
  addSchedule: {
    position: "fixed",
    right: 20,
    bottom: 20
  },
  formFieldError: {
    color: styleBase.colors.red,
    fontSize: styleBase.typography.font.extraSmall
  }
};

export default useStyles;
