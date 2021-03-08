import loginBackground from '../../assets/images/login-background.jpg';
import { colors, typography } from '../../styles';

const styles = {
  content: {
    display: 'flex',
    flexDirection: 'column',
    justifyContent: 'center',
    minHeight: 'calc(100% - 50px)',
    padding: '25px 12%',
    background: `${colors.blue} url(${loginBackground})`,
    backgroundPosition: 'center',
    backgroundSize: 'cover'
  },
  title: {
    color: colors.white,
    fontFamily: typography.types.light,
    fontSize: typography.font.extraLarge,
    fontWeight: 100,

    '& > strong': {
      display: 'block',
      lineHeight: '40px',
      fontFamily: typography.types.regular,
      fontWeight: 900
    }
  },
  description: {
    color: colors.white,
    fontFamily: typography.types.light,
    fontSize: typography.font.small
  },
  button: {
    width: 170,
    marginTop: 25,
    textTransform: 'uppercase',
    color: `${colors.gray} !important`,
    fontSize: `${typography.font.small}px !important`
  },
  sidebar: {
    display: 'flex',
    flexDirection: 'column',
    justifyContent: 'space-evenly',
    minHeight: '100%'
  },
  header: {
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    fontFamily: typography.types.light,
    margin: '10px 0'
  },
  brandName: {
    textAlign: 'center',
    fontSize: typography.font.large - 5,
    color: colors.blue,
    fontFamily: typography.types.light
  },
  pageName: {
    display: 'inline-flex',
    borderLeft: `solid 1px ${colors.grayClear1}`,
    color: colors.gray,
    fontSize: typography.font.medium,
    lineHeight: '24px',
    marginLeft: 15,
    paddingLeft: 15,
    height: 25
  },
  logo: {
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',

    '& img': {
      height: 80
    }
  },
  form: {
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',

    '& form': {
      width: '80%'
    }
  },
  textButton: {
    color: `${colors.gray} !important`,
    fontSize: `${typography.font.small}px !important`,
    fontWeight: 'bold'
  },
  footer: {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center'
  }
};

export { styles };
