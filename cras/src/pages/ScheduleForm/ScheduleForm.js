import React, { useState, useEffect } from 'react';
import Title from '../../components/Title';
import Grid from '@material-ui/core/Grid';
import { makeStyles } from '@material-ui/core/styles';
import { connect } from 'react-redux';

import StageField from '../../components/StageField';
import SelectField from '../../components/SelectField';
import Button from '../../components/Button';
import Loading from '../../components/Loading';

import api from '../../services/api';
import { years } from '../../data';
import { styles } from './styles';
import { ValidationSchedule } from '../../utils';

const useStyles = makeStyles(styles);

const ScheduleForm = (props) => {
  const classes = useStyles();
  const scheduleId = props.match.params.id;
  const [errors, setErrors] = useState({});
  const [desableField, setDesableField] = useState(false);
  const [isExistSchedule, setIsExistSchedule] = useState(false);
  const [loading, setLoading] = useState(false);
  const [loadData, setLoadData] = useState(false);

  const [state, setState] = useState({
    dateStartHealth1: '',
    dateEndHealth1: '',
    dateStartHealth2: '',
    dateEndHealth2: '',
    dateStartEducation1: '',
    dateEndEducation1: '',
    dateStartEducation2: '',
    dateEndEducation2: '',
    dateStartEducation3: '',
    dateEndEducation3: '',
    year: ''
  });

  useEffect(() => {
    if (props.match.params.id && !loadData) {
      try {
        api.get(`/schedule/${props.match.params.id}`).then(function (response) {
          if (response.data) {
            setState({
              dateStartHealth1: response.data.data.dateStartHealth1,
              dateEndHealth1: response.data.data.dateEndHealth1,
              dateStartHealth2: response.data.data.dateStartHealth2,
              dateEndHealth2: response.data.data.dateEndHealth2,
              dateStartEducation1: response.data.data.dateStartEducation1,
              dateEndEducation1: response.data.data.dateEndEducation1,
              dateStartEducation2: response.data.data.dateStartEducation2,
              dateEndEducation2: response.data.data.dateEndEducation2,
              dateStartEducation3: response.data.data.dateStartEducation3,
              dateEndEducation3: response.data.data.dateEndEducation3,
              year: response.data.data.year
            });
            setLoadData(true);
            setDesableField(true);
          }
        });
      } catch (error) {
        displayExeption;
      }
    }
  }, [scheduleId]);

  useEffect(() => {
    if (state.year !== '' && !props.match.params.id) {
      try {
        api.get(`/schedule/registred/year/${state.year}`).then(function (response) {
          setIsExistSchedule(response.data.exist);
        });
      } catch (error) {
        displayExeption;
      }
    }
  }, [state.year]);

  const handleChange = (event) => {
    setState({ ...state, [event.target.name]: event.target.value });
  };

  const save = () => {
    try {
      api.post(`/schedule`, state).then(function (response) {
        if (response) {
          setLoading(false);
          props.dispatch({
            type: 'ALERT',
            payload: {
              type: response.data.status === '0' ? 'danger' : 'success',
              message: response.data.message,
              autoHideDuration: 2000
            }
          });

          if (response.data.status === '1') {
            setState({
              dateStartHealth1: '',
              dateEndHealth1: '',
              dateStartHealth2: '',
              dateEndHealth2: '',
              dateStartEducation1: '',
              dateEndEducation1: '',
              dateStartEducation2: '',
              dateEndEducation2: '',
              dateStartEducation3: '',
              dateEndEducation3: '',
              year: ''
            });
          }
        }
      });
    } catch (error) {
      displayExeption;
    }
  };

  const update = () => {
    try {
      api.put(`/schedule/${props.match.params.id}`, state).then(function (response) {
        if (response) {
          setLoading(false);
          props.dispatch({
            type: 'ALERT',
            payload: {
              type: response.data.status === '0' ? 'danger' : 'success',
              message: response.data.message,
              autoHideDuration: 2000
            }
          });
        }
      });
    } catch (error) {
      displayExeption;
    }
  };

  const displayExeption = () => {
    props.dispatch({
      type: 'ALERT',
      payload: {
        type: 'danger',
        message: 'Algo inesperado aconteceu. Por favor, tente mais tarde.',
        autoHideDuration: 2000
      }
    });
  };

  const handleSubmit = () => {
    setErrors({});
    const errors = ValidationSchedule(state);

    if (!scheduleId && state.year !== '' && isExistSchedule) {
      errors['year'] = 'Calendário já cadastrado para esse ano';
    }

    if (Object.keys(errors).length === 0) {
      setLoading(true);
      if (scheduleId) {
        update();
      } else {
        save();
      }
    } else {
      setErrors(errors);
    }
  };

  return (
    <>
      <Grid container direction="row">
        <Title title="Calendário" />
      </Grid>
      <Grid container spacing={10} direction="row">
        <Grid item md={4} sm={6} xs={12}>
          <SelectField
            widthSelect="big"
            withLabel="Ano"
            displayText="Selecione"
            value={state.year}
            name="year"
            optionsValueLabel={years}
            handleChange={handleChange}
            errors={errors}
            disabledField={desableField}
          />
        </Grid>
      </Grid>
      <Grid container direction="row">
        <Grid item md={4} sm={6} xs={12}>
          <StageField
            handleChange={handleChange}
            icon="health"
            type="Saúde"
            numberStage={1}
            stage="Etapa 1"
            errors={errors}
            valueStart={state.dateStartHealth1}
            valueEnd={state.dateEndHealth1}
          />
        </Grid>
        <Grid item md={4} sm={6} xs={12}>
          <StageField
            handleChange={handleChange}
            icon="health"
            type="Saúde"
            numberStage={2}
            stage="Etapa 2"
            errors={errors}
            valueStart={state.dateStartHealth2}
            valueEnd={state.dateEndHealth2}
          />
        </Grid>
      </Grid>
      <Grid container direction="row">
        <Grid item md={4} sm={6} xs={12}>
          <StageField
            handleChange={handleChange}
            icon="paper"
            type="Educação"
            numberStage={1}
            stage="Etapa 1"
            errors={errors}
            valueStart={state.dateStartEducation1}
            valueEnd={state.dateEndEducation1}
          />
        </Grid>
        <Grid item md={4} sm={6} xs={12}>
          <StageField
            handleChange={handleChange}
            icon="paper"
            type="Educação"
            numberStage={2}
            stage="Etapa 2"
            errors={errors}
            valueStart={state.dateStartEducation2}
            valueEnd={state.dateEndEducation2}
          />
        </Grid>
        <Grid item md={4} sm={6} xs={12}>
          <StageField
            handleChange={handleChange}
            icon="paper"
            type="Educação"
            numberStage={3}
            stage="Etapa 3"
            errors={errors}
            valueStart={state.dateStartEducation3}
            valueEnd={state.dateEndEducation3}
          />
        </Grid>
        <Grid
          className={classes.mt80}
          container
          justify="flex-end"
          alignItems="center"
          direction="row"
        >
          <Grid item md={2} sm={3} xs={12}>
            {!loading ? (
              <Button
                size="medium"
                className={`${classes.floatRight} ${classes.widthButton}`}
                onClick={handleSubmit}
                type="submit"
                title={scheduleId ? 'Editar' : 'Salvar'}
              >
                {scheduleId ? 'Editar' : 'Salvar'}
              </Button>
            ) : (
              <Loading top="0" left="25%" />
            )}
          </Grid>
        </Grid>
      </Grid>
    </>
  );
};

const mapStateToProps = () => {
  return {};
};
export default connect(mapStateToProps)(ScheduleForm);
