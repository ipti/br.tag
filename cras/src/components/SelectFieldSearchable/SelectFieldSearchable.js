import React, { useState, useEffect } from 'react';
import { makeStyles } from '@material-ui/core/styles';

import Select from 'react-select';

import { styles } from './styles';

const useStyles = makeStyles(styles);

const SelectFieldSearchable = (props) => {
  const classes = useStyles();
  const [state] = useState({
    isClearable: true,
    isDisabled: false,
    isLoading: false,
    isRtl: false,
    isSearchable: true
  });

  const [options, setOptions] = useState([]);

  useEffect(() => {
    if (props.options != undefined) {
      setOptions(props.options);
    }
  }, [props.options]);
  return (
    <>
      <label className={`${classes.labelFilter} ${props.margin}`}>{props.label}</label>
      <Select
        className={classes.selectFilter}
        classNamePrefix="select"
        isDisabled={state.isDisabled}
        isLoading={state.isLoading}
        isClearable={state.isClearable}
        isRtl={state.isRtl}
        isSearchable={state.isSearchable}
        name={props.name}
        options={options}
        value={props.inputValue}
        onChange={props.handleChange}
        placeholder={'Selecione'}
        noOptionsMessage={() => 'Sem opção'}
        loadingMessage={() => 'Carregando...'}
      />
    </>
  );
};

export default SelectFieldSearchable;
