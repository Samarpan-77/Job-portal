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

<button class="btn btn-primary" onclick="startInterview()">Start Interview</button>

<div id="chat" class="mt-3" style="display: none;">
  <div id="messages" class="border p-3" style="height: 400px; overflow-y: auto;"></div>
  <div class="input-group mt-3">
    <input type="text" id="message" class="form-control" placeholder="Type your answer..." onkeypress="handleKeyPress(event)">
    <button class="btn btn-primary" onclick="sendMessage()">Send</button>
  </div>
</div>

<script>
  let currentRole = '';

  function startInterview() {
    currentRole = document.getElementById('role').value;
    document.getElementById('chat').style.display = 'block';
    document.getElementById('messages').innerHTML = '<div class="text-muted">Starting interview...</div>';
    sendMessage(true); // Start with empty message to get first question
  }

  function handleKeyPress(event) {
    if (event.key === 'Enter') {
      sendMessage();
    }
  }

  function sendMessage(isStart = false) {
    const messageInput = document.getElementById('message');
    const message = isStart ? '' : messageInput.value.trim();
    if (!isStart && message === '') return;

    const params = new URLSearchParams({
      role: currentRole,
      message: message,
      csrf_token: '<?= $_SESSION['csrf_token'] ?>'
    });

    fetch('<?= base_url('interview/chat') ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: params.toString()
      })
      .then(res => res.json())
      .then(data => {
        const messagesDiv = document.getElementById('messages');

        if (data.feedback) {
          messagesDiv.innerHTML += '<div class="alert alert-info"><strong>Feedback:</strong> ' + data.feedback + '</div>';
          if (data.score !== null) {
            messagesDiv.innerHTML += '<div class="alert alert-success"><strong>Score:</strong> ' + data.score + '/10</div>';
          }
        }

        if (data.question) {
          messagesDiv.innerHTML += '<div class="fw-bold">HR: ' + data.question + '</div>';
        }

        if (data.finished) {
          messagesDiv.innerHTML += '<div class="alert alert-success"><strong>Interview Complete!</strong><br>Average Score: ' + data.average_score + '/10<br>Overall Feedback: ' + data.overall_feedback + '</div>';
          document.getElementById('message').disabled = true;
          document.querySelector('button[onclick="sendMessage()"]').disabled = true;
        }

        messagesDiv.scrollTop = messagesDiv.scrollHeight;
        if (!isStart) messageInput.value = '';
      })
      .catch(() => {
        document.getElementById('messages').innerHTML += '<div class="alert alert-danger">Error communicating with interview service.</div>';
      });
  }
</script>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>