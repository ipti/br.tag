import React, { useState, useEffect } from 'react';
import Grid from '@material-ui/core/Grid';
import { makeStyles } from '@material-ui/core/styles';
import { connect } from 'react-redux';
import { format } from 'date-fns';

import Title from '../../components/Title';
import StudentCard from '../../components/StudentCard';
import { Paginator } from '../../components/Paginator';
import ButtonGroupFilter from '../../components/ButtonGroupFilter';
import Button from '../../components/Button';
import SelectField from '../../components/SelectField';

import FilterSchoolsClassrooms from './components/FilterSchoolsClassrooms';
import Loading from '../../components/Loading';

import health from '../../assets/images/health.svg';
import paper from '../../assets/images/paper.svg';
import close from '../../assets/images/close.svg';
import check from '../../assets/images/check.svg';

import { years, steps } from '../../data';
import api from '../../services/api';

import { styles } from './styles';
const useStyles = makeStyles(styles);

const Follow = (props) => {
  const yearNow = format(new Date(), 'yyyy');
  const [alignmentType, setAlignmentType] = useState('left');
  const [alignmentSituation, setAlignmentSituation] = useState('');
  const [schools, setSchools] = useState([]);
  const [students, setStudents] = useState([]);
  const [totalPages, setTotalPages] = useState(1);
  const [page, setPage] = useState(1);
  const [loading, setLoading] = useState(true);
  const [dataSteps, setDataSteps] = useState(steps);
  const [state, setState] = useState({
    stage: '1',
    year: `${yearNow}`,
    type: 'E',
    situation: '',
    school: '',
    classroom: ''
  });

  const [classrooms, setClassrooms] = useState([]);

  const classes = useStyles();

  useEffect(() => {
    props.dispatch({
      type: 'FETCH_STUDENTS'
    });
  }, []);

  useEffect(() => {
    if (state.type === 'E') {
      if (dataSteps.length < 3) {
        setDataSteps([...dataSteps, { value: '3', label: 'Etapa 3' }]);
        setState({ ...state, stage: '' });
      }
    }

    if (state.type === 'H') {
      if (dataSteps.length > 2) {
        let helthSteps = dataSteps;
        helthSteps.pop();
        setState({ ...state, stage: '' });
      }
    }
    handleSubmit();
  }, [state.type, state.situation]);

  useEffect(() => {
    if (props.students.students !== undefined) {
      setLoading(false);
      setStudents(props.students.students);
      setTotalPages(props.students.pagination.totalPages);
    }
  }, [props.students]);

  useEffect(() => {
    if (schools.length === 0) {
      try {
        api.get('/school').then(({ data }) => {
          const dataSchool = data.schools.map((item) => ({
            value: item.inepId,
            label: `${item.name}`
          }));

          setSchools(dataSchool);
        });
      } catch {
        displayExeption;
      }
    }
  }, [schools]);

  useEffect(() => {
    if (state.school?.value) {
      const year = state.year != '' ? state.year : years[0].value;

      try {
        api.get(`/school/${state.school.value}/${year}/classrooms`).then(({ data }) => {
          if (data.classrooms.length == 0) {
            props.dispatch({
              type: 'ALERT',
              payload: {
                type: 'info',
                message: 'Não existe turmas para o ano e escola selecionados',
                autoHideDuration: 2000
              }
            });

            setClassrooms([]);
          } else {
            const dataClassrooms = data.classrooms.map((item) => ({
              value: item.classroomId,
              label: `${item.name}`
            }));

            setClassrooms(dataClassrooms);
          }
        });
      } catch {
        displayExeption;
      }
    }

    if (!state.school?.value) {
      setState({ ...state, classroom: '' });
    }
  }, [state.school]);

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

  const handleChange = (event) => {
    setState({ ...state, [event.target.name]: event.target.value });
  };

  const handlePage = (page) => {
    let data = state;
    data.page = page;
    setLoading(true);
    props.dispatch({
      type: 'FETCH_SEARCH_STUDENTS',
      data
    });
    setPage(page);
  };

  const handleSubmit = () => {
    let data = state;
    data.page = page;
    setLoading(true);
    props.dispatch({
      type: 'FETCH_SEARCH_STUDENTS',
      data
    });
  };

  const handleGeneric = (name, item, align) => {
    setState({ ...state, [name]: item });

    if (name === 'type') {
      setAlignmentType(align);
    } else {
      setAlignmentSituation(align);
    }
  };

  const dataStudents = students.map((item) => <StudentCard student={item} key={item._id} />);
  return (
    <>
      <Grid container direction="row">
        <Title title="Acompanhamento" />
      </Grid>
      <Grid className={`${classes.mb100} ${classes.mtn12}`} container direction="row">
        <Grid item md={3} sm={6} xs={12}>
          <ButtonGroupFilter
            iconLeft={paper}
            iconRight={health}
            buttonOneText="Educação"
            buttonTwoText="Saúde"
            name="type"
            value={state.type}
            alignment={alignmentType}
            handleChange={handleGeneric}
          />
        </Grid>
        <Grid item md={3} sm={6} xs={12}>
          <SelectField
            displayText="Ano"
            value={state.year}
            name="year"
            handleChange={handleChange}
            optionsValueLabel={years}
          />
          <SelectField
            displayText="Etapa"
            value={state.stage}
            name="stage"
            handleChange={handleChange}
            optionsValueLabel={dataSteps}
          />
        </Grid>
        <Grid className={classes.mtSm20} item md={4} sm={6} xs={12}>
          <ButtonGroupFilter
            iconLeft={close}
            iconRight={check}
            name="situation"
            buttonOneText="Descumpriu"
            buttonTwoText="Cumpriu"
            alignment={alignmentSituation}
            handleChange={handleGeneric}
            value={state.situation}
          />
        </Grid>
        <Grid item className={classes.mtSm20} md={2} sm={6} xs={12}>
          <FilterSchoolsClassrooms
            handleSubmit={handleSubmit}
            handleGeneric={handleGeneric}
            className={classes.floatLeft}
            schools={schools}
            classrooms={classrooms}
            classroom={state.classroom}
            school={state.school}
          />
          <Button
            size="small"
            className={`${classes.floatLeft} ${classes.widthButton}`}
            onClick={handleSubmit}
            type="submit"
            title="Filtrar"
          >
            Filtrar
          </Button>
        </Grid>
      </Grid>

      {loading ? (
        <Grid container justify="center" alignItems="center" spacing={12} direction="row">
          <Grid item md={4} sm={4} xs={4}>
            <Loading top="0" left="25%" />
          </Grid>
        </Grid>
      ) : (
        <Grid container spacing={4} direction="row">
          {dataStudents}
        </Grid>
      )}
      <Grid className={classes.mt34} container spacing={10} direction="row">
        <Grid item md={12} sm={12} xs={12}>
          {totalPages > 1 && <Paginator count={totalPages} handlePage={handlePage} />}
        </Grid>
      </Grid>
    </>
  );
};

const mapStateToProps = (state) => {
  return {
    students: state.follow.students
  };
};

export default connect(mapStateToProps)(Follow);
