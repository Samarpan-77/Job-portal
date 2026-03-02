<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<h3>Interview Analytics</h3>

<div class="row">
    <div class="col-md-6">
        <div class="card p-3">
            <h5>Average Score</h5>
            <p><?= round((float)$avgScore, 2) ?></p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3">
            <h5>Total Sessions</h5>
            <p><?= (int)$totalSessions ?></p>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
