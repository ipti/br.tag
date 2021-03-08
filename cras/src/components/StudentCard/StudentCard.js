import React from 'react';
import { makeStyles } from '@material-ui/core/styles';
import Grid from '@material-ui/core/Grid';
import IconMale from '../../assets/images/boy.svg';
import IconWoman from '../../assets/images/girl.svg';
import { styles } from './styles';
const useStyles = makeStyles(styles);

const StudentCard = (props) => {
  const { student } = props;
  const classes = useStyles();

  const isOutOfAverage =
    (student.type === 'E' && parseInt(student.frequency) < 75) ||
    (student.type === 'H' && !student.vaccined);

  return (
    <Grid item xl={3} md={4} sm={12} xs={12} className={classes.container}>
      <div
        className={`${classes.boxStudent} ${classes.floatLeft} ${
          isOutOfAverage ? classes.borderRed : classes.borderGray
        }`}
      >
        <div className={classes.iconStudent}>
          <img src={student.sex === '1' ? IconMale : IconWoman} alt="Icone de aluno" />
        </div>
        <div className={`${classes.floatLeft} ${classes.nameStudent}`}>
          <div title={student.name} className={`${classes.truncate}`}>
            {student.name}
          </div>
          <div className={classes.subtitleStudent}>
            {' '}
            {student.type === 'E' ? `Frequência ${student.frequency}%` : ''}
            {student.type === 'H' ? `${student.vaccined ? 'Vacinado' : 'Sem Vacinação'}` : ''}
          </div>
        </div>
      </div>
    </Grid>
  );
};

export default StudentCard;
