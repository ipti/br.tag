//Agency Data
import ChartConfig from 'Constants/chart-config';

// helpers
import { hexToRgbA } from 'Helpers/helpers';

//Welcome bar chart 1 data
export const WelcomeBarChart1 = {
   labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24'],
   data: [75, 60, 50, 35, 90, 35, 75, 20, 10, 20, 40, 5, 30, 75, 40, 90, 35, 20, 40, 30, 50, 35, 20, 75],
   color: ChartConfig.color.primary
}

//Welcome bar chart 2 data
export const WelcomeBarChart2 = {
   labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24'],
   data: [75, 60, 50, 35, 90, 35, 75, 20, 10, 20, 40, 5, 30, 75, 40, 90, 35, 20, 40, 30, 50, 35, 20, 75],
   color: ChartConfig.color.info
}

// traffic Status
export const trafficStatus = {
   chartLabels: ['0.00', '1.0', '2.0', '3.0', '4.0', '5.0', '6.0'],
   chartDatasets: [
      {
         label: 'Series A',
         backgroundColor: ChartConfig.color.primary,
         borderColor: ChartConfig.color.primary,
         borderWidth: 1,
         hoverBackgroundColor: ChartConfig.color.primary,
         hoverBorderColor: ChartConfig.color.primary,
         data: [65, 59, 80, 81, 56, 55, 40]
      },
      {
         label: 'Series B',
         backgroundColor: ChartConfig.color.default,
         borderColor: ChartConfig.color.default,
         borderWidth: 1,
         hoverBackgroundColor: ChartConfig.color.default,
         hoverBorderColor: ChartConfig.color.default,
         data: [45, 39, 40, 60, 35, 25, 60]
      }
   ],
   onlineSources: '3500',
   today: '17,020',
   lastMonth: '20.30%'
}
// total earns
export const totalEarns = {
   labels: ['Jan 1', 'Jan 7', 'Jan 14', 'Jan 21', 'Jan 28', 'Feb 4', 'Feb 11', 'Feb 18', 'Feb 25', 'Feb 28', 'Mar 2', 'Mar 6'],
   datasets: [
      {
         label: 'Sales',
         fill: true,
         lineTension: 0,
         fillOpacity: 0.5,
         backgroundColor: hexToRgbA(ChartConfig.color.primary, 0.8),
         borderColor: ChartConfig.color.primary,
         pointBorderColor: ChartConfig.color.white,
         pointBackgroundColor: ChartConfig.color.white,
         pointBorderWidth: 0,
         pointHoverRadius: 5,
         pointHoverBackgroundColor: hexToRgbA(ChartConfig.color.primary, 1),
         pointHoverBorderColor: hexToRgbA(ChartConfig.color.primary, 1),
         pointHoverBorderWidth: 8,
         pointRadius: 0,
         pointHitRadius: 10,
         data: [250, 350, 270, 420, 380, 220, 400, 550, 480, 190, 390, 380]
      },
      {
         label: 'Visitors',
         fill: true,
         lineTension: 0,
         fillOpacity: 0.5,
         backgroundColor: hexToRgbA(ChartConfig.color.warning, 0.4),
         borderColor: ChartConfig.color.warning,
         pointBorderColor: ChartConfig.color.white,
         pointBackgroundColor: ChartConfig.color.white,
         pointBorderWidth: 0,
         pointHoverRadius: 5,
         pointHoverBackgroundColor: hexToRgbA(ChartConfig.color.warning, 1),
         pointHoverBorderColor: hexToRgbA(ChartConfig.color.warning, 1),
         pointHoverBorderWidth: 8,
         pointRadius: 0,
         pointHitRadius: 10,
         data: [600, 400, 500, 350, 650, 630, 450, 480, 650, 500, 530, 550,]
      }
   ]
}

// Daily Sales
export const dailySales = {
   label: 'Daily Sales',
   chartdata: [100, 200, 125, 250, 200, 150, 200],
   labels: ['9', '10', '11', '12', '13', '14', '15'],
}

//Traffic Channel
export const trafficChannel = {
   label: 'Direct User',
   labels: ['Direct User', 'Referral', 'Facebook', 'Google', 'Instagram'],
   chartdata: [8.5, 6.75, 5.5, 7, 4.75]
}