<?php require 'app/views/templates/header.php'; ?>

<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card tw-card animate-in">
        <div class="tw-card__header">
          <h1 class="tw-card__title h4 mb-0">
            <span class="tw-dot" aria-hidden="true"></span>
            Time Clock
          </h1>
          <div class="mt-2">
            <span id="statusPill" class="tw-status tw-status--info">
              <i class="fas fa-rotate fa-spin me-1"></i> Loading status…
            </span>
          </div>
        </div>

        <div class="tw-card__body">
          <div class="tc-clock mb-3">
            <div class="tc-clock__time" id="tcTime">--:--:--</div>
            <div class="tc-clock__date" id="tcDate">Loading date…</div>
            <span class="tc-clock__duration" id="tcDuration" style="display:none">⏱ 00:00:00</span>
            <span class="tc-break-badge" id="tcBreakBadge" style="display:none"><i class="fas fa-coffee"></i> On Break</span>
          </div>
          <div id="statusDetail" class="text-secondary mb-3" style="min-height:1.25rem;">
            <small>Fetching your current shift state…</small>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <div class="tw-mini h-100">
                <div class="d-flex align-items-center justify-content-between mb-1">
                  <strong>Today’s Shift</strong>
                  <span id="todayBadge" class="tw-badge tw-badge--unscheduled" style="display:none;">Unscheduled</span>
                </div>
                <div id="todayShift" class="small text-secondary">Loading…</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="tw-mini h-100">
                <div class="d-flex align-items-center justify-content-between mb-1">
                  <strong>Next Scheduled</strong>
                  <span class="tw-badge tw-badge--upcoming">Upcoming</span>
                </div>
                <div id="nextShift" class="small text-secondary">—</div>
              </div>
            </div>
          </div>

          <div class="alert alert-light border mb-3" id="clockHint" style="display:none;">
            <i class="fas fa-info-circle me-1"></i>
            No scheduled shift today. You can still <strong>Clock In</strong> — it will be saved as an <strong>Unscheduled</strong> shift.
          </div>

          <div class="d-flex flex-wrap tw-actions">
            <button class="btn btn-success" id="btnClockIn">
              <i class="fas fa-play me-1"></i> Clock In
            </button>
            <button class="btn btn-outline-secondary" id="btnBreakStart">
              <i class="fas fa-coffee me-1"></i> Start Break
            </button>
            <button class="btn btn-secondary" id="btnBreakEnd">
              <i class="fas fa-mug-hot me-1"></i> End Break
            </button>

            <div class="ms-auto"></div>

            <button class="btn btn-danger" id="btnClockOut">
              <i class="fas fa-stop me-1"></i> Clock Out
            </button>
          </div>
        </div>
      </div>

      <!-- Today's Shifts List -->
      <div class="card tw-card mt-3">
        <div class="tw-card__header">
          <h2 class="h6 mb-0">Today’s Shifts</h2>
        </div>
        <div class="tw-card__body">
          <div class="table-responsive">
            <table class="table align-middle mb-2">
              <thead>
                <tr>
                  <th>Clock In</th>
                  <th>Clock Out</th>
                  <th>Break</th>
                  <th>Type</th>
                  <th>Hours</th>
                </tr>
              </thead>
              <tbody id="todayList">
                <tr><td colspan="5" class="text-secondary">No entries.</td></tr>
              </tbody>
            </table>
          </div>
          <div class="d-flex justify-content-end">
            <strong>Total Today:&nbsp;<span id="todayTotalHours">0.00</span>&nbsp;hrs</strong>
          </div>
        </div>
      </div>

      <!-- Toast -->
      <div class="position-fixed bottom-0 end-0 p-3" style="z-index:1080">
        <div id="toaster" class="toast align-items-center text-bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div class="toast-body" id="toastMsg">Action completed.</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Busy Overlay -->
<div id="busyOverlay" aria-hidden="true">
  <div class="tw-spinner-wrap">
    <div class="spinner-border" role="status" aria-label="Working…"></div>
    <small id="busyText">Working…</small>
  </div>
</div>

<!-- Satisfaction Modal -->
<div class="modal fade" id="satisfactionModal" tabindex="-1" aria-labelledby="satisfactionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="satisfactionModalLabel">Satisfaction Survey</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-2">How satisfied are you with your day?</p>
        <select id="satisfactionSelect" class="form-select">
          <option value="" selected>— Select —</option>
          <option value="1">1 — Very Dissatisfied</option>
          <option value="2">2 — Dissatisfied</option>
          <option value="3">3 — Neutral</option>
          <option value="4">4 — Satisfied</option>
          <option value="5">5 — Very Satisfied</option>
        </select>
        <small class="text-secondary d-block mt-2">You can skip this if you prefer.</small>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link text-secondary" id="btnSkip">Skip</button>
        <button type="button" class="btn btn-primary" id="btnSubmit">
          <span class="me-1"><i class="fas fa-paper-plane"></i></span> Submit
        </button>
      </div>
    </div>
  </div>
</div>


<?php require 'app/views/templates/footer.php'; ?>
