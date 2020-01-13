import React from "react";
import { BrowserRouter, Route, Switch, Redirect } from "react-router-dom";
import Login from "./containers/Login";
import Home from "./containers/Home";
import { Schedule, ScheduleCreate } from "./containers/Schedule";
import { School, SchoolClassrooms } from "./containers/School";
import Classroom from "./containers/Classroom";
import { isAuthenticated } from "./services/auth";
import MainLayout from "./components/Layouts/MainLayout";

const PrivateRoute = ({ component: Component, ...rest }) => (
  <Route
    {...rest}
    render={props =>
      isAuthenticated() ? (
        <Component {...props} />
      ) : (
        <Redirect to={{ pathname: "/", state: { from: props.location } }} />
      )
    }
  />
);

const Routes = () => (
  <BrowserRouter>
    <Switch>
      <Route path="/" exact={true} component={Login} />
      <MainLayout>
        <PrivateRoute exact={true} path="/inicio" component={Home} />
        <PrivateRoute exact={true} path="/cronograma" component={Schedule} />
        <PrivateRoute
          exact={true}
          path="/cronograma/adicionar"
          component={ScheduleCreate}
        />
        <PrivateRoute exact={true} path="/escolas" component={School} />
        <PrivateRoute
          exact={true}
          path="/escolas/:id"
          component={SchoolClassrooms}
        />
        <PrivateRoute exact={true} path="/turmas" component={Classroom} />
      </MainLayout>
    </Switch>
  </BrowserRouter>
);

export default Routes;
