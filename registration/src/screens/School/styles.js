import styleBase from "../../styles";

const useStyles = {
  name: {
    color: styleBase.colors.gray,
    marginTop: 30,
    marginBottom: 0,
    fontSize: styleBase.typography.font.small
  },
  city: {
    color: styleBase.colors.grayClear,
    fontSize: styleBase.typography.font.extraSmall
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
  links: {
    display: "block"
  },
  floatLeft: {
    float: "left"
  },
  floatRight: {
    float: "right"
  },
  linePurple: {
    width: 90,
    height: 5,
    backgroundColor: styleBase.colors.purple,
    borderRadius: 50,
    display: "block"
  },
  boxCalssroom: {
    marginTop: 90
  },
  label: {
    color: styleBase.colors.grayClear,
    marginTop: 0,
    marginBottom: 10
  },
  boxAddress: {
    width: 330
  },
  boxSchool: {
    marginRight: 12
  },
  lineGrayClean: {
    backgroundColor: styleBase.colors.grayClear,
    display: "block",
    width: "100%",
    height: 2,
    margin: "30px 0"
  },
  boxImageMale: {
    backgroundColor: styleBase.colors.purple,
    height: 58,
    paddingTop: 2,
    padding: "15px 10px 2px 10px",
    borderRadius: 5,
    marginRight: 12
  },
  boxManager: {
    width: 240
  }
};

export default useStyles;
