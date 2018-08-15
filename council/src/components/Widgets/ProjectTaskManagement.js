/**
 * Project Task Management
 */
import React, { Component } from 'react'
import IconButton from '@material-ui/core/IconButton';
import Button from '@material-ui/core/Button';
import List from '@material-ui/core/List';
import ListItem from '@material-ui/core/ListItem';
import { Badge, FormGroup,Input, Collapse } from 'reactstrap';
import Avatar from '@material-ui/core/Avatar';
import { Scrollbars } from 'react-custom-scrollbars';
import TextField from '@material-ui/core/TextField';
import Tooltip from '@material-ui/core/Tooltip';

//Data File
import { projectTaskData } from 'Routes/widgets/data';

// rct section loader
import RctSectionLoader from 'Components/RctSectionLoader/RctSectionLoader';

// card component
import { RctCardFooter } from 'Components/RctCard';

//Helper
import { getTheDate, convertDateToTimeStamp } from 'Helpers/helpers';

// intl messages
import IntlMessages from 'Util/IntlMessages';

export default class ProjectTaskManagement extends Component {
   constructor(props) {
      super(props);
      this.state = {
         sectionReload: false,
         projectTaskData,
         projectData: projectTaskData,
         collapse: false,
         selectedProject: null,
         newTask: {
            taskTitle: "",
            taskDate: null,
            status: "",
            color: "",
            completed: false,
            team: null,
         }
      };
   }

   //Close New Task
   closeForm(key) {
      this.setState({
         selectedProject: key,
         collapse: false,
      })
   }

   //Open New Task
   openForm(key) {
      this.setState({
         selectedProject: key,
         collapse: true,
      })
   }

   // on change schedule
   onChangeDueDate(e) {
      let timestamp = convertDateToTimeStamp(e.target.value);
      this.setState({ newTask: { ...this.state.newTask, taskDate: timestamp } })
   }

   //Add New Task
   addNewTask(key) {
      const { taskTitle, taskDate } = this.state.newTask;
      if (taskTitle !== '' && taskDate) {
         let newTask = {
            title: taskTitle,
            date: taskDate,
            status: "Planning",
            color: "primary",
            team: [
               {
                  id: 1,
                  name: "Lucile",
                  avatar: "http://reactify.theironnetwork.org/data/images/user-1.jpg",
               }
            ]
         };
         this.setState({ sectionReload: true, collapse: false });
         let self = this;
         setTimeout(() => {
            let projects = this.state.projectData;
            projects[key].push(newTask);
            self.setState({
               projectData: projects,
               collapse: false,
               sectionReload: false,
               newTask: {
                  taskTitle: "",
                  taskDate: null
               }
            });
         }, 1500);
      }
   }

   render() {
      const { projectData, collapse, selectedProject } = this.state;
      return (
         <div className="task-management-wrapper">
            {this.state.sectionReload &&
               <RctSectionLoader />
            }
            <Scrollbars className="rct-scroll" autoHeight autoHeightMin={100} autoHeightMax={600} autoHide>
               <ul className="mb-0">
                  {Object.keys(projectData).map((project, key) => (
                     <li key={key}>
                        <div className="project-heading d-flex justify-content-between align-items-center border-bottom">
                           <h4 className="fw-semi-bold mb-0">{project}</h4>
                           <IconButton onClick={() => this.openForm(project)}>
                              <i className="material-icons">add_circle</i>
                           </IconButton>
                        </div>
                        <List className="p-0">
                           {projectData && projectData[project].map((data, subKey) => (
                              <ListItem
                                 button
                                 className="justify-content-between align-items-center border-bottom post-item"
                                 key={subKey}
                              >
                                 <div className="task-title pr-3">
                                    <h4 className="mb-5">{data.title}</h4>
                                    <span className="fs-12 text-base">{getTheDate(data.date, 'DD MMM YYYY')}</span>
                                 </div>
                                 <div className="w-40 d-flex justify-content-between">
                                    <span><Badge color={data.color}>{data.status}</Badge></span>
                                    <div className="team-avatar">
                                       <ul className="m-0 list-inline">
                                          {data.team.map((member, nestedSubkey) => (
                                             <li className="list-inline-item" key={nestedSubkey}>
                                                <Tooltip id="tooltip-top" title={member.name} placement="top">
                                                   <Avatar src={member.avatar} alt="avatar" className="rounded-circle" />
                                                </Tooltip>
                                             </li>
                                          ))}
                                       </ul>
                                    </div>
                                 </div>
                              </ListItem>
                           ))}
                        </List>
                        {(selectedProject === project) &&
                           <Collapse isOpen={collapse}>
                              <div className="d-flex p-4 form-wrap border-bottom justify-content-between">
                                 <div className="mr-10">
                                    <TextField
                                       id="taskName"
                                       onChange={(e) => this.setState({ newTask: { ...this.state.newTask, taskTitle: e.target.value } })}
                                       value={this.state.newTask.taskTitle}
                                       label="Task Name"
                                       type="text"
                                       fullWidth
                                       className="mx-5 mb-30"
                                    />
                                    <TextField
                                       id="date"
                                       label="Due Date"
                                       type="date"
                                       InputLabelProps={{
                                          shrink: true
                                       }}
                                       fullWidth
                                       onChange={(e) => this.onChangeDueDate(e)}
                                       className="mx-5"
                                    />
                                 </div>
                                 <div className="align-self-end d-flex flex-column text-center">
                                    <Button variant="raised" color="primary" className="mb-30 text-white  btn-xs" onClick={() => this.addNewTask(project)}>
                                       <span><IntlMessages id="button.add" /></span>
                                    </Button>
                                    <Button variant="raised" onClick={() => this.closeForm(project)} className="btn-xs btn-danger text-white">
                                       <span><IntlMessages id="button.cancel" /></span>
                                    </Button>
                                 </div>
                              </div>
                           </Collapse>
                        }
                     </li>
                  ))}
               </ul>
            </Scrollbars>
            <RctCardFooter customClasses=" d-flex justify-content-between align-items-center">
               <div className="d-flex w-40 align-items-center justify-content-between">
                  <span className="fs-12 w-50 text-base"><IntlMessages id="widgets.selectProject" /></span>
                  <div className="app-selectbox-sm">
                     <FormGroup className="mb-0">
                        <Input
                           type="select"
                           className="fs-12"
                           name="select"
                           id="projectSelect"
                        >
                           <option disabled>Select Project</option>
                           <option value="all">all</option>
                           <option value="BookingKoala">BookingKoala</option>
                           <option value="Reactify">Reactify</option>
                           <option value="Adminify">Adminify</option>
                        </Input>
                     </FormGroup>
                  </div>
               </div>
               <span className="fs-12 text-base">
                  <i className="mr-15 zmdi zmdi-refresh"></i>
                  <IntlMessages id="widgets.updated10Minago" />
               </span>
            </RctCardFooter>
         </div>
      )
   }
}
