import styleBase from "../../styles";

const useStyles = {
  contentBox: {
    border: "1px solid",
    borderRadius: "8px",
    borderColor: styleBase.colors.purple,
    padding: "20px",
    width: "88%",
    textDecoration: "none"
  },
  floatLeft: {
    float: "left"
  },
  floatRight: {
    float: "right"
  },
  textRight: {
    backgroundColor: styleBase.colors.purple,
    color: styleBase.colors.white,
    padding: "0px 15px",
    borderRadius: "50px"
  },
  iconHouse: {
    marginTop: "-5px",
    width: "40px",
    height: "30px"
  },
  addCursor: {
    cursor: "pointer"
  },
  subtitle: {
    fontSize: styleBase.typography.font.extraSmall,
    color: styleBase.colors.grayClear,
    whiteSpace: 'nowrap',
    overflow: 'hidden',
    textOverflow: 'ellipsis'
  },
  title: {
    fontSize: styleBase.typography.font.small,
    color: styleBase.colors.gray,
    whiteSpace: 'nowrap',
    overflow: 'hidden',
    textOverflow: 'ellipsis'
  },
  boxWithoutImage: {
    marginBottom: 20
  },
  boxDescriptionCalssroom: {
    color: styleBase.colors.grayClear,
    fontSize: styleBase.typography.font.extraSmall
  },
  boxDescriptionCalssroomTitle: {
    color: styleBase.colors.gray,
    fontSize: styleBase.typography.font.extraSmall
  },
  boxDescriptionSchedule: {
    color: styleBase.colors.grayClear,
    fontSize: styleBase.typography.font.extraSmall
  },
  boxDescriptionScheduleSubtitle: {
    color: styleBase.colors.gray,
    fontSize: styleBase.typography.font.extraSmall
  },
  marginBox: {
    marginRight: 10
  },
  boxQuantityBackgroundPurple: {
    backgroundColor: styleBase.colors.purple
  },
  boxQuantityBackgroundPink: {
    backgroundColor: styleBase.colors.pink
  },
  boxQuantity: {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    color: styleBase.colors.white,
    fontSize: styleBase.typography.font.extraSmall,
    padding: "9px 9px",
    borderRadius: 8,
    marginRight: 8,
    minWidth: 20,
    textAlign: "center"
  },
  boxVacancies: {
    color: styleBase.colors.white,
    borderRadius: 8,
    padding: 20,
    fontFamily: styleBase.typography.types.light
  },
  backgroundBlue: {
    backgroundColor: styleBase.colors.blue
  },
  backgroundPurple: {
    backgroundColor: styleBase.colors.purple
  },
  backgroundPink: {
    backgroundColor: styleBase.colors.pink
  },
  quantity: {
    fontSize: styleBase.typography.font.large,
    marginTop: 50,
    marginBottom: 0
  },
  vacanciesTitle: {
    marginTop: 0
  },
  iconStudent: {
    marginRight: 15,
    float: "left",
    "& img": {
      borderRadius: "6px",
      backgroundColor: styleBase.colors.blueClear,
      width: "45px"
    }
  },
  boxStudent: {
    border: "1px solid",
    borderRadius: "8px",
    borderColor: styleBase.colors.purple,
    fontFamily: styleBase.typography.types.light,
    width: "100%",
    padding: "10px 5px 0px 5px",
    position: "relative"
  },
  subtitleStudent: {
    color: styleBase.colors.grayClear
  },
  confimedCicle: {
    width: 10,
    height: 10,
    display: "block",
    borderRadius: "50%",
    right: "8px",
    bottom: "5px",
    position: "absolute",
    backgroundColor: styleBase.colors.green
  },
  refusedCicle: {
    width: 10,
    height: 10,
    display: "block",
    borderRadius: "50%",
    right: "8px",
    bottom: "5px",
    position: "absolute",
    backgroundColor: styleBase.colors.red
  },
  nameStudent: {
    width: "80%",
    fontSize: styleBase.typography.font.small,
    color: styleBase.colors.gray
  },
  boxStatusStudent: {
    border: "1px solid",
    borderRadius: "30px",
    borderColor: styleBase.colors.purple,
    color: styleBase.colors.purple,
    textAlign: "center",
    paddingTop: 10,
    paddingBottom: 10,
    width: "250px"
  },
  truncate: {
    width: "230px",
    whiteSpace: "nowrap",
    overflow: "hidden",
    textOverflow: "ellipsis",
    "&:hover": {
      whiteSpace: "unset"
    }
  }
};
export default useStyles;
