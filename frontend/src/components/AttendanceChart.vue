<template>
  <div class="chart-wrapper">
    <Bar :data="chartData" :options="chartOptions" />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const chartData = ref({
  labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
  datasets: [
    {
      label: 'Jumlah Siswa',
      backgroundColor: ['#2e7d32', '#43a047', '#66bb6a', '#8bc34a'],
      borderColor: ['#1b5e20', '#2e7d32', '#43a047', '#558b2f'],
      borderWidth: 2,
      borderRadius: 8,
      data: [450, 30, 15, 5]
    }
  ]
});

const chartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false
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
      cornerRadius: 8
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      grid: {
        color: 'rgba(46, 125, 50, 0.1)',
        drawBorder: false
      },
      ticks: {
        color: '#1b5e20',
        font: {
          size: 12,
          weight: '500'
        }
      }
    },
    x: {
      grid: {
        display: false
      },
      ticks: {
        color: '#1b5e20',
        font: {
          size: 12,
          weight: '600'
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
}
</style>
