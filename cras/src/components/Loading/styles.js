import { makeStyles } from '@material-ui/core/styles';

const styles = makeStyles((theme) => ({
  root: {
    display: 'flex',
    '& > * + *': {
      marginLeft: theme.spacing(2)
    },
    marginTop: ({ top }) => top,
    marginLeft: ({ left }) => left
  }
}));

export { styles };
