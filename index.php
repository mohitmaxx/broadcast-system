<?php

define('APP_START', true);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/queue.php';

requireLogin();

$pageTitle = 'Dashboard';
$pageHeading = 'Dashboard';
$pageDescription = 'Telegram Broadcast Overview';

$totalUsers = totalUsers();

$totalBroadcasts = totalBroadcasts();

$activeBroadcasts = activeBroadcasts();

$completedBroadcasts = completedBroadcasts();

$recentBroadcasts = recentBroadcasts(10);

include __DIR__ . '/includes/header.php';
?>

<div class="row g-4 mb-4">

    <div class="col-lg-3 col-md-6">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <div class="text-muted">

                            Total Users

                        </div>

                        <h2>

                            <?=number($totalUsers)?>

                        </h2>

                    </div>

                    <div class="display-5 text-primary">

                        <i class="bi bi-people-fill"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <div class="text-muted">

                            Broadcasts

                        </div>

                        <h2>

                            <?=number($totalBroadcasts)?>

                        </h2>

                    </div>

                    <div class="display-5 text-success">

                        <i class="bi bi-megaphone-fill"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <div class="text-muted">

                            Running

                        </div>

                        <h2>

                            <?=number($activeBroadcasts)?>

                        </h2>

                    </div>

                    <div class="display-5 text-warning">

                        <i class="bi bi-lightning-charge-fill"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <div class="text-muted">

                            Completed

                        </div>

                        <h2>

                            <?=number($completedBroadcasts)?>

                        </h2>

                    </div>

                    <div class="display-5 text-info">

                        <i class="bi bi-check-circle-fill"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white">

        <div class="d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                Recent Broadcasts

            </h5>

            <a
                href="broadcast.php"
                class="btn btn-primary btn-sm">

                <i class="bi bi-plus-circle"></i>

                New Broadcast

            </a>

        </div>

    </div>

    <div class="card-body p-0">
        <?php if (empty($recentBroadcasts)): ?>

<div class="p-5 text-center text-muted">

    <i class="bi bi-inbox display-4"></i>

    <p class="mt-3 mb-0">

        No broadcasts found.

    </p>

</div>

<?php else: ?>

<div class="table-responsive">

<table class="table table-hover align-middle mb-0">

<thead class="table-light">

<tr>

<th>ID</th>

<th>Type</th>

<th>Created</th>

<th>Status</th>

<th width="220">Progress</th>

<th class="text-end">Actions</th>

</tr>

</thead>

<tbody>

<?php foreach ($recentBroadcasts as $broadcast): ?>

<?php

$stats = queueStatistics($broadcast['id']);

$status = strtolower($broadcast['status']);

switch ($status) {

    case 'running':
    case 'processing':
        $badge = 'warning';
        break;

    case 'completed':
    case 'finished':
        $badge = 'success';
        break;

    case 'paused':
        $badge = 'secondary';
        break;

    case 'failed':
        $badge = 'danger';
        break;

    default:
        $badge = 'primary';
}

?>

<tr>

<td>

<strong>#<?= (int)$broadcast['id'] ?></strong>

</td>

<td>

<?= e(ucfirst($broadcast['type'])) ?>

</td>

<td>

<?= formatDate($broadcast['created_at']) ?>

</td>

<td>

<span class="badge bg-<?= $badge ?>">

<?= e(ucfirst($status)) ?>

</span>

</td>

<td>

<div class="progress" style="height:20px;">

<div
class="progress-bar"
role="progressbar"
style="width: <?= $stats['progress'] ?>%;">

<?= $stats['progress'] ?>%

</div>

</div>

<div class="small text-muted mt-2">

<?= number($stats['success']) ?>

Success

|

<?= number($stats['failed']) ?>

Failed

|

<?= number($stats['pending']) ?>

Pending

</div>

</td>

<td class="text-end">

<a
href="history_view.php?id=<?= (int)$broadcast['id'] ?>"
class="btn btn-sm btn-outline-primary">

<i class="bi bi-eye"></i>

</a>

<?php if (
    $status === 'failed'
): ?>

<a
href="retry_failed.php?id=<?= (int)$broadcast['id'] ?>"
class="btn btn-sm btn-outline-warning">

<i class="bi bi-arrow-repeat"></i>

</a>

<?php endif; ?>

<a
href="history_delete.php?id=<?= (int)$broadcast['id'] ?>"
class="btn btn-sm btn-outline-danger"
onclick="return confirmAction('Delete this broadcast?')">

<i class="bi bi-trash"></i>

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<?php endif; ?>

</div>

</div>

<div class="row">

<div class="col-lg-6">

<div class="card border-0 shadow-sm">

<div class="card-header bg-white">

<h5 class="mb-0">

Quick Actions

</h5>

</div>

<div class="card-body">

<div class="d-grid gap-2">

<a
href="broadcast.php"
class="btn btn-primary">

<i class="bi bi-megaphone"></i>

Create Broadcast

</a>

<a
href="history.php"
class="btn btn-outline-secondary">

<i class="bi bi-clock-history"></i>

Broadcast History

</a>

</div>

</div>

</div>

</div>

<div class="col-lg-6">

<div class="card border-0 shadow-sm">

<div class="card-header bg-white">

<h5 class="mb-0">

System Status

</h5>

</div>

<div class="card-body">

<ul class="list-group list-group-flush">

<li class="list-group-item d-flex justify-content-between">

<span>Total Users</span>

<strong><?= number($totalUsers) ?></strong>

</li>

<li class="list-group-item d-flex justify-content-between">

<span>Running Broadcasts</span>

<strong><?= number($activeBroadcasts) ?></strong>

</li>

<li class="list-group-item d-flex justify-content-between">

<span>Completed</span>

<strong><?= number($completedBroadcasts) ?></strong>

</li>

</ul>

</div>

</div>

</div>

</div>
<script>

function refreshDashboard()
{
    fetch('progress.php')
        .then(function(response){

            return response.json();

        })
        .then(function(data){

            if(!data.success){
                return;
            }

            /*
             * Future:
             * Update statistics cards,
             * progress bars,
             * running broadcasts.
             */

        })
        .catch(function(error){

            console.error(error);

        });
}

setInterval(
    refreshDashboard,
    5000
);

</script>

<?php

include __DIR__ . '/includes/footer.php';
?>