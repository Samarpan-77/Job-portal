<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="mx-auto max-w-4xl space-y-6">
    <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-8 shadow-soft backdrop-blur-sm">
        <h3 class="text-3xl font-bold tracking-tight text-slate-950">AI Interview Practice</h3>
        <p class="mt-3 text-sm leading-7 text-slate-600">Practice with an HR-style interviewer that asks one question at a time, scores your answers, and helps you improve.</p>
    </div>

    <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-6 shadow-soft backdrop-blur-sm">
        <div class="grid gap-4 md:grid-cols-[1fr_auto] md:items-end">
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Role</label>
                <select id="role" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100">
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

            <div class="flex flex-wrap gap-3">
                <button id="startBtn" class="inline-flex rounded-full bg-sky-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-500 disabled:cursor-not-allowed disabled:opacity-50" onclick="startInterview()">Start HR Interview</button>
                <button id="resetBtn" class="inline-flex rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50" onclick="resetInterview()" disabled>Start New Session</button>
            </div>
        </div>
    </div>

    <div id="statusCard" class="hidden rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-800"></div>

    <div id="questionCard" class="hidden rounded-[2rem] border border-slate-200 bg-slate-950 p-6 text-white shadow-soft">
        <p class="mb-2 text-sm text-slate-300" id="questionMeta"></p>
        <h5 class="text-xl font-bold tracking-tight" id="currentQuestion"></h5>
    </div>

    <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-6 shadow-soft backdrop-blur-sm">
        <label class="mb-2 block text-sm font-semibold text-slate-700">Your Answer</label>
        <textarea id="answer" class="min-h-40 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100" rows="6" placeholder="Start the interview first, then answer the current HR question..." disabled></textarea>
        <button id="submitBtn" class="mt-4 inline-flex rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-500 disabled:cursor-not-allowed disabled:opacity-50" onclick="sendInterview()" disabled>Submit Answer</button>
    </div>

    <div id="result" class="space-y-4"></div>
</section>

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
  const palette = {
    info: 'border-sky-200 bg-sky-50 text-sky-800',
    success: 'border-emerald-200 bg-emerald-50 text-emerald-800',
    warning: 'border-amber-200 bg-amber-50 text-amber-800',
    danger: 'border-rose-200 bg-rose-50 text-rose-700'
  };
  el.className = 'rounded-2xl border px-4 py-3 text-sm ' + (palette[type] || palette.info);
  el.textContent = message;
  el.classList.remove('hidden');
}

function showQuestion(question, questionNumber, maxQuestions) {
  document.getElementById('questionMeta').textContent = 'Question ' + questionNumber + ' of ' + maxQuestions;
  document.getElementById('currentQuestion').textContent = question;
  document.getElementById('questionCard').classList.remove('hidden');
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

      let html = '<div class="rounded-[2rem] border border-slate-200 bg-white/85 p-6 shadow-soft backdrop-blur-sm">';
      html += '<p class="text-sm text-slate-700"><strong class="text-slate-950">Score:</strong> ' + escapeHtml(data.score ?? 0) + '/10</p>';
      html += '<p class="mt-3 text-sm text-slate-700"><strong class="text-slate-950">Feedback:</strong> ' + escapeHtml(data.feedback ?? 'No feedback available') + '</p>';
      if (data.improved_answer) {
        html += '<p class="mt-3 text-sm text-slate-700"><strong class="text-slate-950">Improved Answer:</strong><br>' + escapeHtml(data.improved_answer) + '</p>';
      }
      if (completed && data.final_summary) {
        html += '<hr class="my-4 border-slate-200"><p class="text-sm text-slate-700"><strong class="text-slate-950">Final HR Summary:</strong><br>' + escapeHtml(data.final_summary) + '</p>';
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
        '<div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">Interview service is unavailable right now.</div>';
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
  document.getElementById('questionCard').classList.add('hidden');
  setStatus('Choose a role and start a fresh HR interview session.', 'info');
}
</script>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
