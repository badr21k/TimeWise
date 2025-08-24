<style>
  :root{
    --tw-navy:#09194D;
    --tw-gold:#B59E5F;
    --tw-coral:#D97F76;
    --tw-lilac:#8D77AB;
    --tw-surface:#FFFFFF;
    --tw-track:#E4E4EF;
  }
  .tw-logo{
    --logo-w: 140px; --text-size: 18px; --track-h: 4px; --dot: 8px;
    width:var(--logo-w); display:flex; flex-direction:column; align-items:center; gap:6px;
  }
  .tw-logo__word{ font-weight:800; letter-spacing:.12em; color:var(--tw-navy); font-size:var(--text-size);}
  .tw-logo__timeline{ width:100%; margin-top:4px; position:relative; }
  .tw-logo__track{ height:var(--track-h); width:100%; border-radius:999px; background:var(--tw-track); position:relative; overflow:hidden; }
  .tw-logo__progress{ position:absolute; inset:0 auto 0 0; width:68%; background:linear-gradient(90deg,var(--tw-navy),var(--tw-lilac)); border-radius:999px; background-size:200% 100%; animation:tw-flow 2.4s linear infinite; }
  @keyframes tw-flow { to{ background-position:-200% 0; } }
  .tw-logo__marks{ position:absolute; left:0; top:50%; transform:translateY(-50%); width:100%; display:flex; justify-content:space-between; }
  .tw-logo__dot{ width:var(--dot); height:var(--dot); border-radius:50%; box-shadow:0 0 0 3px #fff; }
  .tw-logo__dot--1{ background:var(--tw-navy); animation:tw-pulse 2.4s ease-in-out infinite; }
  .tw-logo__dot--2{ background:var(--tw-gold); }
  .tw-logo__dot--3{ background:var(--tw-coral); }
  .tw-logo__dot--4{ background:var(--tw-lilac); }
  @keyframes tw-pulse{ 0%,100%{ transform:scale(1);} 50%{ transform:scale(1.12);} }
</style>

<!-- TimeWise Logo -->
<div class="tw-logo" aria-label="TimeWise logo">
  <div class="tw-logo__word">TIMEWISE</div>
  <div class="tw-logo__timeline">
    <div class="tw-logo__track">
      <div class="tw-logo__progress"></div>
    </div>
    <div class="tw-logo__marks">
      <span class="tw-logo__dot tw-logo__dot--1"></span>
      <span class="tw-logo__dot tw-logo__dot--2"></span>
      <span class="tw-logo__dot tw-logo__dot--3"></span>
      <span class="tw-logo__dot tw-logo__dot--4"></span>
    </div>
  </div>
</div>
