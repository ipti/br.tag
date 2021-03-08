import { colors, typography } from '../../styles';

const styles = {
  contentStart: {
    color: colors.gray,
    fontFamily: typography.types.light,
    textAlign: 'center',
    fontSize: typography.font.midium,
    '& img': {
      marginTop: 40
    }
  },
  title: {
    color: colors.grayDark,
    fontSize: typography.font.large,
    fontFamily: typography.types.semiBold
  },
  link: {
    textDecoration: 'none',
    background: colors.blue,
    color: colors.white,
    fontSize: typography.font.medium,
    padding: '10px 40px',
    borderRadius: '5px',
    marginTop: 30,
    marginBottom: 30,
    display: 'inline-flex'
  }
};

export { styles };
