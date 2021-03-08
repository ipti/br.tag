import { createMuiTheme } from '@material-ui/core/styles';
import { colors } from '../../styles';

const styles = {
  mb100: {
    marginBottom: 100
  },
  mt80: {
    marginTop: 80
  },
  mt34: {
    marginTop: 34
  },
  mt60: {
    marginTop: 60
  },
  mtn12: {
    marginTop: -12
  },
  ml20: {
    marginLeft: 20
  },
  mt15: {
    marginTop: 15
  },
  floatLeft: {
    float: 'left'
  },
  floatRight: {
    float: 'right'
  },
  widthButton: {
    width: 100
  },
  buttonFilter: {
    border: `solid 1px ${colors.blue}`,
    backgroundColor: colors.white,
    marginLeft: 15,
    borderRadius: 8,
    width: 35,
    outline: 'none',
    padding: '7px 10px',
    cursor: 'pointer',
    textAlign: 'center',
    '& img': {
      width: 16
    }
  },
  list: {
    width: 250
  },
  fullList: {
    width: 'auto'
  },
  boxDrawer: {
    position: 'relative'
  },
  boxButtons: {
    position: 'absolute',
    bottom: 80,
    right: 26
  },
  boxImg: {
    backgroundColor: colors.grayClear2,
    padding: 10,
    borderRadius: 50,
    width: 30,
    textAlign: 'center',
    marginTop: 30,
    marginBottom: 40
  },
  '@media(max-width: 1440px)': {
    buttonFilter: {
      padding: '5px 10px'
    }
  },
  '@media(min-width: 960px and max-width: 1024px)': {},
  '@media(max-width: 768px)': {
    mtSm20: {
      marginTop: 20
    }
  }
};

const theme = createMuiTheme({
  overrides: {
    MuiPaper: {
      root: {
        width: 300,
        paddingLeft: '20px',
        paddingRight: '20px'
      }
    }
  }
});

export { styles, theme };
