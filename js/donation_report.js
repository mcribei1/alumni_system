document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('searchInput');
  const typeFilter = document.getElementById('donationType');
  const amountFilter = document.getElementById('amountRange');
  const yearFilter = document.getElementById('donationYear');
  const applyBtn = document.getElementById('applyBtn');
  const generateBtn = document.getElementById('generateChartBtn');
  const resultsContainer = document.getElementById('results');
  let chartInstance = null;

  function fetchDonationData() {
    const name = searchInput.value.trim();
    const type = typeFilter.value;
    const amount = amountFilter.value;
    const year = yearFilter.value;

    fetch(`donation_report_data.php?name=${encodeURIComponent(name)}&type=${type}&amount=${amount}&year=${year}`)
      .then(res => res.text())
      .then(html => {
        resultsContainer.innerHTML = html;
        attachToggleEvents();
      })
      .catch(err => console.error('Error loading donation data:', err));
  }

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

  function loadChart() {
    const name = searchInput.value.trim();
    const type = typeFilter.value;
    const amount = amountFilter.value;
    const year = yearFilter.value;

    fetch(`donation_chart_data.php?name=${encodeURIComponent(name)}&type=${type}&amount=${amount}&year=${year}`)
      .then(res => res.json())
      .then(data => {
        const labels = data.map(item => item.label);
        const values = data.map(item => item.total);

        const ctx = document.getElementById('donationChart').getContext('2d');
        if (chartInstance) chartInstance.destroy();

        chartInstance = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [{
              label: 'Total Donations',
              data: values,
              backgroundColor: '#FDBB30',
              borderColor: '#d99e2b',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                display: false
              }
            },
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      })
      .catch(err => console.error('Error loading chart:', err));
  }

  fetchDonationData(); // Initial data
  applyBtn.addEventListener('click', fetchDonationData);
  generateBtn.addEventListener('click', loadChart);
});





