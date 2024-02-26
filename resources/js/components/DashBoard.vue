<template>
  <div class="dashboard-contaniner">
    <h1 class="mb-4 title">Dashbord</h1>
    <div class="dash-bord-tile">
      <v-row>
        <v-col>
          <DashboardTiles :data="pending"></DashboardTiles>
        </v-col>
        <v-col>
          <DashboardTiles :data="total"></DashboardTiles>
        </v-col>
        <v-col>
          <DashboardTiles :data="Archives"></DashboardTiles>
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <div class="chart-tile">
            <Bar id="my-chart-id" :options="chartOptions" :data="chartData" />
          </div>
        </v-col>
        <v-col>
          <div class="chart-tile">
            <Bar id="my-chart-id" :options="chartOptions" :data="chartData2" />
          </div>
        </v-col>
      </v-row>
    </div>
  </div>
</template>

<script>
import DashboardTiles from "./DashboardTiles.vue";
import { Bar } from "vue-chartjs";
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from "chart.js";

ChartJS.register(
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
);
export default {
  components: {
    DashboardTiles,
    Bar,
  },
  data() {
    return {
      pending: {
        title: "Pending Appointments",
        count: 10,
        icon: "fa-solid fa-clock-rotate-left",
      },
      total: {
        title: "Total Appointments",
        count: 10,
        icon: "fa-solid fa-calendar",
      },
      Archives: {
        title: "Archives Appointments",
        count: 30,
        icon: "fa-solid fa-box-archive",
      },
      chartData: {
        labels: ["Pending", "Total", "Archives"],
        datasets: [
          {
            label: "Appointments",
            backgroundColor: ["#DF3F3F", "#FAEC30", "#5F5FFF"], // Example colors
            data: [10, 10, 30],
          },
        ],
      },
      chartData2: {
        labels: ["Pending", "Total", "Archives"],
        datasets: [
          {
            label: "Certificate",
            backgroundColor: ["#DF3F3F", "#FAEC30", "#5F5FFF"], // Example colors
            data: [10, 10, 30],
          },
        ],
      },
      chartOptions: {
        responsive: true,
      },
    };
  },
};
</script>

<style lang="scss" scoped>
.dashboard-contaniner {
  height: 100%;
  padding: 30px;
  box-sizing: border-box;
}
.chart-tile {
  background: #fff;
  border-radius: 8px;
  padding: 20px;
  box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
}
</style>
