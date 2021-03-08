import React, { useEffect, useState } from 'react';
import { makeStyles } from '@material-ui/core/styles';
import TextField from '@material-ui/core/TextField';
import InputLabel from '@material-ui/core/InputLabel';
import IconHealthPaper from '../IconHealthPaper';

import { styles } from './styles';

const useStyles = makeStyles(styles);

const StageField = (props) => {
  const classes = useStyles();
  const [errors, setErrors] = useState({});

  useEffect(() => {
    if (props.errors !== undefined) {
      setErrors(props.errors);
    }
  }, [props.errors]);

  let name = props.icon === 'health' ? 'Health' : 'Education';
  let nameStart = `dateStart${name}${props.numberStage}`;
  let nameEnd = `dateEnd${name}${props.numberStage}`;
  return (
    <div className={classes.boxCardFields}>
      <div>
        {props.icon === 'health' ? <IconHealthPaper /> : <IconHealthPaper iconName="paper" />}
      </div>
      <div className={classes.boxTypeStage}>
        <span>{props.type}</span>
        <span className={classes.spanStage}>{props.stage}</span>
      </div>
      <div className={classes.boxDateStart}>
        <InputLabel>Data Início</InputLabel>
        <TextField
          name={nameStart}
          type="date"
          variant="outlined"
          onChange={props.handleChange}
          className={classes.textField}
          value={props.valueStart}
        />
        <div className={classes.textError}>
          {errors[nameStart] !== undefined ? errors[nameStart] : ''}
        </div>
      </div>
      <div className={classes.boxDateEnd}>
        <InputLabel>Data Término</InputLabel>
        <TextField
          name={nameEnd}
          type="date"
          variant="outlined"
          onChange={props.handleChange}
          className={classes.textField}
          value={props.valueEnd}
        />
        <div className={classes.textError}>
          {errors[nameEnd] !== undefined ? errors[nameEnd] : ''}
        </div>
      </div>
    </div>
  );
};

export default StageField;
