import React, { useEffect, useState } from 'react';

import { makeStyles } from '@material-ui/core/styles';

import IconHealthWhite from '../Svg/IconHealthWhite';
import IconPaperWhite from '../Svg/IconPaperWhite';
import { styles } from './styles';
const useStyles = makeStyles(styles);

const IconHealthPaper = (props) => {
  const classes = useStyles();
  const [icon, setIcon] = useState(<IconHealthWhite />);

  useEffect(() => {
    if (props.iconName && props.iconName !== '') {
      switch (props.iconName) {
        case 'paper':
          setIcon(<IconPaperWhite />);
          break;
      }
    }
  }, [props]);

  return <div className={`${classes.boxIcon}`}>{icon}</div>;
};

export default IconHealthPaper;
