import { colors } from '../../styles';

const styles = {
  container: {
    padding: '15px 25px !important'
  },
  boxCard: {
    border: `solid 1px ${colors.grayClear1}`,
    borderRadius: 10,
    padding: 15,
    textDecoration: 'none',
    color: colors.gray,

    '& > a': {
      display: 'inline-block',
      textDecoration: 'none',
      color: colors.gray
    }
  },
  title1: {
    color: colors.grayClear1
  },
  boxTitle: {
    display: 'block',
    marginTop: 20,
    marginBottom: 40
  },
  boxYear: {
    backgroundColor: colors.yellow,
    borderRadius: 15,
    padding: '3px 20px',
    float: 'left',
    display: 'block'
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
  }
};

export { styles };
