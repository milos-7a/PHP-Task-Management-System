
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Executor</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Dobrodošao, <?php echo htmlspecialchars($user['name']); ?>!</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Filteri -->
    <form method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <label for="deadline" class="form-label">Rok</label>
                <input type="datetime-local" class="form-control" id="deadline" name="deadline" value="<?php echo htmlspecialchars($filter_deadline); ?>">
            </div>
            <div class="col-md-3">
                <label for="manager_id" class="form-label">Rukovodilac</label>
                <select class="form-control" id="manager_id" name="manager_id">
                    <option value="0">Svi rukovodioci</option>
                    <?php foreach ($managers as $manager): ?>
                        <option value="<?php echo $manager['id']; ?>" <?php echo $filter_manager == $manager['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($manager['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="user_id" class="form-label">Član</label>
                <select class="form-control" id="user_id" name="user_id">
                    <option value="0">Svi članovi</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo $user['id']; ?>" <?php echo $filter_user == $user['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($user['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="sort" class="form-label">Sortiraj po</label>
                <select class="form-control" id="sort" name="sort">
                    <option value="deadline" <?php echo $sort == 'deadline' ? 'selected' : ''; ?>>Rok</option>
                    <option value="title" <?php echo $sort == 'title' ? 'selected' : ''; ?>>Naslov</option>
                    <option value="priority" <?php echo $sort == 'priority' ? 'selected' : ''; ?>>Prioritet</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Primeni filtere</button>
    </form>

    <!-- Lista zadataka -->
    <h3>Vaši zadaci</h3>
    <?php if (empty($tasks)): ?>
        <p>Nema zadataka dodeljenih vama.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Naslov</th>
                    <th>Opis</th>
                    <th>Rok</th>
                    <th>Prioritet</th>
                    <th>Status</th>
                    <th>Rukovodilac</th>
                    <th>Akcije</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($task['title']); ?></td>
                        <td><?php echo htmlspecialchars($task['description']); ?></td>
                        <td><?php echo htmlspecialchars($task['deadline']); ?></td>
                        <td><?php echo htmlspecialchars($task['priority']); ?></td>
                        <td><?php echo htmlspecialchars($task['status']); ?></td>
                        <td><?php echo htmlspecialchars($task['manager_name']); ?></td>
                        <td>
                            <?php if ($task['status'] !== 'completed' && $task['status'] !== 'canceled' && !$task['completed']): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                    <button type="submit" name="zavrsi_zadatak" class="btn btn-success btn-sm">Završi</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <!-- Komentari -->
                    <tr>
                        <td colspan="7">
                            <h5>Komentari</h5>
                            <?php if (isset($comments[$task['id']]) && !empty($comments[$task['id']])): ?>
                                <?php foreach ($comments[$task['id']] as $comment): ?>
                                    <p><strong><?php echo htmlspecialchars($comment['name']); ?> (<?php echo $comment['created_at']; ?>):</strong> 
                                       <?php echo htmlspecialchars($comment['comment']); ?></p>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Nema komentara.</p>
                            <?php endif; ?>
                            <form method="POST">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <div class="input-group mb-3">
                                    <input type="text" name="comment" class="form-control" placeholder="Dodaj komentar" required>
                                    <button type="submit" name="dodaj_komentar" class="btn btn-primary">Pošalji</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <a href="logout.php" class="btn btn-primary">Odjavi se</a>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>