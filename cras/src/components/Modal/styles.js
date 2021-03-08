import { colors } from '../../styles';

const styles = (theme) => ({
  title: {
    marginRight: theme.spacing(8)
  },
  closeButton: {
    position: 'absolute',
    right: theme.spacing(1),
    top: theme.spacing(1),
    color: colors.gray
  }
});

export default styles;
