import { combineReducers } from "redux";
import school from "./schoolReducers";
import schedule from "./scheduleReducers";
import classroom from "./classroomReducers";
import registration from "./registrationReducers";
import viaCep from "./cepReducers";

export default combineReducers({
  school,
  schedule,
  classroom,
  registration,
  viaCep
});
