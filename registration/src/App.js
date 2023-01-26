import React from "react";
import Provider from "react-redux/es/components/Provider";
import store from "./store";

// Routes
import Routes from "./routes";

// Styles
import styleBase from "./styles";
import "./assets/css/styles.css";

import {
  createTheme,
  ThemeProvider
} from "@material-ui/core/styles";

const theme = createTheme({
  palette: {
    primary: {
      main: styleBase.colors.purple
    }
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
