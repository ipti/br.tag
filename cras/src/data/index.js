import { format } from 'date-fns';

const yearNow = format(new Date(), 'yyyy');
let arrYears = [];

for (var i = yearNow; i >= yearNow - 2; i--) {
  arrYears.push({ value: i, label: i });
}

export const years = arrYears;

export const steps = [
  { value: '1', label: 'Etapa 1' },
  { value: '2', label: 'Etapa 2' },
  { value: '3', label: 'Etapa 3' }
];
