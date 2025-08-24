
    <!-- Lista zadataka -->
    <h3>Zadaci</h3>
       <?php if (empty($tasks)): ?>
        <p>Nema zadataka.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <th> <?php if($role !== 'executor'): ?>
                            <button class="btn btn-sm btn-outline-danger delete-task" 
                                    data-task-id="<?php echo $task['id']; ?>" 
                                    title="Obriši">
                                <i class="bi bi-trash"></i>
                            </button>
                            <?php endif; ?>
                        </th>
                        <th>Naslov</th>
                        <th>Opis</th>
                        <th>Grupa</th>
                        <th>Rok</th>
                        <th>Prioritet</th>
                        <th>Status</th>
                        <th>Rukovodilac</th>
                        <th>Izvršioci</th>
                        <th>Prilozi</th>
                        <th>Akcije</th>
                    </tr>
                    <tr>
                        <td> <?php if($role !== 'executor'): ?>
                            <button class="btn btn-sm btn-outline-primary modify-task" 
                                    data-task-id="<?php echo $task['id']; ?>"
                                    data-title="<?php echo htmlspecialchars($task['title']); ?>"
                                    data-description="<?php echo htmlspecialchars($task['description']); ?>"
                                    data-deadline="<?php echo htmlspecialchars($task['deadline']); ?>"
                                    data-priority="<?php echo htmlspecialchars($task['priority']); ?>"
                                    data-status="<?php echo htmlspecialchars($task['status']); ?>"
                                    data-group-id="<?php echo htmlspecialchars($task['group_id']); ?>"
                                    data-group-name="<?php echo htmlspecialchars($task['group_name']); ?>"
                                    data-manager-id="<?php echo htmlspecialchars($task['manager_id']); ?>"
                                    data-user-id="<?php echo htmlspecialchars($user_id); ?>"
                            <?php   $taskfilepath = array_column($attachments[$task['id']], 'file_path');
                                    $user_ids = array_column(getExecutorsList($db, (int)$task['id']), 'user_id');?>
                                    data-attachments="<?php echo htmlspecialchars(json_encode($taskfilepath)); ?>"    
                                    data-executors="<?php echo htmlspecialchars(json_encode($user_ids)); ?>"                                
                                    title="Izmeni">
                                <i class="bi bi-pencil-square text-dark"></i>
                            </button>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($task['title']); ?></td>
                        <td><?php echo htmlspecialchars($task['description']); ?></td>
                        <td><?php echo htmlspecialchars($task['group_name']); ?></td>
                        <td><?php echo htmlspecialchars($task['deadline']); ?></td>
                        <td><?php echo htmlspecialchars($task['priority']); ?></td>
                        <td><?php echo htmlspecialchars($task['status']); ?></td>
                        <td><?php echo htmlspecialchars($task['manager_name']); ?></td>
                        <td>
                            <?php
                            $executors_list = getExecutorsList($db, (int)$task['id']);
                            if (empty($executors_list)) {
                                echo "Nema izvršilaca";
                            } else {
                                $names = [];
                                foreach ($executors_list as $executor) {
                                    $name = htmlspecialchars($executor['name']);
                                    if ($executor['completed'] == 1) {
                                        $names[] = "$name (obavio)";
                                    } else {
                                        $names[] = $name;
                                    }
                                }
                                echo implode(", ", $names);
                            }
                            ?>
                        </td>
                        <td>
                            <?php if (!empty($attachments[$task['id']])): ?>
                                <?php foreach ($attachments[$task['id']] as $attachment):?>
                                    <a href="<?php echo htmlspecialchars($attachment['file_path']); ?>" target="_blank">Prilog</a><br>
                                <?php endforeach;?>
                            <?php else: ?>
                                Nema priloga
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($task['status'] == 'open'): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                    <?php if ($user['role'] == 'executor' && isTaskExecutor($db, $user_id, $task['id'])): ?>
                                        <?php if (!hasUserCompletedTask($db, (int)$task['id'], $user_id)): ?>
                                            <button type="submit" name="zavrsi_zadatak" class="btn btn-success btn-sm"><i class="bi bi-clipboard2-check"></i></button>
                                        <?php else: ?>
                                            <span class="text-success">Zadatak je predat</span>
                                        <?php endif; ?>
                                    <?php elseif (($user['role'] == 'manager' && ($task['manager_id'] == $user_id)) || $user['role'] == 'admin'): ?>
                                        <button type="submit" name="zavrsi_zadatak" class="btn btn-success btn-sm"><i class="bi bi-clipboard2-check"></i></button>
                                        <button type="submit" name="otkazi_zadatak" class="btn btn-danger btn-sm"><i class="bi bi-clipboard-x"></i></button>
                                    <?php endif; ?>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <!-- Komentari -->
                    <tr>
                        <td colspan="7">
                            <h5>Komentari</h5>
                            <div id="comments_<?php echo $task['id']; ?>">
                                <?php if (!empty($comments[$task['id']])): ?>
                                    <?php foreach ($comments[$task['id']] as $comment): ?>
                                        <p style="padding:1em;">
                                            <strong><?php echo htmlspecialchars($comment['name']); ?> (<?php echo $comment['created_at']; ?>):</strong>
                                            <?php echo htmlspecialchars($comment['comment']); ?>        
                                            <?php if ($user['role'] !== 'executor'): ?>
                                            <button class="btn btn-danger btn-sm delete-comment" 
                                                    data-comment-id="<?php echo $comment['id']; ?>" 
                                                    data-task-id="<?php echo $task['id']; ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            <?php endif; ?>
                                        <?php if($user['role'] == 'admin'): ?>
                                            <button class="btn btn-secondary btn-sm edit-comment" 
                                                    data-comment-id="<?php echo $comment['id']; ?>" 
                                                    data-comment="<?php echo $comment['comment'] ?>"
                                                    data-task-id="<?php echo $task['id']; ?>">
                                                    <i class="bi bi-pencil"></i>
                                            </button>
                                        </p>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Nema komentara.</p>
                                <?php endif; ?>
                            </div>
                            <?php if(isTaskExecutor($db, $user_id, $task['id']) || $role == 'admin' || $role == 'manager'): ?>
                            <form class="comment-form" data-task-id="<?php echo $task['id']; ?>">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <div class="input-group mb-3">
                                    <input type="text" name="comment" class="form-control" placeholder="Dodaj komentar" required>
                                    <button type="submit" class="btn btn-primary">Pošalji</button>
                                </div>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

     <!-- Filteri -->
    <h3>Filteri</h3>
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
                    <?php foreach ($users as $member): ?>
                        <option value="<?php echo $member['id']; ?>" <?php echo $filter_user == $member['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($member['name']); ?>
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