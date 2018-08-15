/**
 * agency data
 */

// chart config
import ChartConfig from 'Constants/chart-config';

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

// total sales
export const totalSales = {
   label: 'Sales',
   chartdata: [250, 310, 150, 420, 250, 450],
   labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June'],
}

// net profit
export const netProfit = {
   label: 'Net Profit',
   chartdata: [250, 310, 150, 420, 250, 450],
   labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June'],
}

// tax stats
export const taxStats = {
   label: 'Tax',
   chartdata: [250, 310, 150, 420, 250, 450],
   labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June'],
}

// expenses stats
export const expenses = {
   label: 'Expenses',
   chartdata: [250, 310, 150, 420, 250, 450],
   labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June'],
}