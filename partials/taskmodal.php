<div class="modal fade" id="taskModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Izmena zadatka</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="taskForm" enctype="multipart/form-data" method="POST">
          <input type="hidden" name="task_id" id="task_id">

          <div class="mb-3">
            <label class="form-label">Naslov</label>
            <input type="text" class="form-control" name="title" id="task_title">
          </div>

          <div class="mb-3">
            <label class="form-label">Opis</label>
            <textarea class="form-control" name="description" id="task_description"></textarea>
          </div>

          <div class="mb-3">
              <label for="form-label" class="form-label">Grupa</label>
              <select class="form-control" id="task_group_id" name="group_id" required>
                  <?php foreach ($groups as $group): ?>
                      <option value="<?php echo $group['id']; ?>">
                          <?php echo htmlspecialchars($group['name']); ?>
                      </option>
                  <?php endforeach; ?>
              </select>
          </div>
          <?php if ($role == 'admin'): ?>
          <div class="mb-3">
              <label for="form-label" class="form-label">Rukovodilac</label>
              <select class="form-control" id="task_manager_id" name="manager_id" required>
                  <?php foreach ($managers as $manager): ?>
                      <option value="<?php echo $manager['id']; ?>">
                          <?php echo htmlspecialchars($manager['name']); ?>
                      </option>
                  <?php endforeach; ?>
              </select>
          </div>
          <?php elseif ($role == 'manager'): ?>
            <div class="mb-3">
              <input type="hidden" class="form-control" name="manager_id" id="task_user_id">
          </div>
          <?php endif; ?>
        
          <div class="mb-3">
              <label for="form-label" class="form-label">Izvršioci</label>
              <select class="form-control" id="task_executors" name="executors[]" multiple>
                  <?php foreach ($executors as $executor): ?>
                      <option value="<?php echo $executor['id']; ?>">
                          <?php echo htmlspecialchars($executor['name']); ?>
                      </option>
                  <?php endforeach; ?>
              </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Rok</label>
            <input type="datetime-local" class="form-control" name="deadline" id="task_deadline">
          </div>
    
          <div class="mb-3">
            <label class="form-label">Prioritet</label>
            <input type="range" class="form-control" name="priority" id="task_priority" min="1" max="10">
          </div>

          <div class="mb-3">
            <label class="form-label">Dodaj nove priloze</label>
            <input type="file" class="form-control" id="task_attachments" name="prilozi[]" multiple>
          </div>

          <div class="mb-3">
            <label class="form-label">Postojeći prilozi</label>
            <div id="existing_attachments">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status" id="task_status">
              <option value="open">Otvoreno</option>
              <option value="completed">Zavrseno</option>
              <option value="canceled">Otkazano</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary">Sačuvaj izmene</button>
        </form>
      </div>
    </div>
  </div>
</div>
