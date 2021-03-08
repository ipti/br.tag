import React, { useEffect, useState } from 'react';
import { makeStyles } from '@material-ui/core/styles';
import MenuItem from '@material-ui/core/MenuItem';
import FormControl from '@material-ui/core/FormControl';
import Select from '@material-ui/core/Select';
import InputLabel from '@material-ui/core/InputLabel';

import { styles } from './styles';

const useStyles = makeStyles(styles);

const SelectField = (props) => {
  const classes = useStyles();
  const [options, setOptions] = useState([]);
  const [errors, setErrors] = useState({});

  useEffect(() => {
    if (props.optionsValueLabel) {
      setOptions(props.optionsValueLabel);
    }
  }, [props]);

  useEffect(() => {
    if (props.errors !== undefined) {
      setErrors(props.errors);
    }
  }, [props.errors]);

  return (
    <div className={classes.selectRoot}>
      {props.withLabel && (
        <InputLabel className={`${classes.label} ${props.margintop ? props.margintop : ''}`} shrink>
          {props.withLabel}
        </InputLabel>
      )}
      <FormControl
        variant="outlined"
        disabled={props.disabledField}
        className={`${classes.formControl} ${
          props.widthSelect === 'big' ? classes.withSelectBig : classes.withSelectSmall
        }`}
      >
        <Select
          name={props.name}
          value={props.value}
          displayEmpty={true}
          onChange={props.handleChange}
        >
          <MenuItem key={props.name} value="">
            {props.displayText}
          </MenuItem>
          {options.map((option) => (
            <MenuItem key={option.value} value={option.value}>
              {option.label}
            </MenuItem>
          ))}
        </Select>
        <div className={classes.textError}>
          {errors[props.name] !== undefined ? errors[props.name] : ''}
        </div>
      </FormControl>
    </div>
  );
};

export default SelectField;
