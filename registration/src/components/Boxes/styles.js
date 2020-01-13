import styleBase from "../../styles";

const useStyles = {
  contentBox: {
    border: "1px solid",
    borderRadius: "8px",
    borderColor: styleBase.colors.purple,
    padding: "20px",
    width: "88%"
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
    color: styleBase.colors.grayClear
  },
  title: {
    fontSize: styleBase.typography.font.small,
    color: styleBase.colors.gray
  },
  boxWithoutImage: {
    marginBottom: 30
  },
  boxDescriptionCalssroom: {
    color: styleBase.colors.grayClear,
    fontSize: styleBase.typography.font.extraSmall3
  },
  boxDescriptionCalssroomTitle: {
    color: styleBase.colors.gray,
    fontSize: styleBase.typography.font.extraSmall3
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
    color: styleBase.colors.white,
    fontSize: styleBase.typography.font.extraSmall2,
    padding: "5px 8px",
    borderRadius: 8,
    marginRight: 6
  }
};

export default useStyles;
