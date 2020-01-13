import React from "react";
import Provider from "react-redux/es/components/Provider";
import store from "./store";
import Routes from "./routes";
import "./assets/css/styles.css";

function App() {
  return (
    <Provider store={store}>
      <Routes />
    </Provider>
  );
}

export default App;
