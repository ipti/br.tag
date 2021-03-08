import React, { useEffect, useState } from 'react';
import Grid from '@material-ui/core/Grid';
import { Link } from 'react-router-dom';
import { makeStyles } from '@material-ui/core/styles';

import { Paginator } from '../../components/Paginator';
import Title from '../../components/Title';
import ScheduleCard from '../../components/ScheduleCard';
import ButtonFloating from '../../components/ButtonFloating';

import api from '../../services/api';

import { styles } from './styles';
const useStyles = makeStyles(styles);

const Schedule = () => {
  const classes = useStyles();
  const [schedules, setSchedules] = useState([]);
  const [loadData, setLoadData] = useState(false);
  const [totalPages, setTotalPages] = useState(1);

  useEffect(() => {
    if (!loadData) {
      api.get('/schedule').then((response) => {
        if (response && !('error' in response.data.schedules)) {
          setSchedules(response.data.schedules);
          setTotalPages(response.data.pagination.totalPages);
          setLoadData(true);
        }
      });
    }
  }, [schedules]);

  const handlePage = (page) => {
    api.get(`/schedule?page=${page}`).then((response) => {
      if (response && !('error' in response.data.schedules)) {
        setSchedules(response.data.schedules);
        setLoadData(true);
      }
    });
  };

  const schedulesData = schedules.map((schedule) => (
    <Grid key={schedule._id} item xl={3} md={4} sm={12} xs={12}>
      <ScheduleCard schedule={schedule} />
    </Grid>
  ));

  return (
    <Grid container>
      <Grid item xs={12}>
        <Title title="Calendários" />
      </Grid>
      <Grid container spacing={4}>
        {schedulesData}
      </Grid>
      {totalPages > 1 && (
        <Grid className={classes.mt34} container item xs={12}>
          <Paginator count={totalPages} handlePage={handlePage} />
        </Grid>
      )}
      <div className={classes.floatButton}>
        <Link to="/condicionalidades/calendario/cadastro">
          <ButtonFloating />
        </Link>
      </div>
    </Grid>
  );
};

export default Schedule;
