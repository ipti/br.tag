/**
 * App Light Theme
 */
import { createMuiTheme } from '@material-ui/core/styles';
import AppConfig from 'Constants/AppConfig';

const theme = createMuiTheme({
    palette: {
        primary: {
            main: AppConfig.themeColors.info
        },
        secondary: {
            main: AppConfig.themeColors.warning
        }
    }
});

export default theme;