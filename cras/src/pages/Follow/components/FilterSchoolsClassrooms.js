import React from 'react';
import { makeStyles } from '@material-ui/core/styles';
import Drawer from '@material-ui/core/Drawer';
import { ThemeProvider } from '@material-ui/core/styles';

import SelectFieldSearchable from '../../../components/SelectFieldSearchable';
import Button from '../../../components/Button';
import filter from '../../../assets/images/filter.svg';
import { styles, theme } from '../styles';
const useStyles = makeStyles(styles);

const FilterSchoolsClassrooms = (props) => {
  const classes = useStyles();
  const [state, setState] = React.useState({
    right: false,
    school: {},
    classroom: {}
  });

  const toggleDrawer = () => {
    setState({ ...state, right: !state.right });
  };

  const handleChangeSchool = (newValue) => {
    setState({ ...state, school: newValue });
    props.handleGeneric('school', newValue);
  };

  const handleChangeClassroom = (newValue) => {
    setState({ ...state, classroom: newValue });
    props.handleGeneric('classroom', newValue);
  };

  return (
    <>
      <button className={classes.buttonFilter} onClick={toggleDrawer}>
        <img src={filter} alt="Abrir Filtro" />
      </button>
      <ThemeProvider theme={theme}>
        <Drawer
          className={classes.boxDrawer}
          anchor="right"
          open={state['right']}
          onClose={toggleDrawer}
        >
          <div className={classes.boxImg}>
            <img src={filter} alt="Abrir Filtro" />
          </div>
          <SelectFieldSearchable
            options={props.schools}
            inputValue={props.school}
            name="school"
            handleChange={handleChangeSchool}
            label="Escola"
          />
          <SelectFieldSearchable
            options={props.classrooms}
            inputValue={props.classroom}
            name="classrooms"
            handleChange={handleChangeClassroom}
            label="Turma"
            margin={classes.mt60}
          />

          <div className={classes.boxButtons}>
            <Button
              size="small"
              className={`${classes.floatLeft} ${classes.widthButton}`}
              onClick={props.handleSubmit}
              type="submit"
              title="Filtrar"
            >
              Filtrar
            </Button>
            <Button
              size="small"
              className={`${classes.floatLeft} ${classes.ml20}`}
              onClick={toggleDrawer}
              variant="outlined"
              color="secondary"
              type="submit"
              title="Cancelar"
            >
              Cancelar
            </Button>
          </div>
        </Drawer>
      </ThemeProvider>
    </>
  );
};

export default FilterSchoolsClassrooms;
