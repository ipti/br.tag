import { colors, typography } from '../../styles';

const styles = {
  container: {
    // padding: '15px 25px !important'
  },
  boxStatusStudent: {
    border: '1px solid',
    borderRadius: '30px',
    borderColor: colors.purple,
    color: colors.purple,
    textAlign: 'center',
    paddingTop: 10,
    paddingBottom: 10,
    width: '250px'
  },
  truncate: {
    width: '100%',
    whiteSpace: 'nowrap',
    overflow: 'hidden',
    textOverflow: 'ellipsis'
  },
  floatLeft: {
    float: 'left'
  },
  floatRight: {
    float: 'right'
  },
  borderGray: {
    border: `solid 1px ${colors.grayClear1}`
  },
  borderRed: {
    border: `solid 1px ${colors.red}`
  },
  boxStudent: {
    borderRadius: '8px',
    fontFamily: typography.types.light,
    width: '100%',
    padding: '10px 5px 0px 5px',
    position: 'relative'
  },
  iconStudent: {
    marginRight: 15,
    float: 'left',
    '& img': {
      borderRadius: '6px',
      backgroundColor: colors.blueClear,
      width: '45px'
    }
  },
  nameStudent: {
    width: '78%',
    fontSize: typography.font.extraSmall,
    fontFamily: typography.types.regular,
    color: colors.grayDark
  },
  subtitleStudent: {
    color: colors.gray,
    fontSize: typography.font.extraSmall,
    display: 'block'
  },
  '@media(max-width: 1440px)': {
    iconStudent: {
      marginRight: 6
    }
  }
};

export { styles };
