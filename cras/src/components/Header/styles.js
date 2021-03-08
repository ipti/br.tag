import { makeStyles } from '@material-ui/core/styles';
import { colors, typography } from '../../styles';

const styles = makeStyles((theme) => ({
  root: {
    flexGrow: 1,
    boxShadow:
      '0px 0px 0px -1px rgba(0,0,0,0.2), 0px -1px 5px 0px rgba(0,0,0,0.14), 0px 1px 6px 0px rgba(0,0,0,0.12)',
    backgroundColor: colors.white
  },
  tooBar: {
    minHeight: 'unset'
  },
  menuButton: {
    marginRight: 0,
    color: colors.blue
  },
  title: {
    flexGrow: 1,
    color: colors.blue,
    fontFamily: typography.types.extraLight,
    fontWeight: 600
  },
  [theme.breakpoints.up('sm')]: {
    title: {
      fontSize: '1.5rem'
    }
  }
}));

export { styles };
