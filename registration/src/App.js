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
import { QueryClientProvider } from "react-query";
import queryClient from "./services/query";

const theme = createTheme({
  palette: {
    primary: {
      main: styleBase.colors.purple
    }
  }
});

function App() {
  return (
    <QueryClientProvider client={queryClient}>
      <ThemeProvider theme={theme}>
        <Routes />
      </ThemeProvider>
    </QueryClientProvider>
  );
}

export default App;
