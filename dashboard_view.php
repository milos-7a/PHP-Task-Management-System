
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Dobrodošao, <?php echo htmlspecialchars($user['name']); ?>!</h2>

    <?php if ($user['role'] !== 'executor'): ?>
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
    
    <?php endif; ?>
    <?php include 'partials/taskmodal.html' ?>
    <!-- Ukljucivanje filtera -->
    <?php include 'partials/tasks_filters.php'; ?>
    <a href="logout.php" class="btn btn-primary">Odjavi se</a>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
    $('.comment-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var task_id = form.find('input[name="task_id"]').val();
        var comment = form.find('input[name="comment"]').val();
        
        $.ajax({
            url: 'includes/add_comment.php',
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
    $('.delete-comment').on('click', function() {
        var comment_id = $(this).data('comment-id');
        var task_id = $(this).data('task-id');
        
        $.ajax({
            url: 'includes/delete_comment.php',
            type: 'POST',
            data: { comment_id: comment_id },
            success: function() {
                $('#comments_' + task_id).find('button[data-comment-id="' + comment_id + '"]').closest('p').remove();
            },
            error: function() {
                alert('Greška pri brisanju komentara.');
            }
        });
    });
    $(document).on("click", ".delete-task", function () {
        if (!confirm("Da li ste sigurni da želite da obrišete zadatak?")) return;

        var taskId = $(this).data("task-id");

        $.ajax({
            url: 'includes/delete_task.php',
            type: "POST",
            data: { task_id: taskId },
            success: function (response) {
                alert("Zadatak obrisan!");
                location.reload(); 
            },
            error: function () {
                alert("Greška pri brisanju!");
                error_log(url);
            }
        });
    });

    // Klik na "Izmeni"
    $(document).on("click", ".modify-task", function () {
        $("#task_id").val($(this).data("task-id"));
        $("#task_title").val($(this).data("title"));
        $("#task_description").val($(this).data("description"));
        $("#task_deadline").val($(this).data("deadline"));
        $("#task_priority").val($(this).data("priority"));
        $("#task_status").val($(this).data("status"));
        $("#taskModal").modal("show");
    });

    // Slanje forme
    $("#taskForm").on("submit", function (e) {
        e.preventDefault();

        $.ajax({
            url: "includes/update_task.php",
            type: "POST",
            data: $(this).serialize(),
            success: function (response) {
                alert("Zadatak uspešno izmenjen!");
                location.reload();
            },
            error: function (xhr) {
                alert("Greška pri izmeni zadatka!");
                console.log(xhr.responseText);
            }
        });
    });

});
</script>
</body>
</html>