<?php
// app/views/schedule/my.php
require 'app/views/templates/header.php';
require 'app/views/templates/spinner.php';
?>

<style>
:root{
  --primary:#3b82f6; --primary-hover:#2563eb;
  --gray-50:#f9fafb; --gray-100:#f3f4f6; --gray-200:#e5e7eb;
  --gray-300:#d1d5db; --gray-500:#6b7280; --gray-700:#374151; --gray-900:#111827;
  --shadow:0 1px 3px rgba(0,0,0,.1),0 1px 2px rgba(0,0,0,.06);
  --radius:.5rem;
}
.page{background:var(--gray-50);min-height:100vh}
.h1{font-size:1.5rem;font-weight:800;color:var(--gray-900)}
.sub{color:var(--gray-500)}
.btn{border:1px solid var(--gray-300);background:#fff;padding:.5rem .9rem;border-radius:.5rem;font-weight:600}
.btn:hover{background:var(--gray-100)}
.btn.primary{background:var(--primary);border-color:var(--primary);color:#fff}
.toggle{display:inline-flex;border:1px solid var(--gray-300);border-radius:.6rem;overflow:hidden}
.toggle button{background:#fff;border:0;padding:.45rem .8rem;font-weight:600;color:var(--gray-700)}
.toggle button.active{background:var(--primary);color:#fff}
.grid{background:#fff;border:1px solid var(--gray-200);box-shadow:var(--shadow);border-radius:var(--radius);overflow:hidden}
.head{display:grid;grid-template-columns:220px repeat(7,1fr);background:var(--gray-100);border-bottom:1px solid var(--gray-200)}
.head div{padding:.75rem;font-weight:700;color:var(--gray-700);text-align:center}
.head div:first-child{text-align:left}
.row{display:grid;grid-template-columns:220px repeat(7,1fr);border-bottom:1px solid var(--gray-200);min-height:110px}
.emp{background:var(--gray-50);border-right:1px solid var(--gray-200);padding:.75rem}
.emp .name{font-weight:700}
.cell{position:relative;padding:.5rem;border-right:1px solid var(--gray-200);background:#fff}
.shift{background:var(--primary);color:#fff;border-radius:.45rem;padding:.4rem .5rem;margin-bottom:.35rem;box-shadow:0 1px 2px rgba(0,0,0,.06);font-size:.8rem}
.shift.me{outline:2px solid #1d4ed8}
.small{font-size:.8rem;color:var(--gray-500)}
.summary{font-weight:700}
@media(max-width:1024px){.head,.row{min-width:1000px}}
</style>

<div class="page">
  <div class="container-fluid px-3 px-md-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <div>
        <div class="h1">My Shifts</div>
        <div class="sub">This week first. Browse weeks, compare with your team, and see weekly hours.</div>
      </div>
      <div class="d-flex align-items-center gap-2">
        <div class="toggle" role="tablist" aria-label="Scope">
          <button id="scopeMine" class="active" aria-selected="true">Only my shifts</button>
          <button id="scopeAll" aria-selected="false">All team</button>
        </div>
        <div class="d-flex align-items-center ms-2">
          <button class="btn" id="prevBtn" aria-label="Previous week">◀</button>
          <div class="mx-2 fw-bold" id="weekText"></div>
          <button class="btn" id="nextBtn" aria-label="Next week">▶</button>
        </div>
      </div>
    </div>

    <div class="d-flex align-items-center justify-content-between mb-2">
      <div class="small" id="whoLine"></div>
      <div class="summary"><span id="hoursLabel">My hours</span>: <span id="hoursVal">0.00</span></div>
    </div>

    <div class="grid">
      <div class="head">
        <div>Team member</div>
        <div id="h0">Mon</div><div id="h1">Tue</div><div id="h2">Wed</div>
        <div id="h3">Thu</div><div id="h4">Fri</div><div id="h5">Sat</div><div id="h6">Sun</div>
      </div>
      <div id="gridBody"></div>
    </div>
  </div>
</div>

<?php require 'app/views/templates/footer.php'; ?>

<script>
async function fetchJSON(url, options={}) {
  Spinner?.show();
  try {
    const r = await fetch(url,{headers:{'Content-Type':'application/json'}, ...options});
    const t = await r.text();
    if(!r.ok){ try{ throw new Error(JSON.parse(t).error) }catch{ throw new Error(t) } }
    return JSON.parse(t);
  } finally { Spinner?.hide(); }
}

let me = { employee_id:null, name:'' };
let employees = [];
let weekStart; // YYYY-MM-DD (Monday)
let scope = 'mine'; // 'mine' | 'all'
let allWeekShifts = [];

const mondayOf=(ymd)=>{ const d=new Date(ymd+'T12:00:00'); const dow=d.getDay(); d.setDate(d.getDate()-(dow===0?6:dow-1)); return d.toISOString().slice(0,10); };
const weekDays=(monday)=>{ const labels=['Mon','Tue','Wed','Thu','Fri','Sat','Sun']; const mon=new Date(monday+'T12:00:00'); const a=[]; for(let i=0;i<7;i++){const d=new Date(mon); d.setDate(d.getDate()+i); a.push({ ymd:d.toISOString().slice(0,10), label:labels[i]+', '+d.toLocaleDateString('en-US',{month:'short',day:'numeric'}) });} return a; };
const esc=(t='')=>{ const d=document.createElement('div'); d.textContent=t; return d.innerHTML; };
const sumHours=(list)=>list.reduce((acc,s)=>acc+((new Date(s.end_dt)-new Date(s.start_dt))/36e5||0),0);
const uniq=(arr)=>[...new Set(arr)];

function setWeekHeader(){
  const days = weekDays(weekStart);
  days.forEach((d,i)=>document.getElementById('h'+i).textContent=d.label);
  const mon = new Date(weekStart+'T12:00:00'); const sun=new Date(mon); sun.setDate(sun.getDate()+6);
  const fmt=(x)=>x.toLocaleDateString('en-US',{month:'short',day:'numeric'});
  document.getElementById('weekText').textContent = `${fmt(mon)} – ${fmt(sun)}`;
}

function renderShift(s, isMe){
  const div = document.createElement('div'); div.className='shift'+(isMe?' me':'');
  const t1 = s.start_dt.slice(11,16), t2 = s.end_dt.slice(11,16);
  const role = (s.notes || s.employee_role || '').trim();
  div.innerHTML = `<div><strong>${t1}–${t2}</strong></div>${role?`<div>${esc(role)}</div>`:''}`;
  return div;
}

async function loadWeek(){
  setWeekHeader();
  const data = await fetchJSON(`/schedule/api?a=shifts.week&week=${weekStart}`);
  allWeekShifts = data.shifts || [];

  const body = document.getElementById('gridBody'); body.innerHTML='';

  if(scope==='all'){
    document.getElementById('whoLine').textContent = 'Viewing: entire team';
    document.getElementById('hoursLabel').textContent='Team hours';

    const byEmp = {};
    allWeekShifts.forEach(s=>{ (byEmp[s.employee_id] ||= []).push(s); });

    // sort employees by name
    const ids = Object.keys(byEmp).map(Number).sort((a,b)=>{
      const an=(employees.find(e=>e.id===a)?.name||'').toLowerCase();
      const bn=(employees.find(e=>e.id===b)?.name||'').toLowerCase();
      return an<bn?-1:an>bn?1:0;
    });

    let total=0;
    const days = weekDays(weekStart);

    ids.forEach(empId=>{
      const row = document.createElement('div'); row.className='row';
      const meta = employees.find(e=>e.id===empId)||{name:'(Unknown)'};
      const list = byEmp[empId]; const hrs=sumHours(list); total+=hrs;

      const empCell = document.createElement('div'); empCell.className='emp';
      empCell.innerHTML = `<div class="name">${esc(meta.name)}</div><div class="small">${hrs.toFixed(2)} hrs</div>${empId===me.employee_id?'<div class="small">This is you</div>':''}`;
      row.appendChild(empCell);

      days.forEach(d=>{
        const cell=document.createElement('div'); cell.className='cell';
        list.filter(s=>s.start_dt.slice(0,10)===d.ymd).forEach(s=>cell.appendChild(renderShift(s, empId===me.employee_id)));
        row.appendChild(cell);
      });

      body.appendChild(row);
    });

    document.getElementById('hoursVal').textContent = total.toFixed(2);
  } else {
    document.getElementById('whoLine').textContent = `Viewing: ${me.name}`;
    document.getElementById('hoursLabel').textContent='My hours';

    const myList = allWeekShifts.filter(s=>s.employee_id===me.employee_id);

    const row = document.createElement('div'); row.className='row';
    const meCell = document.createElement('div'); meCell.className='emp';
    meCell.innerHTML = `<div class="name">${esc(me.name)}</div><div class="small">${sumHours(myList).toFixed(2)} hrs</div>`;
    row.appendChild(meCell);

    const days=weekDays(weekStart);
    days.forEach(d=>{
      const cell=document.createElement('div'); cell.className='cell';
      const todaysMine=myList.filter(s=>s.start_dt.slice(0,10)===d.ymd);
      todaysMine.forEach(s=>{
        const el = renderShift(s,true);
        const coworkers = allWeekShifts.filter(x=>x.employee_id!==me.employee_id && x.start_dt.slice(0,10)===d.ymd);
        if(coworkers.length){
          const names = uniq(coworkers.map(x=>(employees.find(e=>e.id===x.employee_id)?.name)||'Unknown'));
          const info=document.createElement('div'); info.className='small'; info.style.marginTop='.25rem'; info.textContent='With: '+names.join(', ');
          el.appendChild(info);
        }
        cell.appendChild(el);
      });
      row.appendChild(cell);
    });

    body.appendChild(row);
    document.getElementById('hoursVal').textContent = sumHours(myList).toFixed(2);
  }
}

function bindUI(){
  document.getElementById('prevBtn').onclick = async ()=>{
    const d=new Date(weekStart+'T12:00:00'); d.setDate(d.getDate()-7);
    weekStart=mondayOf(d.toISOString().slice(0,10)); await loadWeek();
  };
  document.getElementById('nextBtn').onclick = async ()=>{
    const d=new Date(weekStart+'T12:00:00'); d.setDate(d.getDate()+7);
    weekStart=mondayOf(d.toISOString().slice(0,10)); await loadWeek();
  };
  const mine=document.getElementById('scopeMine'), all=document.getElementById('scopeAll');
  mine.onclick=async ()=>{scope='mine'; mine.classList.add('active'); all.classList.remove('active'); await loadWeek();};
  all.onclick =async ()=>{scope='all';  all.classList.add('active');  mine.classList.remove('active'); await loadWeek();};
}

document.addEventListener('DOMContentLoaded', async ()=>{
  try {
    const who = await fetchJSON('/schedule/api?a=me.employee');
    me.employee_id = who.employee_id; me.name = who.employee_name || 'Me';

    employees = await fetchJSON('/schedule/api?a=employees.list');

    weekStart = mondayOf(new Date().toISOString().slice(0,10)); // this week first
    bindUI();
    await loadWeek();
  } catch (e){
    console.error(e);
    alert('Could not load your shifts: ' + e.message);
  }
});
</script>
