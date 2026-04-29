<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<h3>AI Interview Practice</h3>
<p class="text-muted">Practice with an HR-style interviewer that asks one question at a time, scores your answers, and helps you improve.</p>

<div class="card p-3 mb-3">
    <div class="mb-3">
        <label class="form-label">Role</label>
        <select id="role" class="form-control">
            <option>Backend Developer</option>
            <option>Frontend Developer</option>
            <option>Data Analyst</option>
            <option>DevOps Engineer</option>
            <option>UI/UX Designer</option>
            <option>Product Manager</option>
            <option>Marketing Executive</option>
            <option>HR Assistant</option>
        </select>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <button id="startBtn" class="btn btn-primary" onclick="startInterview()">Start HR Interview</button>
        <button id="resetBtn" class="btn btn-outline-secondary" onclick="resetInterview()" disabled>Start New Session</button>
    </div>
</div>

<div id="statusCard" class="alert alert-info d-none"></div>

<div id="questionCard" class="card p-3 mb-3 d-none">
    <p class="mb-1 text-muted" id="questionMeta"></p>
    <h5 class="mb-0" id="currentQuestion"></h5>
</div>

<div class="mb-3">
    <label class="form-label">Your Answer</label>
    <textarea id="answer" class="form-control" rows="6" placeholder="Start the interview first, then answer the current HR question..." disabled></textarea>
</div>

<button id="submitBtn" class="btn btn-success" onclick="sendInterview()" disabled>Submit Answer</button>

<div id="result" class="mt-3"></div>

<script>
let interviewState = {
  started: false,
  questionNumber: 0,
  maxQuestions: 0,
  completed: false
};

function escapeHtml(value) {
  return String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

function setStatus(message, type = 'info') {
  const el = document.getElementById('statusCard');
  el.className = 'alert alert-' + type;
  el.textContent = message;
  el.classList.remove('d-none');
}

function showQuestion(question, questionNumber, maxQuestions) {
  document.getElementById('questionMeta').textContent = 'Question ' + questionNumber + ' of ' + maxQuestions;
  document.getElementById('currentQuestion').textContent = question;
  document.getElementById('questionCard').classList.remove('d-none');
}

function startInterview() {
  const role = document.getElementById('role').value;
  const params = new URLSearchParams({
    role,
    csrf_token: '<?= $_SESSION['csrf_token'] ?>'
  });

  document.getElementById('startBtn').disabled = true;
  setStatus('Starting your HR practice interview...', 'info');

  fetch('<?= base_url('interview/begin') ?>', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: params.toString()
  })
    .then((res) => res.json())
    .then((data) => {
      if (!data.started) {
        throw new Error(data.message || 'Could not start the interview.');
      }

      interviewState = {
        started: true,
        questionNumber: Number(data.question_number || 1),
        maxQuestions: Number(data.max_questions || 5),
        completed: false
      };

      document.getElementById('role').disabled = true;
      document.getElementById('answer').disabled = false;
      document.getElementById('submitBtn').disabled = false;
      document.getElementById('resetBtn').disabled = false;
      document.getElementById('startBtn').disabled = true;
      document.getElementById('answer').value = '';
      document.getElementById('result').innerHTML = '';

      showQuestion(data.question, interviewState.questionNumber, interviewState.maxQuestions);
      setStatus(data.intro || 'Your interview session is ready.', 'success');
    })
    .catch((error) => {
      document.getElementById('startBtn').disabled = false;
      setStatus(error.message || 'Interview service is unavailable right now.', 'danger');
    });
}

function sendInterview() {
  if (!interviewState.started || interviewState.completed) {
    setStatus('Start a session before submitting an answer.', 'warning');
    return;
  }

  const params = new URLSearchParams({
    answer: document.getElementById('answer').value,
    csrf_token: '<?= $_SESSION['csrf_token'] ?>'
  });

  document.getElementById('submitBtn').disabled = true;
  setStatus('Reviewing your answer and preparing the next HR response...', 'info');

  fetch('<?= base_url('interview/submit') ?>', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: params.toString()
  })
    .then((res) => res.json())
    .then((data) => {
      const completed = Boolean(data.completed);
      interviewState.completed = completed;

      let html = '<div class="card p-3">';
      html += '<p><strong>Score:</strong> ' + escapeHtml(data.score ?? 0) + '/10</p>';
      html += '<p><strong>Feedback:</strong> ' + escapeHtml(data.feedback ?? 'No feedback available') + '</p>';
      if (data.improved_answer) {
        html += '<p><strong>Improved Answer:</strong><br>' + escapeHtml(data.improved_answer) + '</p>';
      }
      if (completed && data.final_summary) {
        html += '<hr><p><strong>Final HR Summary:</strong><br>' + escapeHtml(data.final_summary) + '</p>';
      }
      html += '</div>';
      document.getElementById('result').innerHTML = html;

      if (completed) {
        document.getElementById('answer').value = '';
        document.getElementById('answer').disabled = true;
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('startBtn').disabled = false;
        document.getElementById('role').disabled = false;
        setStatus('Interview complete. Review the HR summary, then start a new session when you are ready.', 'success');
        return;
      }

      interviewState.questionNumber += 1;
      showQuestion(data.next_question, interviewState.questionNumber, interviewState.maxQuestions);
      document.getElementById('answer').value = '';
      document.getElementById('submitBtn').disabled = false;
      setStatus('Feedback is ready. Continue with the next HR question.', 'success');
    })
    .catch(() => {
      document.getElementById('submitBtn').disabled = false;
      document.getElementById('result').innerHTML =
        '<div class="alert alert-danger">Interview service is unavailable right now.</div>';
      setStatus('Something went wrong while reviewing your answer.', 'danger');
    });
}

function resetInterview() {
  interviewState = {
    started: false,
    questionNumber: 0,
    maxQuestions: 0,
    completed: false
  };
  document.getElementById('role').disabled = false;
  document.getElementById('startBtn').disabled = false;
  document.getElementById('resetBtn').disabled = true;
  document.getElementById('submitBtn').disabled = true;
  document.getElementById('answer').disabled = true;
  document.getElementById('answer').value = '';
  document.getElementById('result').innerHTML = '';
  document.getElementById('questionCard').classList.add('d-none');
  setStatus('Choose a role and start a fresh HR interview session.', 'info');
}
</script>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
