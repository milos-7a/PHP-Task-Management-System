
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
    <?php if ($user['role'] === 'admin'): ?>
        <h2>Korisnici</h2>
        <button class="btn btn-sm btn-primary create-user-btn"
            data-bs-toggle="modal" data-bs-target="#userModal">
            Kreiraj korisnika
        </button>
        <?php foreach ($roles as $uloge): ?>
            <h4 class="mt-4"><?= ucfirst($uloge) ?>:</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Ime</th>
                        <th>Email</th>
                        <th>Telefon</th>
                        <th>Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($korisnici as $korisnik): ?>
                        <?php if ($korisnik['role'] === $uloge): ?>
                        <tr>
                            <td><?= htmlspecialchars($korisnik['name']) ?></td>
                            <td><?= htmlspecialchars($korisnik['email']) ?></td>
                            <td><?= htmlspecialchars($korisnik['phone']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary edit-user-btn"
                                    data-user='<?= json_encode($korisnik) ?>'
                                    data-bs-toggle="modal" data-bs-target="#userModal">
                                    <i class="bi bi-pencil-square text-dark"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-user-btn"
                                    data-user='<?= json_encode($korisnik) ?>'>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
        <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="userForm">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Dodaj korisnika</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                <input type="hidden" id="user_id" name="user_id">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" class="form-control" name="username" id="username" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
                <div class="mb-3">
                    <label>Ime i prezime</label>
                    <input type="text" class="form-control" name="name" id="name" required>
                </div>
                <div class="mb-3">
                    <label>Telefon</label>
                    <input type="text" class="form-control" name="phone" id="phone">
                </div>
                <div class="mb-3">
                    <label>Datum rođenja</label>
                    <input type="date" class="form-control" name="birth_date" id="birth_date">
                </div>
                <div class="mb-3">
                    <label>Uloga</label>
                    <select class="form-control" name="role" id="role" required>
                    <option value="admin">Admin</option>
                    <option value="manager">Menadžer</option>
                    <option value="executor">Izvršilac</option>
                    </select>
                </div>
                </div>
                <div class="modal-footer">
                <button type="submit" id="userModalSaveBtn" class="btn btn-primary">Sačuvaj</button>
                </div>
            </div>
            </form>
        </div>
        </div>
        <?php endif; ?>

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
    <?php include 'partials/taskmodal.php' ?>
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
    $('.edit-comment').on('click', function() {
        var comment_id = $(this).data('comment-id');
        var staricomment = $(this).data('comment');
        var task_id = $(this).data('task-id');
        let comment = prompt("Edit comment:", staricomment);
        $.ajax({
            url: 'includes/edit_comment.php',
            type: 'POST',
            data: { comment_id: comment_id, comment:comment },
            success: function() {
            var $p = $('#comments_' + task_id)
                .find('button[data-comment-id="' + comment_id + '"]')
                .closest('p');
            var escapedComment = $('<div/>').text(comment).html();
            $p.html('<strong>' + '<?php echo htmlspecialchars($user["name"]); ?>' + 
                   ' (' + new Date().toLocaleString() + '):</strong> ' + 
                   escapedComment);
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

    $(document).on("click", ".modify-task", function () {
        const btn = $(this);

        const taskId = btn.data("task-id");
        const title = btn.data("title");
        const description = btn.data("description");
        const deadline = btn.data("deadline");
        const priority = btn.data("priority");
        const status = btn.data("status");
        const groupId = btn.data("group-id");
        const manager_id = btn.data("manager-id"); 
        const attachments = btn.data("attachments") || [];
        const executors = btn.data("executors") || [];
        const user_id = btn.data("user-id");

        $("#task_id").val(taskId);
        $("#task_title").val(title);
        $("#task_description").val(description);
        $("#task_deadline").val(deadline);
        $("#task_priority").val(priority);
        $("#task_status").val(status);
        $("#task_group_id").val(groupId);
        $("#task_manager_id").val(manager_id);
        $("#task_user_id").val(user_id);
        $("#task_executors").val(executors);

        const attachmentsDiv = $("#existing_attachments");
        attachmentsDiv.html(""); 
        attachments.forEach(filePath => {
            const fileName = filePath.split("/").pop();
            const html = `
                <div class="d-flex align-items-center mb-1" data-file-path="${filePath}">
                    <a href="${filePath}" target="_blank" class="me-2">${fileName}</a>
                    <button type="button" class="btn btn-sm btn-danger remove-attachment">Obriši</button>
                </div>
            `;
            attachmentsDiv.append(html);
        });

        $("#taskModal").modal("show");
    });

    // Brisanje postojećeg priloga
    $(document).on("click", ".remove-attachment", function () {
        const wrapper = $(this).closest("div[data-file-path]");
        const filePath = wrapper.data("file-path");

        if (confirm("Da li ste sigurni da želite da obrišete ovaj prilog?")) {
            $.ajax({
                url: "includes/delete_attachment.php",
                type: "POST",
                data: { file_path: filePath },
                success: function (response) {
                    wrapper.remove();
                },
                error: function () {
                    alert("Došlo je do greške prilikom brisanja priloga.");
                }
            });
        }
    });

    $("#taskForm").on("submit", function (e) {
        e.preventDefault();
            const formData = new FormData(this);
            formData.append("user_id", "<?php echo $user_id; ?>");
            $.ajax({
                url: "includes/update_task.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    console.log(response);
                    $("#taskModal").modal("hide");
                    location.reload();
                },
                error: function(err){
                    console.error(err);
                    alert("Greška prilikom slanja fajlova.");
                }
            });
    });

    let isEditMode = false;

    function openAddUserModal() {
        isEditMode = false;
        $("#userModalLabel").text("Dodaj korisnika");
        $("#userModalSaveBtn").text("Dodaj");
        $("#userForm")[0].reset();
        $("#user_id").val(""); 
        $("#userModal").modal("show");
    }

    function openEditUserModal(user) {
        isEditMode = true;
        $("#userModalLabel").text("Izmeni korisnika");
        $("#userModalSaveBtn").text("Sačuvaj");

        $("#user_id").val(user.id);
        $("#username").val(user.username);
        $("#email").val(user.email);
        $("#password").val(""); 
        $("#name").val(user.name);
        $("#phone").val(user.phone);
        $("#birth_date").val(user.birth_date);
        $("#role").val(user.role);

        $("#userModal").modal("show");
    }
    
    $(".edit-user-btn").click(function(){
        const user = $(this).data("user");
        openEditUserModal(user);
    });
    $(".create-user-btn").click(function(){
        openAddUserModal();
    });

    $(".delete-user-btn").click(function(){
        if (!confirm("Da li ste sigurni da želite da obrišete korisnika?")) return;

        const user = $(this).data("user");
        var user_id = user.id;

        $.ajax({
            url: 'includes/delete_user.php',
            type: "POST",
            data: { user_id },
            success: function (response) {
                alert("Korisnik obrisan!");
                location.reload(); 
            },
            error: function () {
                alert("Greška pri brisanju!");
                error_log(url);
            }
        });
    });

    $("#userForm").on("submit", function(e) {
        e.preventDefault();

        let formData = $(this).serialize();

        $.ajax({
            url: isEditMode ? "includes/update_user.php" : "includes/create_user.php",
            type: "POST",
            data: formData,
            success: function(response) {
                console.log(response);
                $("#userModal").modal("hide");
                location.reload();
            },
            error: function(err) {
                console.error(err);
                alert("Greška prilikom čuvanja korisnika.");
            }
        });
    });

});
</script>
</body>
</html>