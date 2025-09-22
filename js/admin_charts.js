//diagrammes avec chart.js
const ctx1 = document.getElementById("covoituragesChart").getContext("2d");
const covoituragesChart = new Chart(ctx1, {
  type: "bar",
  data: {
    labels: covoituragesLabels,
    datasets: [
      {
        label: "Nombre de covoiturages par jour",
        data: covoituragesData,
        backgroundColor: "rgb(68, 221, 221)",
      },
    ],
  },
  options: {
    responsive: true,
    scales: {
      x: {
        ticks: {
          color: "rgb(0, 68, 119)", // couleur du texte en bas
        },
      },
      y: {
        beginAtZero: true,
        ticks: {
          color: "rgb(0, 68, 119)", // couleur du texte en bas
        },
      },
    },
  },
});

const ctx2 = document.getElementById("creditsChart").getContext("2d");
const creditsChart = new Chart(ctx2, {
  type: "line",
  data: {
    labels: creditsLabels,
    datasets: [
      {
        label: "Crédits gagnés par jour",
        data: creditsData,
        backgroundColor: "rgb(170, 238, 136)",
        borderColor: "rgb(0, 68, 119)",
        fill: false,
        tension: 0.3,
      },
    ],
  },
  options: {
    responsive: true,
    scales: {
      x: {
        ticks: {
          color: "rgb(0, 68, 119)", // couleur du texte en bas
        },
      },
      y: {
        beginAtZero: true,
        ticks: {
          color: "rgb(0, 68, 119)", // couleur du texte en bas
        },
      },
    },
  },
});
