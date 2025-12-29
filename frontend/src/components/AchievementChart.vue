<template>
  <div class="chart-wrapper">
    <Pie :data="chartData" :options="chartOptions" />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Pie } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, ArcElement);

const chartData = ref({
  labels: ['Sangat Baik', 'Baik', 'Cukup', 'Kurang'],
  datasets: [
    {
      backgroundColor: ['#2e7d32', '#43a047', '#66bb6a', '#8bc34a'],
      borderColor: ['#1b5e20', '#2e7d32', '#43a047', '#558b2f'],
      borderWidth: 3,
      data: [40, 30, 20, 10]
    }
  ]
});

const chartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom',
      labels: {
        color: '#1b5e20',
        font: {
          size: 12,
          weight: '600'
        },
        padding: 15,
        usePointStyle: true,
        pointStyle: 'circle'
      }
    },
    tooltip: {
      backgroundColor: 'rgba(46, 125, 50, 0.9)',
      padding: 12,
      borderColor: '#2e7d32',
      borderWidth: 2,
      titleFont: {
        size: 14,
        weight: 'bold'
      },
      bodyFont: {
        size: 13
      },
      cornerRadius: 8,
      callbacks: {
        label: function(context) {
          let label = context.label || '';
          if (label) {
            label += ': ';
          }
          const value = context.parsed;
          const total = context.dataset.data.reduce((a, b) => a + b, 0);
          const percentage = ((value / total) * 100).toFixed(1);
          label += value + ' siswa (' + percentage + '%)';
          return label;
        }
      }
    }
  }
});
</script>

<style scoped>
.chart-wrapper {
  height: 280px;
  padding: 1rem 0;
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
