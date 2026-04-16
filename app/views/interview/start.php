<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<h3>AI Interview Practice</h3>

<div class="mb-3">
    <label class="form-label">Role</label>
    <select id="role" class="form-control">
        <option>Backend Developer</option>
        <option>Frontend Developer</option>
        <option>Data Analyst</option>
        <option>DevOps Engineer</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Your Answer</label>
    <textarea id="answer" class="form-control" rows="5" placeholder="Type your answer here..."></textarea>
</div>

<button class="btn btn-primary" onclick="sendInterview()">Submit Answer</button>

<div id="result" class="mt-3"></div>

<script>
function sendInterview() {
  const params = new URLSearchParams({
    role: document.getElementById('role').value,
    answer: document.getElementById('answer').value,
    csrf_token: '<?= $_SESSION['csrf_token'] ?>'
  });

  fetch('<?= base_url('interview/submit') ?>', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: params.toString()
  })
    .then((res) => res.json())
    .then((data) => {
      document.getElementById('result').innerHTML =
        '<div class="card p-3">' +
        '<p><strong>Score:</strong> ' + (data.score ?? 0) + '/10</p>' +
        '<p><strong>Feedback:</strong> ' + (data.feedback ?? 'No feedback available') + '</p>' +
        '<p><strong>Improved Answer:</strong><br>' + (data.improved_answer ?? '') + '</p>' +
        '</div>';
    })
    .catch(() => {
      document.getElementById('result').innerHTML =
        '<div class="alert alert-danger">Interview service is unavailable right now.</div>';
    });
}
</script>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
