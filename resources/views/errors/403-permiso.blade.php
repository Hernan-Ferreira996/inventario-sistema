<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sin permiso</title>
<style>
    body {
        font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        margin: 0;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f7fafc;
        color: #1a202c;
    }
    @media (prefers-color-scheme: dark) {
        body { background: #1a202c; color: #edf2f7; }
        .divider { border-color: #4a5568 !important; }
    }
    .wrap { display: flex; align-items: center; padding: 2rem; max-width: 42rem; }
    .code { font-size: 1.125rem; padding-right: 1rem; margin-right: 1rem; border-right: 1px solid #cbd5e0; letter-spacing: .05em; }
    .divider { }
    .message { font-size: 1.125rem; }
</style>
</head>
<body>
    <div class="wrap">
        <div class="code divider">403</div>
        <div class="message">
            No tenés permiso para realizar esta acción.<br>
            Solicitá el permiso al área de Informática.
        </div>
    </div>
</body>
</html>
