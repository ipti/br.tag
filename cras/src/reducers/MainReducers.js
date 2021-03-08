import { combineReducers } from 'redux';
import follow from './FollowReducers';
import display from './MainDisplayReducers';

export default combineReducers({
  follow,
  display
});
