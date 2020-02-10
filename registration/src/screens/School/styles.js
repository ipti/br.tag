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
  boxClassroom: {
    marginTop: 90
  },
  label: {
    color: styleBase.colors.grayClear,
    marginTop: 0,
    marginBottom: 6
  },
  boxAddress: {
    width: 330
  },
  boxSchool: {
    marginRight: 12
  },
  lineGrayClean: {
    backgroundColor: styleBase.colors.grayClearOne,
    display: "block",
    width: "100%",
    height: 2,
    margin: "30px 0"
  },
  boxImageMale: {
    backgroundColor: styleBase.colors.purple,
    width: 32,
    paddingTop: 2,
    padding: "15px 10px 0px 10px",
    borderRadius: 5,
    marginRight: 12
  },
  boxManager: {
    width: 240
  },
  truncate: {
    width: "230px",
    whiteSpace: "nowrap",
    overflow: "hidden",
    textOverflow: "ellipsis",
    "&:hover": {
      whiteSpace: "unset"
    }
  },
  iconHouse: {
    borderRadius: "6px",
    backgroundColor: styleBase.colors.purple,
    width: "30px",
    padding: "6px 10px 6px 10px",
    marginRight: 15
  }
};

export default useStyles;
