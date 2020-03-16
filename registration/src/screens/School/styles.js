import styleBase from "../../styles";

const useStyles = {
  name: {
    color: styleBase.colors.gray,
    marginTop: 30,
    marginBottom: 0,
    fontSize: styleBase.typography.font.small,
    whiteSpace: 'nowrap',
    overflow: 'hidden',
    textOverflow: 'ellipsis'
  },
  city: {
    display: 'block',
    color: styleBase.colors.grayClear,
    fontSize: styleBase.typography.font.extraSmall,
    whiteSpace: 'nowrap',
    overflow: 'hidden',
    textOverflow: 'ellipsis'
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
    marginBottom: 4
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
    display: 'block',
    whiteSpace: "nowrap",
    overflow: "hidden",
    textOverflow: "ellipsis"
  },
  iconHouse: {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    borderRadius: "6px",
    backgroundColor: styleBase.colors.purple,
    padding: "6px 6px",
    marginRight: 15,
    width: 43
  },
  cursor: {
    cursor: "pointer"
  }
};

export default useStyles;
