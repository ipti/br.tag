/**
 * Food_action
 */

 import React from 'react';
 import { Redirect, Route, Switch, Link } from 'react-router-dom';
 
// async components
import {
    AsyncFoodActionFromComponent
} from 'Components/AsyncComponent/AsyncComponent';

const Food_action = ({match}) => (
    <div className="food_action-wrapper" style={ {overflowY: 'auto', height: "100%"} }>
        <Switch>
            <Redirect exact from = { `${match.url}/` } to = {` ${match.url}/form `} />
            <Route path = { `${match.url}/form` } component = { AsyncFoodActionFromComponent } />
        </Switch>
    </div>
);

export default Food_action;