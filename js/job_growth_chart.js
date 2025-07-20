document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('searchInput');
  const statusFilter = document.getElementById('statusFilter');
  const applyBtn = document.getElementById('applyBtn');
  const resultsContainer = document.getElementById('results');
  const generateBtn = document.getElementById('generateChartBtn');
  const chartTypeSelect = document.getElementById('chartType');
  const chartCanvas = document.getElementById('growthChart');

  let chartInstance = null;

  // Load filtered alumni job cards
  function fetchJobGrowthData() {
    const search = searchInput.value.trim();
    const status = statusFilter.value;

    fetch(`job_growth_data.php?search=${encodeURIComponent(search)}&status=${status}`)
      .then(res => res.text())
      .then(html => {
        resultsContainer.innerHTML = html;
        attachToggleEvents();
      })
      .catch(err => console.error('Error loading job data:', err));
  }

  // Toggle detailed job entries
  function attachToggleEvents() {
    document.querySelectorAll('.alumni-toggle').forEach(button => {
      button.addEventListener('click', function () {
        const targetId = this.dataset.target;
        const detailSection = document.getElementById(targetId);
        if (detailSection) {
          detailSection.style.display = detailSection.style.display === 'block' ? 'none' : 'block';
        }
      });
    });
  }

  // Load and render the chart
  function loadChart(type = 'alumni') {
    fetch(`job_growth_chart_data.php?type=${type}`)
      .then(res => res.json())
      .then(data => {
        const labels = data.map(item => item.name);
        const values = data.map(item => item.count);

        const ctx = chartCanvas.getContext('2d');

        if (chartInstance) {
          chartInstance.destroy();
        }

        chartInstance = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [{
              label: `Number of Jobs by ${capitalizeFirst(type)}`,
              data: values,
              backgroundColor: '#FDBB30',
              borderColor: '#d9a323',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                display: true,
                position: 'top'
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  precision: 0,
                  stepSize: 1
                }
              }
            }
          }
        });
      })
      .catch(err => console.error('Error loading chart:', err));
  }

  function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  // Event bindings
  fetchJobGrowthData();
  applyBtn.addEventListener('click', fetchJobGrowthData);
  generateBtn.addEventListener('click', () => {
    const selectedType = chartTypeSelect?.value || 'alumni';
    loadChart(selectedType);
  });
});



