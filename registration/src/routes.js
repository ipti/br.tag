import React, { Suspense, lazy } from "react";
import { HashRouter, Route, Switch, Redirect } from "react-router-dom";
import Login from "./containers/Login";
import RegistrationHome from "./containers/Registration/Home";
import { isAuthenticated } from "./services/auth";
import MainLayout from "./components/Layouts/MainLayout";
import CircularLoading from "./components/Loading/CircularLoading";

const Home = lazy(() => import("./containers/Home"));
const Schedule = lazy(() => import("./containers/Schedule/Schedule"));
const ScheduleForm = lazy(() => import("./containers/Schedule/ScheduleForm"));

const School = lazy(() => import("./containers/School/School"));
const SchoolClassrooms = lazy(() =>
  import("./containers/School/SchoolClassrooms")
);

const Classroom = lazy(() => import("./containers/Classroom/Classroom"));
const ClassroomForm = lazy(() =>
  import("./containers/Classroom/ClassroomForm")
);
const RegistrationClassroom = lazy(() =>
  import("./containers/Classroom/Registration")
);

const PrivateRoute = ({ component: Component, ...rest }) => (
  <Route
    {...rest}
    render={props =>
      isAuthenticated() ? (
        <Component {...props} />
      ) : (
        <Redirect
          to={{ pathname: "/login", state: { from: props.location } }}
        />
      )
    }
  />
);

const Routes = () => (
  <HashRouter>
    <Switch>
      <Route path="/login" exact={true} component={Login} />
      <Route path="/matricula" exact={true} component={RegistrationHome} />
      <MainLayout>
        <Suspense fallback={<CircularLoading />}>
          <PrivateRoute exact={true} path="/inicio" component={Home} />
          <PrivateRoute exact={true} path="/cronograma" component={Schedule} />
          <PrivateRoute
            exact={true}
            path="/cronograma/adicionar"
            component={ScheduleForm}
          />
          <PrivateRoute
            exact={true}
            path="/cronograma/editar/:id"
            component={ScheduleForm}
          />
          <PrivateRoute exact={true} path="/escolas" component={School} />
          <PrivateRoute
            exact={true}
            path="/escolas/:id"
            component={SchoolClassrooms}
          />
          <PrivateRoute exact={true} path="/turmas" component={Classroom} />
          <PrivateRoute
            exact={true}
            path="/turmas/:id"
            component={ClassroomForm}
          />
          <PrivateRoute
            exact={true}
            path="/turmas/:id/matricula/:idRegistration"
            component={RegistrationClassroom}
          />
        </Suspense>
      </MainLayout>
    </Switch>
  </HashRouter>
);

export default Routes;
