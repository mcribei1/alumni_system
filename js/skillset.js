document.addEventListener('DOMContentLoaded', function () {
  const stateDropdown = document.getElementById('stateFilter');
  const jobTitleDropdown = document.getElementById('jobTitleFilter');
  const skillInput = document.getElementById('skillKeyword');
  const applyBtn = document.getElementById('applySkillFilter');
  const resultContainer = document.getElementById('skillsetResults');
  const topSkillsBtn = document.getElementById('generateSkillsetReport');

  // Load state and job title options
  fetch('get_filter_options.php')
    .then(res => res.json())
    .then(data => {
      data.states.forEach(state => {
        const option = document.createElement('option');
        option.value = state;
        option.textContent = state;
        stateDropdown.appendChild(option);
      });

      data.jobTitles.forEach(title => {
        const option = document.createElement('option');
        option.value = title;
        option.textContent = title;
        jobTitleDropdown.appendChild(option);
      });
    });

  // Fetch skillset results
  function fetchSkillsetData() {
    const skill = skillInput.value.trim();
    const state = stateDropdown.value;
    const jobTitle = jobTitleDropdown.value;

    fetch(`skillset_data.php?skill=${encodeURIComponent(skill)}&state=${state}&jobTitle=${jobTitle}`)
      .then(res => res.text())
      .then(html => {
        resultContainer.innerHTML = html;
        attachClickHandlers();
      });
  }

  // Attach skill button chart click
  function attachClickHandlers() {
    document.querySelectorAll('.skill-btn').forEach(skillBtn => {
      skillBtn.addEventListener('click', () => {
        const skill = skillBtn.dataset.skill;
        const alumniID = skillBtn.closest('tr').querySelector('.name-btn').dataset.id;

        fetch(`alumni_skills_chart.php?id=${alumniID}&skill=${encodeURIComponent(skill)}`)
          .then(res => res.json())
          .then(data => {
            showSkillChartModal(data.labels, data.counts, data.highlightIndex, skill);
          });
      });
    });
  }

  // Chart modal renderer
  function showSkillChartModal(labels, counts, highlightIndex, chartTitle) {
    const modal = document.createElement('div');
    modal.classList.add('chart-modal');

    const modalContent = document.createElement('div');
    modalContent.classList.add('chart-modal-content');

    const closeBtn = document.createElement('span');
    closeBtn.classList.add('chart-close');
    closeBtn.innerHTML = '&times;';
    closeBtn.addEventListener('click', () => modal.remove());

    const canvas = document.createElement('canvas');
    modalContent.appendChild(closeBtn);
    modalContent.appendChild(canvas);
    modal.appendChild(modalContent);
    document.body.appendChild(modal);

    const backgroundColors = counts.map((_, i) =>
      i === highlightIndex ? '#FDBB30' : '#ccc'
    );

    new Chart(canvas.getContext('2d'), {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: chartTitle,
          data: counts,
          backgroundColor: backgroundColors
        }]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: { enabled: true }
        },
        scales: {
          x: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Number of Alumni'
            }
          }
        }
      }
    });
  }

  // 🔹 "View Top Skills" button click
  topSkillsBtn.addEventListener('click', () => {
    fetch('skillset_global_chart.php')
      .then(res => res.json())
      .then(data => {
        showSkillChartModal(data.labels, data.counts, -1, 'Top Skills Among Alumni');
      });
  });

  // Load initial results
  applyBtn.addEventListener('click', fetchSkillsetData);
  fetchSkillsetData();
});







