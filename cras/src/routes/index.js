import React, { Suspense, lazy } from 'react';
import { HashRouter, Route, Switch, Redirect } from 'react-router-dom';

import Loading from '../components/Loading';
import Login from '../pages/Login';
import { NotFound } from '../pages/NotFound';
import Layout from '../components/Layout';
import Display from '../components/Display';
import { isAuthenticated } from '../services/auth';

const Home = lazy(() => import('../pages/Home'));
const Schedule = lazy(() => import('../pages/Schedule/Schedule'));
const ScheduleForm = lazy(() => import('../pages/ScheduleForm/ScheduleForm'));
const Follow = lazy(() => import('../pages/Follow/Follow'));

const PrivateRoute = ({ component: Component, ...rest }) => (
  <Route
    {...rest}
    render={(props) =>
      isAuthenticated() ? (
        <Layout>
          <Suspense fallback={<Loading />}>
            <Component {...props} />
          </Suspense>
        </Layout>
      ) : (
        <Redirect to={{ pathname: '/login', state: { from: props.location } }} />
      )
    }
  />
);

const Routes = () => {
  return (
    <HashRouter>
      <Switch>
        <Route path="/login" exact component={Login} />
        <PrivateRoute exact path="/" component={Home} />
        <PrivateRoute exact path="/condicionalidades/calendario" component={Schedule} />
        <PrivateRoute
          exact
          path="/condicionalidades/calendario/editar/:id"
          component={ScheduleForm}
        />
        <PrivateRoute
          exact
          path="/condicionalidades/calendario/cadastro"
          component={ScheduleForm}
        />
        <PrivateRoute exact path="/condicionalidades/acompanhar" component={Follow} />
        <Route path="/*" component={NotFound} />
      </Switch>
      <Display />
    </HashRouter>
  );
};

export default Routes;
