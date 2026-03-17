/* ── Admin Panel CSS ── */
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --saffron:#E8660A;--saffron-lt:#FDF3EA;
  --gold:#C9921A;
  --deep:#1C1209;--mid:#3D2609;
  --warm:#FDF8F2;--white:#fff;
  --border:#EDE0CC;--border-soft:#F3EDE4;
  --text:#2C1A08;--muted:#9E8870;--mid-text:#7A6550;
  --navy:#003366;
  --sidebar-w:240px;
  --font-d:'Cormorant Garamond',serif;
  --font-b:'DM Sans',sans-serif;
}
body{font-family:var(--font-b);background:#F5F0E8;color:var(--text);display:flex;min-height:100vh;}

/* ── Sidebar ── */
.sidebar{
  width:var(--sidebar-w);background:linear-gradient(180deg,var(--deep) 0%,var(--mid) 100%);
  position:fixed;top:0;left:0;height:100vh;overflow-y:auto;
  display:flex;flex-direction:column;z-index:100;
}
.sidebar-brand{padding:24px 20px 20px;border-bottom:1px solid rgba(255,255,255,.08);}
.sidebar-brand .brand-icon{font-size:24px;margin-bottom:8px;}
.sidebar-brand h2{font-family:var(--font-d);font-size:18px;font-weight:600;color:#F5E6C8;line-height:1.2;}
.sidebar-brand p{font-size:11px;color:rgba(245,230,200,.45);letter-spacing:.08em;text-transform:uppercase;margin-top:3px;}

.sidebar-nav{flex:1;padding:16px 0;}
.nav-section-label{font-size:10px;font-weight:500;color:rgba(245,230,200,.35);
  letter-spacing:.12em;text-transform:uppercase;padding:14px 20px 6px;}
.sidebar-nav a{
  display:flex;align-items:center;gap:10px;
  padding:10px 20px;font-size:13.5px;color:rgba(245,230,200,.7);
  text-decoration:none;transition:all .2s;border-left:3px solid transparent;
}
.sidebar-nav a:hover{color:#F5E6C8;background:rgba(255,255,255,.06);}
.sidebar-nav a.active{color:#F5E6C8;background:rgba(232,102,10,.18);border-left-color:var(--saffron);}
.sidebar-nav a i{width:16px;text-align:center;font-size:14px;opacity:.8;}

.sidebar-footer{padding:16px 20px;border-top:1px solid rgba(255,255,255,.08);}
.sidebar-footer a{display:flex;align-items:center;gap:8px;font-size:13px;
  color:rgba(245,230,200,.5);text-decoration:none;transition:color .2s;}
.sidebar-footer a:hover{color:#F5E6C8;}

/* ── Main ── */
.main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh;}

/* ── Topbar ── */
.topbar{
  background:var(--white);border-bottom:1px solid var(--border);
  padding:14px 28px;display:flex;align-items:center;justify-content:space-between;
  position:sticky;top:0;z-index:50;
}
.topbar-left{font-size:13px;color:var(--muted);}
.topbar-left a{color:var(--saffron);text-decoration:none;}
.topbar-right{display:flex;align-items:center;gap:14px;}
.topbar-user{font-size:13px;font-weight:500;color:var(--text);}
.topbar-user i{color:var(--saffron);margin-right:5px;}

/* ── Content ── */
.content{padding:28px;flex:1;}

.page-header{display:flex;align-items:center;justify-content:space-between;
  margin-bottom:24px;flex-wrap:wrap;gap:12px;}
.page-header h2{font-family:var(--font-d);font-size:28px;font-weight:600;color:var(--deep);}
.page-sub{font-size:13px;color:var(--muted);margin-top:2px;}

/* ── Alerts ── */
.alert{display:flex;align-items:center;gap:10px;padding:13px 18px;border-radius:10px;
  margin-bottom:20px;font-size:13.5px;}
.alert-success{background:#F0FDF4;border:1px solid #BBF7D0;color:#166534;}
.alert-error{background:#FEF2F2;border:1px solid #FECACA;color:#B91C1C;}
.alert-warning{background:#FFFBEB;border:1px solid #FDE68A;color:#92400E;}

/* ── Buttons ── */
.btn-primary{
  display:inline-flex;align-items:center;gap:7px;
  background:var(--saffron);color:var(--white);
  padding:10px 20px;border-radius:8px;font-size:13.5px;font-weight:500;
  text-decoration:none;border:none;cursor:pointer;transition:background .2s,transform .15s;
  font-family:var(--font-b);
}
.btn-primary:hover{background:#C9530A;transform:translateY(-1px);}
.btn-secondary{
  display:inline-flex;align-items:center;gap:7px;
  background:var(--white);color:var(--text);
  border:1px solid var(--border);padding:10px 18px;border-radius:8px;
  font-size:13.5px;font-weight:500;text-decoration:none;cursor:pointer;
  transition:all .2s;font-family:var(--font-b);
}
.btn-secondary:hover{border-color:var(--saffron);color:var(--saffron);}
.btn-danger{
  display:inline-flex;align-items:center;gap:7px;
  background:#FEF2F2;color:#B91C1C;border:1px solid #FECACA;
  padding:8px 14px;border-radius:8px;font-size:13px;
  cursor:pointer;transition:all .2s;font-family:var(--font-b);text-decoration:none;
}
.btn-danger:hover{background:#B91C1C;color:var(--white);}
.btn-sm{padding:7px 13px;font-size:12.5px;}

/* ── Stats Grid ── */
.stats-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:14px;margin-bottom:24px;}
.stat-card{background:var(--white);border-radius:12px;border:1px solid var(--border);
  padding:18px;display:flex;align-items:center;gap:14px;
  box-shadow:0 1px 6px rgba(28,18,9,.05);}
.stat-icon{width:42px;height:42px;border-radius:10px;
  display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;}
.stat-icon.orange{background:var(--saffron-lt);color:var(--saffron);}
.stat-icon.brown{background:#F5EDE0;color:var(--mid);}
.stat-card strong{display:block;font-family:var(--font-d);font-size:26px;font-weight:600;color:var(--deep);line-height:1;}
.stat-card span{font-size:11.5px;color:var(--muted);margin-top:3px;display:block;}

/* ── Section Card ── */
.section-card{background:var(--white);border-radius:14px;border:1px solid var(--border);overflow:hidden;margin-bottom:24px;}
.section-card-head{display:flex;align-items:center;justify-content:space-between;
  padding:16px 20px;border-bottom:1px solid var(--border-soft);}
.section-card-head h3{font-family:var(--font-d);font-size:20px;font-weight:600;color:var(--deep);}
.section-card-head a{font-size:13px;color:var(--saffron);text-decoration:none;}

/* ── Thumbnail Grid ── */
.thumb-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:14px;padding:20px;}
.thumb-item{cursor:pointer;}
.thumb-wrap{position:relative;border-radius:10px;overflow:hidden;background:var(--saffron-lt);
  aspect-ratio:1;border:1px solid var(--border);}
.thumb-wrap img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .3s;}
.thumb-wrap:hover img{transform:scale(1.06);}
.thumb-fallback{width:100%;height:100%;display:flex;align-items:center;justify-content:center;
  color:var(--muted);font-size:28px;}
.thumb-badge{position:absolute;top:6px;right:6px;font-size:10px;font-weight:500;
  padding:3px 8px;border-radius:100px;}
.thumb-badge.inactive{background:#F3F4F6;color:#6B7280;}
.thumb-title{font-size:12.5px;font-weight:500;color:var(--text);margin-top:7px;
  white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.thumb-cat{font-size:11px;color:var(--muted);margin-top:2px;}

/* ── Upload Form ── */
.form-card{background:var(--white);border-radius:14px;border:1px solid var(--border);padding:28px;margin-bottom:24px;}
.form-card h3{font-family:var(--font-d);font-size:22px;font-weight:600;color:var(--deep);margin-bottom:20px;}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px;}
.form-group{margin-bottom:0;}
.form-group.full{grid-column:1/-1;}
.form-group label{display:block;font-size:12px;font-weight:500;color:var(--muted);
  text-transform:uppercase;letter-spacing:.08em;margin-bottom:7px;}
.form-group input,.form-group select,.form-group textarea{
  width:100%;padding:11px 14px;border:1px solid var(--border);border-radius:9px;
  font-family:var(--font-b);font-size:13.5px;color:var(--text);
  background:var(--warm);transition:border .2s,box-shadow .2s;outline:none;
}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{
  border-color:var(--saffron);box-shadow:0 0 0 3px rgba(232,102,10,.1);}
.form-group textarea{resize:vertical;min-height:90px;}
.form-row-actions{display:flex;gap:10px;margin-top:6px;grid-column:1/-1;}

/* ── Drop Zone ── */
.drop-zone{
  border:2px dashed var(--border);border-radius:12px;padding:40px 20px;
  text-align:center;cursor:pointer;background:var(--warm);
  transition:border-color .2s,background .2s;grid-column:1/-1;
}
.drop-zone.dragover{border-color:var(--saffron);background:var(--saffron-lt);}
.drop-zone i{font-size:36px;color:var(--muted);margin-bottom:12px;}
.drop-zone p{font-size:14px;color:var(--mid-text);margin:0;}
.drop-zone span{font-size:12px;color:var(--muted);display:block;margin-top:4px;}
.drop-zone input[type=file]{display:none;}
#previewWrap{display:none;grid-column:1/-1;position:relative;}
#previewWrap img{max-height:240px;border-radius:10px;border:1px solid var(--border);display:block;margin:0 auto;}
#removePreview{position:absolute;top:8px;right:8px;background:#fff;border:1px solid var(--border);
  border-radius:50%;width:28px;height:28px;cursor:pointer;
  display:flex;align-items:center;justify-content:center;font-size:13px;color:#B91C1C;}

/* ── Manage Table ── */
.table-filters{display:flex;gap:10px;flex-wrap:wrap;padding:16px 20px;border-bottom:1px solid var(--border-soft);}
.table-filters select,.table-filters input{
  padding:9px 13px;border:1px solid var(--border);border-radius:8px;
  font-family:var(--font-b);font-size:13px;color:var(--text);background:var(--warm);outline:none;
}
.table-filters input{flex:1;min-width:180px;}
.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead tr{background:#FAF5EE;border-bottom:1px solid var(--border);}
th{padding:11px 14px;font-size:11.5px;font-weight:500;color:var(--muted);
  text-transform:uppercase;letter-spacing:.07em;text-align:left;white-space:nowrap;}
td{padding:12px 14px;border-bottom:1px solid var(--border-soft);font-size:13.5px;vertical-align:middle;}
tr:last-child td{border-bottom:none;}
tr:hover td{background:#FDF8F2;}
.table-img{width:52px;height:52px;border-radius:8px;object-fit:cover;border:1px solid var(--border);}
.table-img-fallback{width:52px;height:52px;border-radius:8px;background:var(--saffron-lt);
  display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:18px;}
.badge{display:inline-block;padding:3px 10px;border-radius:100px;font-size:11.5px;font-weight:500;}
.badge-active{background:#F0FDF4;color:#166534;}
.badge-inactive{background:#F3F4F6;color:#6B7280;}
.actions{display:flex;gap:6px;align-items:center;}

/* ── Empty ── */
.empty-state{text-align:center;padding:48px 20px;}
.empty-state i{font-size:40px;color:var(--border);margin-bottom:14px;}
.empty-state p{color:var(--muted);font-size:15px;margin-bottom:16px;}

/* ── Pagination ── */
.pagination{display:flex;gap:6px;justify-content:center;padding:18px;flex-wrap:wrap;}
.page-link{padding:7px 13px;border:1px solid var(--border);border-radius:7px;
  font-size:13px;color:var(--text);text-decoration:none;transition:all .2s;}
.page-link:hover{border-color:var(--saffron);color:var(--saffron);}
.page-link.active{background:var(--saffron);color:var(--white);border-color:var(--saffron);}

/* ── Responsive ── */
@media(max-width:768px){
  .sidebar{transform:translateX(-100%);transition:transform .3s;}
  .sidebar.open{transform:translateX(0);}
  .main{margin-left:0;}
  .form-grid{grid-template-columns:1fr;}
  .form-group.full{grid-column:1;}
  .form-row-actions{grid-column:1;}
  .drop-zone{grid-column:1;}
  #previewWrap{grid-column:1;}
}