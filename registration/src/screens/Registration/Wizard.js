import React, { Component } from "react";

// Material UI
import Grid from "@material-ui/core/Grid";

// Components
import Start from "./Start";
import StepOne from "./StepOne";
import StepTwo from "./StepTwo";
import StepThree from "./StepThree";
import StepFour from "./StepFour";
import StepFive from "./StepFive";
import StepSix from "./StepSix";
import Finish from "./Finish";
import Classroom from "./ClassRoom";

class Wizard extends Component {
  constructor(props) {
    super(props);
    this.state = {
      step: props.step
    };
  }

  nextStep = step => {
    this.props.next(step);
  };

  componentMapping = {
    "0": Start,
    "1": Classroom,
    "2": StepOne,
    "3": StepTwo,
    "4": StepThree,
    "5": StepFour,
    "6": StepFive,
    "7": Finish
  };

  render() {
    const StepComponent = this.componentMapping[this.props.step];
    
    return (
      <Grid item xs={12}>
        <StepComponent {...this.props} nextStep={this.nextStep} />
      </Grid>
    );
  }
}

export default Wizard;
