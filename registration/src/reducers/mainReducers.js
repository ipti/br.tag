import { combineReducers } from "redux";
import school from "./schoolReducers";
import schedule from "./scheduleReducers";

export default combineReducers({
  school,
  schedule
});
