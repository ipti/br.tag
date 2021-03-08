import { colors, typography } from '../../styles';
import avatar from '../../assets/images/avatar.svg';

export const styles = () => ({
  container: {
    display: 'flex',
    marginBottom: 45
  },
  avatar: {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    flex: 0.25,
    paddingRight: 5
  },
  content: {
    display: 'flex',
    flex: 0.75,
    flexDirection: 'column',
    justifyContent: 'center'
  },
  button: {
    cursor: 'pointer',
    background: 'transparent',
    outline: 'none',
    border: 'unset',
    display: 'inline-flex',
    fontSize: typography.font.small - 1,
    fontWeight: 500,
    color: colors.gray,
    padding: 'unset',

    '&:hover': {
      fontWeight: 600
    }
  },
  avatar2: {
    background: `${colors.grayClear2} url(${avatar})`,
    width: 46,
    height: 46,
    display: 'block',
    borderRadius: '100px',
    marginRight: 15
  },
  icon: {
    fontSize: typography.font.small,
    fontWeight: 600,
    color: colors.grayDark,
    marginRight: 5
  }
});
