/**
 * App Reducers
 */
import { combineReducers } from 'redux';
import settings from './settings';
import chatAppReducer from './ChatAppReducer';
import emailAppReducer from './EmailAppReducer';
import sidebarReducer from './SidebarReducer';
import todoAppReducer from './TodoAppReducer';
import authUserReducer from './AuthUserReducer';
import feedbacksReducer from './FeedbacksReducer';
import ecommerceReducer from './EcommerceReducer';
import peopleReducer from './PeopleReducer';
import serviceReducer from './ServiceReducer';
import notificationReducer from './NotificationReducer';
import foodReducer from './FoodReducer';
import InstitutionReducer from './InstitutionReducer';
import HousingReducer from './HousingReducer';
import ReportReducer from "./ReportReducer";

const reducers = combineReducers({
  settings,
  chatAppReducer,
  emailApp: emailAppReducer,
  sidebar: sidebarReducer,
  todoApp: todoAppReducer,
  authUser: authUserReducer,
  feedback: feedbacksReducer,
  ecommerce: ecommerceReducer,
  people: peopleReducer,
  service: serviceReducer,
  notification: notificationReducer,
  food: foodReducer,
  institution: InstitutionReducer,
  housing: HousingReducer,
  report: ReportReducer,
});

export default reducers;
