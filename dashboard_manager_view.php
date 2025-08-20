<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Rukovodilac</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Dobrodošao, <?php echo htmlspecialchars($user['name']); ?>!</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Kreiranje grupe -->
    <h3>Kreiraj grupu zadataka</h3>
    <form method="POST" class="mb-3">
        <div class="mb-3">
            <label for="group_name" class="form-label">Naziv grupe</label>
            <input type="text" class="form-control" id="group_name" name="group_name" required>
        </div>
        <button type="submit" name="kreiraj_grupu" class="btn btn-primary">Kreiraj grupu</button>
    </form>

        <!-- Izmena/brisanje grupe -->
    <h3>Izmeni grupu zadataka</h3>
    <form method="POST" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <select class="form-control" id="group_id" name="group_id" required>
                    <?php foreach ($groups as $group): ?>
                        <option value="<?php echo $group['id']; ?>"><?php echo htmlspecialchars($group['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Novi naziv">
            </div>
        <button type="submit" name="izmeni_grupu" class="btn btn-success">Izmeni grupu</button>
        <button type="submit" name="obrisi_grupu" class="btn btn-danger">Obrisi grupu</button>
        </div>
    </form>

    <!-- Kreiranje zadatka -->
    <h3>Kreiraj zadatak</h3>
    <form method="POST" enctype="multipart/form-data" class="mb-3">
        <div class="mb-3">
            <label for="title" class="form-label">Naslov</label>
            <input type="text" class="form-control" id="title" name="title" maxlength="191" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Opis</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>
        <div class="mb-3">
            <label for="group_id" class="form-label">Grupa</label>
            <select class="form-control" id="group_id" name="group_id" required>
                <?php foreach ($groups as $group): ?>
                    <option value="<?php echo $group['id']; ?>"><?php echo htmlspecialchars($group['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="executors" class="form-label">Izvršioci</label>
            <select class="form-control" id="executors" name="executors[]" multiple>
                <?php foreach ($executors as $executor): ?>
                    <option value="<?php echo $executor['id']; ?>"><?php echo htmlspecialchars($executor['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="deadline" class="form-label">Rok</label>
            <input type="datetime-local" class="form-control" id="deadline" name="deadline" required>
        </div>
        <div class="mb-3">
            <label for="priority" class="form-label">Prioritet (1-10)</label>
            <input type="number" class="form-control" id="priority" name="priority" min="1" max="10" required>
        </div>
        <div class="mb-3">
            <label for="prilozi" class="form-label">Prilozi</label>
            <input type="file" class="form-control" id="prilozi" name="prilozi[]" multiple>
        </div>
        <button type="submit" name="kreiraj_zadatak" class="btn btn-primary">Kreiraj zadatak</button>
    </form>

    <!-- Filteri -->
    <h3>Pretraži zadatke</h3>
    <form method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <label for="deadline_od" class="form-label">Rok od</label>
                <input type="datetime-local" class="form-control" id="deadline_od" name="deadline_od" value="<?php echo htmlspecialchars($filter_deadline_od); ?>">
            </div>
            <div class="col-md-3">
                <label for="deadline_do" class="form-label">Rok do</label>
                <input type="datetime-local" class="form-control" id="deadline_do" name="deadline_do" value="<?php echo htmlspecialchars($filter_deadline_do); ?>">
            </div>
            <div class="col-md-3">
                <label for="priority" class="form-label">Prioritet</label>
                <input type="number" class="form-control" id="priority" name="priority" min="1" max="10" value="<?php echo $filter_priority ?: ''; ?>">
            </div>
            <div class="col-md-3">
                <label for="executor_id" class="form-label">Izvršilac</label>
                <select class="form-control" id="executor_id" name="executor_id">
                    <option value="0">Svi izvršioci</option>
                    <?php foreach ($executors as $executor): ?>
                        <option value="<?php echo $executor['id']; ?>" <?php echo $filter_executor == $executor['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($executor['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="title" class="form-label">Naslov</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($filter_title); ?>">
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
    <h3>Zadaci</h3>
    <?php if (empty($tasks)): ?>
        <p>Nema zadataka.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Naslov</th>
                    <th>Opis</th>
                    <th>Grupa</th>
                    <th>Rok</th>
                    <th>Prioritet</th>
                    <th>Status</th>
                    <th>Izvršioci</th>
                    <th>Prilozi</th>
                    <th>Akcije</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($task['title']); ?></td>
                        <td><?php echo htmlspecialchars($task['description']); ?></td>
                        <td><?php echo htmlspecialchars($task['group_name']); ?></td>
                        <td><?php echo htmlspecialchars($task['deadline']); ?></td>
                        <td><?php echo htmlspecialchars($task['priority']); ?></td>
                        <td><?php echo htmlspecialchars($task['status']); ?></td>
                        <td>
                            <?php
                            $query = "SELECT u.name FROM veza_izvrsilaczadatak vz JOIN korisnici u ON vz.user_id = u.id WHERE vz.task_id = ?";
                            $stmt = $db->prepare($query);
                            $stmt->bind_param("i", $task['id']);
                            $stmt->execute();
                            $executors_list = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                            echo implode(", ", array_column($executors_list, 'name'));
                            ?>
                        </td>
                        <td>
                            <?php if (!empty($attachments[$task['id']])): ?>
                                <?php foreach ($attachments[$task['id']] as $attachment): ?>
                                    <a href="<?php echo htmlspecialchars($attachment['file_path']); ?>" target="_blank">Prilog</a><br>
                                <?php endforeach; ?>
                            <?php else: ?>
                                Nema priloga
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($task['status'] !== 'completed' && $task['status'] !== 'canceled'): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                    <button type="submit" name="zavrsi_zadatak" class="btn btn-success btn-sm">Završi</button>
                                    <button type="submit" name="otkazi_zadatak" class="btn btn-danger btn-sm">Otkaži</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <!-- Komentari -->
                    <tr>
                        <td colspan="9">
                            <h5>Komentari</h5>
                            <div id="comments_<?php echo $task['id']; ?>">
                                <?php if (!empty($comments[$task['id']])): ?>
                                    <?php foreach ($comments[$task['id']] as $comment): ?>
                                        <p>
                                            <strong><?php echo htmlspecialchars($comment['name']); ?> (<?php echo $comment['created_at']; ?>):</strong>
                                            <?php echo htmlspecialchars($comment['comment']); ?>
                                            <button class="btn btn-danger btn-sm delete-comment" data-comment-id="<?php echo $comment['id']; ?>" data-task-id="<?php echo $task['id']; ?>">Obriši</button>
                                        </p>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Nema komentara.</p>
                                <?php endif; ?>
                            </div>
                            <form class="comment-form" data-task-id="<?php echo $task['id']; ?>">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <div class="input-group mb-3">
                                    <input type="text" name="comment" class="form-control" placeholder="Dodaj komentar" required>
                                    <button type="submit" class="btn btn-primary">Pošalji</button>
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
<script>
    $(document).ready(function() {
    // AJAX za dodavanje komentara
    $('.comment-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var task_id = form.find('input[name="task_id"]').val();
        var comment = form.find('input[name="comment"]').val();
        
        $.ajax({
            url: 'add_comment.php',
            type: 'POST',
            data: { task_id: task_id, comment: comment },
            success: function(response) {
                $('#comments_' + task_id).append('<p><strong><?php echo htmlspecialchars($user['name']); ?> (' + new Date().toLocaleString() + '):</strong> ' + $('<div/>').text(comment).html() + '</p>');
                form.find('input[name="comment"]').val('');
            },
            error: function(xhr, status, error) {
                alert('Greška pri dodavanju komentara: ' + xhr.responseText);
            }        
        });
    });
    // AJAX za brisanje komentara
    $('.delete-comment').on('click', function() {
        var comment_id = $(this).data('comment-id');
        var task_id = $(this).data('task-id');
        
        $.ajax({
            url: 'delete_comment.php',
            type: 'POST',
            data: { comment_id: comment_id },
            success: function() {
                $('#comments_' + task_id).find('button[data-comment-id="' + comment_id + '"]').parent().remove();
            },
            error: function() {
                alert('Greška pri brisanju komentara.');
            }
        });
    });
});
</script>
</body>
</html>