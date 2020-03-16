import React, { Suspense, lazy } from "react";
import { HashRouter, Route, Switch, Redirect } from "react-router-dom";
import Login from "./containers/Login";
import RegistrationHome from "./containers/Registration/Home";
import { isAuthenticated } from "./services/auth";
import MainLayout from "./components/Layouts/MainLayout";
import NotFoundPage from "./components/Layouts/NotFoundPage";
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
        <MainLayout>
          <Suspense fallback={<CircularLoading />}>
            <Component {...props} />
          </Suspense>
        </MainLayout>
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
      <Route path="/login" exact component={Login} />
      <Route path="/matricula" exact component={RegistrationHome} />
      <PrivateRoute exact path="/" component={Schedule} />
      <PrivateRoute exact path="/cronograma" component={Schedule} />
      <PrivateRoute
        exact
        path="/cronograma/adicionar"
        component={ScheduleForm}
      />
      <PrivateRoute
        exact
        path="/cronograma/editar/:id"
        component={ScheduleForm}
      />
      <PrivateRoute exact path="/escolas" component={School} />
      <PrivateRoute exact path="/escolas/:id" component={SchoolClassrooms} />
      <PrivateRoute exact path="/turmas" component={Classroom} />
      <PrivateRoute exact path="/turmas/:id" component={ClassroomForm} />
      <PrivateRoute
        exact
        path="/turmas/:id/matricula/:idRegistration"
        component={RegistrationClassroom}
      />
      <Route path="/*" component={NotFoundPage} />
    </Switch>
  </HashRouter>
);

export default Routes;
