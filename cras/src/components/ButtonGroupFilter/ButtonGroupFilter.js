import React, { useState, useEffect } from 'react';
import ToggleButton from '@material-ui/lab/ToggleButton';
import ToggleButtonGroup from '@material-ui/lab/ToggleButtonGroup';
import { makeStyles } from '@material-ui/core/styles';

import { styles } from './styles';
const useStyles = makeStyles(styles);

const ButtonGroupFilter = (props) => {
  const [alignment, setAlignment] = useState('');

  const classes = useStyles();

  useEffect(() => {
    if (props.alignment !== undefined) {
      setAlignment(props.alignment);
    }
  }, [props.alignment]);

  const handleAlignment = (event, newAlignment) => {
    let value = '';
    switch (props.name) {
      case 'type':
        value = '';
        if (newAlignment !== null) {
          value = newAlignment === 'left' ? 'E' : 'H';
        }
        break;
      case 'situation':
        console.log(newAlignment);
        value = '';
        if (newAlignment !== null) {
          value = newAlignment === 'left' ? false : true;
        }
        break;
    }
    props.handleChange(props.name, value, newAlignment);
  };

  return (
    <>
      <ToggleButtonGroup
        value={alignment}
        exclusive
        onChange={handleAlignment}
        aria-label="text alignment"
        className={classes.buttonGroup}
      >
        <ToggleButton value="left" aria-label="left aligned">
          <img src={props.iconLeft} />
          {props.buttonOneText}
        </ToggleButton>
        <ToggleButton value="right" aria-label="centered">
          <img src={props.iconRight} />
          {props.buttonTwoText}
        </ToggleButton>
      </ToggleButtonGroup>
    </>
  );
};

export default ButtonGroupFilter;
