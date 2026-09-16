<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <style>
        :root {
            --bg: #f4f7f4;
            --surface: #ffffff;
            --ink: #1a2e1a;
            --muted: #5a6b5a;
            --accent: #2d6a4f;
            --accent-hover: #1b4332;
            --border: #d8e2d8;
            --danger: #b42318;
            --danger-bg: #fef3f2;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, #d8f3dc 0%, transparent 40%),
                radial-gradient(circle at bottom right, #cce3de 0%, transparent 35%),
                var(--bg);
        }
        a { color: var(--accent); text-decoration: none; }
        a:hover { text-decoration: underline; }
        .shell {
            max-width: 720px;
            margin: 0 auto;
            padding: 2rem 1.25rem 3rem;
        }
        .brand {
            display: block;
            margin-bottom: 1.5rem;
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--accent);
            letter-spacing: -0.02em;
        }
        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.75rem;
            box-shadow: 0 10px 30px rgba(26, 46, 26, 0.06);
        }
        h1 {
            margin: 0 0 0.35rem;
            font-size: 1.5rem;
        }
        .subtitle {
            margin: 0 0 1.5rem;
            color: var(--muted);
            font-size: 0.95rem;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        @media (max-width: 640px) {
            .grid { grid-template-columns: 1fr; }
        }
        .field { margin-bottom: 1rem; }
        .field.full { grid-column: 1 / -1; }
        label {
            display: block;
            margin-bottom: 0.35rem;
            font-size: 0.875rem;
            font-weight: 600;
        }
        input, select {
            width: 100%;
            padding: 0.65rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            color: var(--ink);
            font: inherit;
        }
        input:focus, select:focus {
            outline: 2px solid rgba(45, 106, 79, 0.25);
            border-color: var(--accent);
        }
        .error {
            margin-top: 0.35rem;
            color: var(--danger);
            font-size: 0.8rem;
        }
        .alert {
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            background: var(--danger-bg);
            color: var(--danger);
            font-size: 0.9rem;
        }
        .actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-top: 1.25rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.7rem 1.2rem;
            border: none;
            border-radius: 8px;
            background: var(--accent);
            color: #fff;
            font: inherit;
            font-weight: 600;
            cursor: pointer;
        }
        .btn:hover { background: var(--accent-hover); text-decoration: none; }
        .btn-ghost {
            background: transparent;
            color: var(--accent);
            border: 1px solid var(--border);
        }
        .btn-ghost:hover { background: #eef6f0; }
        .remember {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: var(--muted);
        }
        .remember input { width: auto; }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .meta { color: var(--muted); font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="shell">
        <a class="brand" href="{{ url('/') }}">{{ config('app.name', 'NutriTrace') }}</a>
        @yield('content')
    </div>
</body>
</html>
