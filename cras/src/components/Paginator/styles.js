import { createMuiTheme } from '@material-ui/core/styles';
import { colors } from '../../styles';

const theme = createMuiTheme({
  overrides: {
    MuiButtonBase: {
      root: {
        backgroundColor: colors.grayClear2
      }
    },
    primary: {
      backgroundColor: colors.blue,
      color: colors.white
    },
    MuiPagination: {
      ul: {
        '& li:first-child > button': {
          backgroundColor: colors.white
        },
        '& li:last-child > button': {
          backgroundColor: colors.white
        }
      }
    },
    MuiPaginationItem: {
      icon: {
        fontSize: '2.25rem'
      },
      textPrimary: {
        color: colors.gray,
        '&.Mui-selected': {
          backgroundColor: colors.blue,
          color: colors.white,
          '&:hover': {
            color: colors.white,
            backgroundColor: colors.blue + ' !important'
          }
        },
        '&:hover': {
          color: colors.white,
          backgroundColor: colors.blue + ' !important'
        }
      }
    }
  }
});

export { theme };
