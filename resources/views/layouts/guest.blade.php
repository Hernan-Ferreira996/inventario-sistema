<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ config('app.name', 'Inventario Pro') }}</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
body { margin:0; background:#f1f5f9; min-height:100vh; display:flex; align-items:center; justify-content:center; font-family:'Segoe UI',sans-serif; }
.auth-wrapper { display:flex; width:100%; max-width:960px; min-height:560px; border-radius:20px; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,.15); }
.auth-brand { width:42%; background:linear-gradient(160deg,#1e293b 60%,#334155); color:#fff; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:3rem 2.5rem; }
.auth-brand .logo-icon { width:72px; height:72px; background:rgba(255,255,255,.1); border-radius:18px; display:flex; align-items:center; justify-content:center; font-size:2rem; margin-bottom:1.5rem; }
.auth-brand h1 { font-size:1.6rem; font-weight:700; margin-bottom:.4rem; }
.auth-brand p { color:#94a3b8; font-size:.9rem; text-align:center; line-height:1.5; }
.auth-brand .features { margin-top:2rem; width:100%; }
.auth-brand .feature { display:flex; align-items:center; gap:.75rem; padding:.5rem 0; color:#cbd5e1; font-size:.85rem; border-bottom:1px solid rgba(255,255,255,.07); }
.auth-brand .feature:last-child { border:none; }
.auth-brand .feature i { color:#60a5fa; font-size:1rem; flex-shrink:0; }
.auth-form { flex:1; background:#fff; display:flex; flex-direction:column; justify-content:center; padding:3rem 3.5rem; }
.auth-form h2 { font-size:1.5rem; font-weight:700; color:#1e293b; margin-bottom:.3rem; }
.auth-form .subtitle { color:#64748b; font-size:.9rem; margin-bottom:2rem; }
.auth-form .form-label { font-weight:600; font-size:.85rem; color:#374151; margin-bottom:.4rem; }
.auth-form .form-control { border-radius:10px; padding:.65rem 1rem; border-color:#e2e8f0; font-size:.95rem; }
.auth-form .form-control:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,.12); }
.auth-form .btn-primary { background:#1e293b; border:none; border-radius:10px; padding:.75rem; font-weight:600; font-size:.95rem; transition:.2s; }
.auth-form .btn-primary:hover { background:#0f172a; transform:translateY(-1px); }
.auth-form .form-check-input:checked { background-color:#1e293b; border-color:#1e293b; }
.auth-form .link-subtle { color:#3b82f6; text-decoration:none; font-size:.85rem; }
.auth-form .link-subtle:hover { text-decoration:underline; }
.brand-badge { background:rgba(59,130,246,.15); color:#93c5fd; border-radius:6px; padding:.25rem .75rem; font-size:.75rem; font-weight:600; margin-bottom:1rem; display:inline-block; }
@media(max-width:640px){ .auth-brand{display:none} .auth-wrapper{max-width:420px;border-radius:16px} .auth-form{padding:2.5rem 2rem} }
</style>
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-brand">
        <div class="logo-icon"><i class="bi bi-boxes"></i></div>
        <h1>Inventario Pro</h1>
        <span class="brand-badge"><i class="bi bi-geo-alt me-1"></i>Paraguay</span>
        <p>Sistema completo de inventario, ventas y facturación electrónica</p>
        <div class="features">
            <div class="feature"><i class="bi bi-receipt-cutoff"></i>Facturación electrónica SIFEN</div>
            <div class="feature"><i class="bi bi-building"></i>Multi-empresa y sucursales</div>
            <div class="feature"><i class="bi bi-shield-check"></i>Control de acceso por roles</div>
            <div class="feature"><i class="bi bi-graph-up-arrow"></i>Reportes y estadísticas</div>
            <div class="feature"><i class="bi bi-stack"></i>Control de stock en tiempo real</div>
        </div>
    </div>
    <div class="auth-form">
        {{ $slot }}
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
