<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Feedback Dashboard</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root {
    --ink: #1B2430;
    --paper: #FAF9F6;
    --teal: #0F6B5C;
    --gold: #C9A227;
    --grey: #6B7280;
    --border: #E5E2DA;
  }

  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'Inter', sans-serif;
    background: var(--paper);
    color: var(--ink);
    padding: 40px;
  }

  h1 {
    font-family: 'Fraunces', serif;
    font-weight: 600;
    font-size: 28px;
  }

  p.sub { color: var(--grey); margin-top: 4px; margin-bottom: 28px; }

  .stat-row { display: flex; gap: 16px; margin-bottom: 24px; flex-wrap: wrap; }

  .stat {
    background: #fff;
    border-radius: 12px;
    padding: 20px 24px;
    box-shadow: 0 2px 12px rgba(27,36,48,0.06);
    min-width: 140px;
  }

  .stat .value { font-family: 'Fraunces', serif; font-size: 28px; font-weight: 600; }
  .stat .label { font-size: 13px; color: var(--grey); margin-top: 4px; }
  .positive { color: var(--teal); }
  .negative { color: #C0392B; }

  .grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 20px;
  }

  .card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 12px rgba(27,36,48,0.06);
  }

  .card h2 { font-size: 15px; margin-bottom: 16px; color: var(--grey); }

  table { width: 100%; border-collapse: collapse; font-size: 13px; }
  th, td { text-align: left; padding: 8px 6px; border-bottom: 1px solid var(--border); }
  th { color: var(--grey); font-weight: 600; }

  .badge {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 600;
  }
  .badge.positive { background: #E3F3EE; color: var(--teal); }
  .badge.negative { background: #FBE9E9; color: #C0392B; }
  .badge.neutral { background: #F0F0F0; color: var(--grey); }
</style>
</head>
<body>

<h1>Customer Feedback Dashboard</h1>
<p class="sub">Live view of ratings and sentiment trends</p>

<div class="stat-row" id="stat-row"></div>

<div class="grid">
  <div class="card">
    <h2>Average Rating Over Time</h2>
    <canvas id="ratingChart"></canvas>
  </div>
  <div class="card">
    <h2>Sentiment Breakdown</h2>
    <canvas id="sentimentChart"></canvas>
  </div>
  <div class="card" style="grid-column: 1 / -1;">
    <h2>Recent Feedback</h2>
    <table>
      <thead>
        <tr><th>Date</th><th>Rating</th><th>Comment</th><th>Sentiment</th></tr>
      </thead>
      <tbody id="feedback-table"></tbody>
    </table>
  </div>
</div>

<script>
async function loadDashboard() {
    const res = await fetch('get_feedback.php');
    const data = await res.json();

    if (!data.length) {
        document.body.innerHTML += '<p>No feedback yet.</p>';
        return;
    }

    const avgRating = (data.reduce((sum, d) => sum + Number(d.rating), 0) / data.length).toFixed(1);
    const posCount = data.filter(d => d.sentiment_label === 'positive').length;
    const negCount = data.filter(d => d.sentiment_label === 'negative').length;
    const neuCount = data.filter(d => d.sentiment_label === 'neutral').length;

      document.getElementById('stat-row').innerHTML = `
      <div class="stat"><div class="value">${avgRating} / 5</div><div class="label">Average Rating</div></div>
      <div class="stat"><div class="value">${data.length}</div><div class="label">Total Responses</div></div>
      <div class="stat"><div class="value positive">${posCount}</div><div class="label">Positive</div></div>
      <div class="stat"><div class="value negative">${negCount}</div><div class="label">Negative</div></div>
    `;

    const labels = data.map(d => new Date(d.date_submitted).toLocaleDateString());
    new Chart(document.getElementById('ratingChart'), {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Rating',
          data: data.map(d => d.rating),
          borderColor: '#0F6B5C',
          backgroundColor: 'rgba(15,107,92,0.1)',
          fill: true,
          tension: 0.3
        }]
      },
      options: {
        scales: { y: { min: 0, max: 5, ticks: { stepSize: 1 } } },
        plugins: { legend: { display: false } }
      }
    });

    new Chart(document.getElementById('sentimentChart'), {
      type: 'doughnut',
      data: {
        labels: ['Positive', 'Neutral', 'Negative'],
        datasets: [{
          data: [posCount, neuCount, negCount],
          backgroundColor: ['#0F6B5C', '#C9C9C9', '#C0392B']
        }]
      }
    });

    const tbody = document.getElementById('feedback-table');
    data.slice().reverse().slice(0, 15).forEach(d => {
      const label = d.sentiment_label || 'pending';
      tbody.innerHTML += `
        <tr>
            <td>${new Date(d.date_submitted).toLocaleDateString()}</td>
            <td>${'★'.repeat(d.rating)}${'☆'.repeat(5 - d.rating)}</td>
            <td>${d.comment_text}</td>
            <td><span class="badge ${label}">${label}</span></td>
          </tr>
        `;
    });



}

loadDashboard();
</script>

</body>
</html>