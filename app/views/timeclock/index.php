<?php require 'app/views/templates/header.php'; ?>

<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-7">
      <div class="card shadow-sm">
        <div class="card-body">
          <h1 class="h4 mb-3"><i class="fas fa-user-check me-2 text-primary"></i>Time Clock</h1>

          <div id="status" class="alert alert-info mb-4">Loading status...</div>

          <div class="d-flex flex-wrap gap-2 mb-3">
            <button class="btn btn-success" id="btnClockIn"><i class="fas fa-play me-1"></i>Clock In</button>
            <button class="btn btn-warning" id="btnBreakStart"><i class="fas fa-coffee me-1"></i>Start Break</button>
            <button class="btn btn-secondary" id="btnBreakEnd"><i class="fas fa-mug-hot me-1"></i>End Break</button>

            <div class="ms-auto"></div>

            <div class="input-group" style="max-width: 280px;">
              <label class="input-group-text" for="satisfaction">Satisfaction</label>
              <select id="satisfaction" class="form-select">
                <option value="" selected>--</option>
                <option value="1">1 - Very Dissatisfied</option>
                <option value="2">2 - Dissatisfied</option>
                <option value="3">3 - Neutral</option>
                <option value="4">4 - Satisfied</option>
                <option value="5">5 - Very Satisfied</option>
              </select>
              <button class="btn btn-danger" id="btnClockOut"><i class="fas fa-stop me-1"></i>Clock Out</button>
            </div>
          </div>

          <small class="text-muted">Tip: You can set satisfaction before clocking out, or leave it blank.</small>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
async function api(action, body = null) {
  const url = `/timeclock/api?a=${encodeURIComponent(action)}`;
  const opts = { method: body ? 'POST' : 'GET' };
  if (body) {
    const form = new FormData();
    for (const k in body) form.append(k, body[k]);
    opts.body = form;
  }
  const res = await fetch(url, opts);
  if (!res.ok) throw new Error(`Request failed (${res.status})`);
  const data = await res.json();
  if (data && data.error) throw new Error(data.error);
  return data;
}

function setStatus(text, type = 'info') {
  const el = document.getElementById('status');
  el.className = `alert alert-${type} mb-4`;
  el.textContent = text;
}

function updateButtons(state) {
  const clockedIn = state.clocked_in;
  const onBreak = state.on_break;
  document.getElementById('btnClockIn').disabled = clockedIn;
  document.getElementById('btnBreakStart').disabled = !clockedIn || onBreak;
  document.getElementById('btnBreakEnd').disabled = !clockedIn || !onBreak;
  document.getElementById('btnClockOut').disabled = !clockedIn || onBreak;
}

async function refreshStatus() {
  try {
    const s = await api('status');
    if (!s.clocked_in) {
      setStatus('You are currently clocked out.');
    } else {
      let msg = `Clocked in at ${new Date(s.entry.clock_in).toLocaleString()}. `;
      msg += s.on_break ? 'On break.' : 'Not on break.';
      if (s.entry.total_break_minutes) msg += ` Total break: ${s.entry.total_break_minutes} min.`;
      if (s.entry.satisfaction) msg += ` Satisfaction: ${s.entry.satisfaction}/5.`;
      setStatus(msg, 'success');
    }
    updateButtons(s);
  } catch (e) {
    setStatus(e.message || 'Failed to load status', 'danger');
  }
}

async function doClockIn() {
  try {
    await api('clock.in');
    await refreshStatus();
  } catch (e) { setStatus(e.message, 'danger'); }
}
async function doBreakStart() {
  try {
    await api('break.start');
    await refreshStatus();
  } catch (e) { setStatus(e.message, 'danger'); }
}
async function doBreakEnd() {
  try {
    await api('break.end');
    await refreshStatus();
  } catch (e) { setStatus(e.message, 'danger'); }
}
async function doClockOut() {
  try {
    const sat = document.getElementById('satisfaction').value;
    const payload = {};
    if (sat !== '') payload.satisfaction = sat;
    await api('clock.out', payload);
    document.getElementById('satisfaction').value = '';
    await refreshStatus();
  } catch (e) { setStatus(e.message, 'danger'); }
}

document.getElementById('btnClockIn').addEventListener('click', doClockIn);
document.getElementById('btnBreakStart').addEventListener('click', doBreakStart);
document.getElementById('btnBreakEnd').addEventListener('click', doBreakEnd);
document.getElementById('btnClockOut').addEventListener('click', doClockOut);

refreshStatus();
</script>

<?php require 'app/views/templates/footer.php'; ?>
