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
            <Bar
              id="my-chart-id"
              v-if="!loading"
              :options="chartOptions"
              :data="chartData"
            />
          </div>
        </v-col>
        <v-col>
          <div class="chart-tile">
            <Bar
              v-if="!loading"
              id="my-chart-id"
              :options="chartOptions"
              :data="chartData2"
            />
          </div>
        </v-col>
      </v-row>
    </div>
  </div>
</template>

<script>
import DashboardTiles from "./DashboardTiles.vue";
import axios from "axios";
import config from "../utils.js";
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
      chart: null,
      chart2: null,
      loading: true,
      pending: {
        title: "Pending Appointments",
        count: 0,
        icon: "fa-solid fa-clock-rotate-left",
      },
      total: {
        title: "Total Appointments",
        count: 0,
        icon: "fa-solid fa-calendar",
      },
      Archives: {
        title: "Archives Appointments",
        count: 0,
        icon: "fa-solid fa-box-archive",
      },

      chartOptions: {
        responsive: true,
      },
    };
  },
  computed: {
    chartData() {
      return this.chart;
    },
    chartData2() {
      return this.chart2;
    },
  },
  created() {
    this.getDashboardData();
  },
  methods: {
    getDashboardData() {
      var token = localStorage.getItem("token");
      axios({
        method: "post",
        url: `${config.baseUrl}/appointment/dashboard`,
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
        .then((res) => {
          this.pending.count = res.data.pending;
          this.total.count = res.data.appointment;
          this.Archives.count = res.data.archive;

          const chartData = {
            labels: ["Pending", "Total", "Archives"],
            datasets: [
              {
                label: "Appointments",
                backgroundColor: ["#DF3F3F", "#FAEC30", "#5F5FFF"], // Example colors
                data: [
                  res.data.pending,
                  res.data.appointment,
                  res.data.archive,
                ],
              },
            ],
          };

          this.chart = chartData;

          var chartData2 = {
            labels: [],
            datasets: [
              {
                label: "Certificate Released",
                backgroundColor: [],
                data: [],
              },
            ],
          };

          res.data.documents.forEach((item) => {
            chartData2.labels.push(item.document.name);
            chartData2.datasets[0].data.push(item.appointment_count);
            chartData2.datasets[0].backgroundColor.push(this.getRandomColor());
          });

          this.chart2 = chartData2;
        })
        .catch((error) => {
          console.error("Error occurred:", error);
        });

      setTimeout(() => {
        this.loading = false;
      }, 500); // 3000 milliseconds = 3 seconds
    },
    getRandomColor() {
      // Generate a random color in hexadecimal format
      return "#" + Math.floor(Math.random() * 16777215).toString(16);
    },
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
