import { colors, typography } from '../../styles';

const styles = (theme) => ({
  grow: {
    flexGrow: 1
  },
  contentMain: {
    overflow: 'hidden'
  },
  root: {
    display: 'block',
    borderRight: '1px solid',
    borderRightColor: colors.grayClear2,
    minHeight: 'calc(100% - 25px)',
    padding: '25px 10px 5px'
  },
  boxContentMain: {
    display: 'block',
    margin: '40px 30px 30px',
    fontFamily: typography.types.regular,
    color: colors.gray
  },
  header: {
    flexBasis: 'auto',
    flexGrow: 0
  },
  content: {
    flexBasis: 'auto',
    flexGrow: 1,

    '& > div:first-child': {
      flexBasis: 270
    },
    '& > div:last-child': {
      flexBasis: 'calc(100% - 270px)',
      maxWidth: 'calc(100% - 270px)'
    },
    [theme.breakpoints.down('sm')]: {
      '& > div:first-child': {
        flexBasis: 0
      },
      '& > div:last-child': {
        flexBasis: '100%',
        maxWidth: '100%'
      }
    }
  }
});
export { styles };
