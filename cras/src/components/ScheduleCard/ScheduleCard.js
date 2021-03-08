import React from 'react';
import { makeStyles } from '@material-ui/core/styles';
import { Link } from 'react-router-dom';
import { parseISO, format } from 'date-fns';
import { ptBR } from 'date-fns/locale';

import IconHealthPaper from '../IconHealthPaper';

import { styles } from './styles';
const useStyles = makeStyles(styles);

const ScheduleCard = (props) => {
  const classes = useStyles();

  const dateStartHealth1 = parseISO(props.schedule.dateStartHealth1);
  const nameMonthStartHealth1 = format(dateStartHealth1, 'MMMM', { locale: ptBR });
  const dateEndHealth2 = parseISO(props.schedule.dateEndHealth2);
  const nameMonthEndHealth1 = format(dateEndHealth2, 'MMMM', { locale: ptBR });

  const dateStartEducation1 = parseISO(props.schedule.dateStartEducation1);
  const nameMonthStartEducation1 = format(dateStartEducation1, 'MMMM', { locale: ptBR });
  const dateEndEndEducation3 = parseISO(props.schedule.dateEndEducation3);
  const nameMonthEndEducation3 = format(dateEndEndEducation3, 'MMMM', { locale: ptBR });

  return (
    <div className={classes.boxCard}>
      <Link to={`/condicionalidades/calendario/editar/${props.schedule._id}`}>
        <IconHealthPaper />
        <div className={classes.boxTitle}>
          <div className={classes.title1}>Saúde</div>
          <div>{`${nameMonthStartHealth1} - ${nameMonthEndHealth1}`}</div>
        </div>
        <IconHealthPaper iconName="paper" />
        <div className={classes.boxTitle}>
          <div className={classes.title1}>Educação</div>
          <div>{`${nameMonthStartEducation1} - ${nameMonthEndEducation3}`}</div>
        </div>
        <div className={classes.boxYear}>{props.schedule.year}</div>
      </Link>
    </div>
  );
};

export default ScheduleCard;
