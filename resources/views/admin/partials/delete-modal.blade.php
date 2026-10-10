{{-- Shared delete confirmation. Buttons: <button type="button" class="btn btn-ghost js-delete" data-action="{{ route(...destroy) }}" data-name="..."> --}}
<dialog class="del-modal" id="deleteModal" aria-labelledby="delTitle" aria-describedby="delDesc">
  <form method="POST" id="deleteForm" action="">
    @csrf @method('DELETE')
    <div class="del-body">
      <div class="del-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v6M14 11v6"/></svg>
      </div>
      <h3 id="delTitle">Delete this entry?</h3>
      <p id="delDesc">You are about to delete <b id="delName"></b>. This can't be undone.</p>
    </div>
    <div class="del-actions">
      <button type="button" class="btn btn-ghost" id="delCancel">Cancel</button>
      <button type="submit" class="btn-danger" id="delConfirm">Delete</button>
    </div>
  </form>
</dialog>
<script>
(function () {
  var modal = document.getElementById('deleteModal'), form = document.getElementById('deleteForm'),
      nameEl = document.getElementById('delName'), confirmBtn = document.getElementById('delConfirm'),
      cancelBtn = document.getElementById('delCancel');
  document.querySelectorAll('.js-delete').forEach(function (btn) {
    btn.addEventListener('click', function () {
      form.action = btn.dataset.action;
      nameEl.textContent = btn.dataset.name;
      confirmBtn.disabled = false; confirmBtn.textContent = 'Delete';
      modal.showModal(); cancelBtn.focus();
    });
  });
  cancelBtn.addEventListener('click', function () { modal.close(); });
  modal.addEventListener('click', function (e) { if (e.target === modal) modal.close(); });
  form.addEventListener('submit', function () { confirmBtn.disabled = true; confirmBtn.textContent = 'Deleting…'; });
})();
</script>
