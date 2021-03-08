import { colors } from '../../styles';
import { makeStyles } from '@material-ui/core/styles';

const useStyles = makeStyles((theme) => ({
  root: {
    '& > *': {
      margin: theme.spacing(1),
      color: colors.white
    },
    float: 'right'
  },
  extendedIcon: {
    marginRight: theme.spacing(1)
  }
}));

export { useStyles };
