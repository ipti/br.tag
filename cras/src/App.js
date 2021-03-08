import React from 'react';
import Provider from 'react-redux/es/components/Provider';
import { createMuiTheme, ThemeProvider } from '@material-ui/core/styles';

import store from './store';
import { colors } from './styles';
import Routes from './routes';
import './assets/css/styles.css';

const theme = createMuiTheme({
  palette: {
    primary: {
      main: colors.blue
    }
  },
  typography: {
    fontFamily: 'Poppins Regular, Arial'
  }
});

function App() {
  return (
    <Provider store={store}>
      <ThemeProvider theme={theme}>
        <Routes />
      </ThemeProvider>
    </Provider>
  );
}

export default App;
