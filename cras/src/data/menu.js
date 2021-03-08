import { IconRadar, IconHouse } from '../components/Svg';

const menu = [
  {
    path: '/',
    name: 'Início',
    Icon: IconHouse
  },
  {
    path: '/condicionalidades/calendario',
    name: 'Condicionalidades',
    Icon: IconRadar,
    submenu: [
      {
        path: '/condicionalidades/calendario',
        name: 'Calendário'
      },
      {
        path: '/condicionalidades/acompanhar',
        name: 'Acompanhar'
      }
    ]
  }
];

export default menu;
