<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traceability Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body {
            background: #edf2ef;
            color: #183b36;
            font-family: Arial, sans-serif;
        }
        .dashboard-shell {
            max-width: 1400px;
            margin: 32px auto;
            padding: 0 20px 32px;
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .title h1 {
            margin: 0;
            font-size: 2.2rem;
            font-weight: 700;
            color: #113d3b;
        }
        .title p {
            margin: 6px 0 0;
            color: #5a6f6c;
        }
        .pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: white;
            border: 1px solid #dfeae5;
            border-radius: 999px;
            padding: 10px 16px;
            font-size: 0.9rem;
            color: #1d4d44;
            box-shadow: 0 8px 20px rgba(17, 61, 59, 0.04);
        }
        .kpis {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 18px;
            margin-bottom: 24px;
        }
        .kpi-card {
            background: rgba(255,255,255,0.9);
            border: 1px solid #dfeae5;
            border-radius: 18px;
            padding: 18px 18px 16px;
            box-shadow: 0 10px 22px rgba(20, 49, 46, 0.04);
        }
        .kpi-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        .icon-box {
            width: 46px;
            height: 46px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            background: #eaf7ef;
            color: #1f7a4f;
            font-weight: 700;
        }
        .kpi-label {
            color: #516d69;
            font-size: 0.92rem;
        }
        .kpi-value {
            display: block;
            font-size: 2rem;
            font-weight: 700;
            color: #0f2d27;
            margin-top: 4px;
        }
        .kpi-trend {
            display: inline-block;
            margin-top: 8px;
            font-size: 0.82rem;
            font-weight: 700;
        }
        .positive { color: #1f8e56; }
        .negative { color: #d85757; }
        .content-grid {
            display: grid;
            grid-template-columns: 1.2fr 1.6fr;
            gap: 20px;
            margin-bottom: 24px;
        }
        .panel {
            background: white;
            border: 1px solid #dfeae5;
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 10px 22px rgba(20, 49, 46, 0.04);
        }
        .panel h2 {
            margin: 0 0 14px;
            font-size: 1.25rem;
            color: #123d38;
        }
        .mini-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 12px;
        }
        .mini-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #edf2ef;
            padding-bottom: 8px;
            color: #234d49;
        }
        .mini-list li:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: #edf9f0;
            color: #1f8e56;
            font-size: 0.75rem;
            font-weight: 700;
        }
        #traceability-map {
            height: 360px;
            width: 100%;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #dfeae5;
        }
        .map-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 12px;
            color: #526b68;
            font-size: 0.85rem;
        }
        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
        .secondary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 18px;
        }
        .card-metric {
            font-size: 1.8rem;
            font-weight: 700;
            color: #123d38;
            margin: 12px 0 0;
        }
        .small-note {
            color: #607b78;
            font-size: 0.82rem;
        }
        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
            .topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }
    </style>
</head>

<body>
    
    <div class="dashboard-shell">
        <div class="topbar">
            <div class="title">
                <h1>Traceability</h1>
                <p>Suivi complet du parcours du lot, de la production à la vente.</p>
            </div>
            <div class="pill">Production • Carte • KPIs</div>
        </div>

        <div class="kpis">
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-label">Production</span>
                    <div class="icon-box">P</div>
                </div>
                <span class="kpi-value">{{ $stats['total_lots'] ?? 1245 }}</span>
                <span class="kpi-trend positive">↑ +12% vs mois dernier</span>
            </div>

            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-label">Lots actifs</span>
                    <div class="icon-box">L</div>
                </div>
                <span class="kpi-value">{{ $stats['active_lots'] ?? 582 }}</span>
                <span class="kpi-trend positive">↑ +8% vs mois dernier</span>
            </div>

            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-label">En transit</span>
                    <div class="icon-box">T</div>
                </div>
                <span class="kpi-value">124</span>
                <span class="kpi-trend positive">↑ +15% vs mois dernier</span>
            </div>

            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-label">Stockés</span>
                    <div class="icon-box">S</div>
                </div>
                <span class="kpi-value">87</span>
                <span class="kpi-trend positive">↑ +10% vs mois dernier</span>
            </div>

            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-label">Alertes</span>
                    <div class="icon-box">A</div>
                </div>
                <span class="kpi-value">{{ \App\Models\TraceAlert::count() }}</span>
                <span class="kpi-trend negative">↓ 6% vs mois dernier</span>
            </div>
        </div>

        <div class="content-grid">
            <div class="panel">
                <h2>Étapes du lot</h2>
                <ul class="mini-list">
                    <li><span>Production</span><span class="badge">OK</span></li>
                    <li><span>Transformation</span><span class="badge">OK</span></li>
                    <li><span>Conditionnement</span><span class="badge">OK</span></li>
                    <li><span>Stockage</span><span class="badge">OK</span></li>
                    <li><span>Transport</span><span class="badge">OK</span></li>
                    <li><span>Distribution</span><span class="badge">OK</span></li>
                    <li><span>Vente</span><span class="badge">OK</span></li>
                </ul>
            </div>

            <div class="panel">
                <h2>Carte de traçabilité</h2>
                <div id="traceability-map"></div>
                <div class="map-legend">
                    <span><span class="dot" style="background:#2e7d32;"></span>Production</span>
                    <span><span class="dot" style="background:#4aa66d;"></span>Stockage</span>
                    <span><span class="dot" style="background:#7abf84;"></span>Transport</span>
                    <span><span class="dot" style="background:#a9d9ad;"></span>Distribution</span>
                </div>
            </div>
        </div>

        <div class="secondary-grid">
            <div class="panel">
                <h2>Lots en production</h2>
                <div class="card-metric">{{ $stats['total_lots'] ?? 1245 }}</div>
                <div class="small-note">Volume total enregistré cette semaine</div>
            </div>

            <div class="panel">
                <h2>Lots bloqués</h2>
                <div class="card-metric">{{ $stats['blocked_lots'] ?? 18 }}</div>
                <div class="small-note">À surveiller pour rappel ou correction</div>
            </div>

            <div class="panel">
                <h2>Lots vendus</h2>
                <div class="card-metric">432</div>
                <div class="small-note">Dernier cycle commercialisé</div>
            </div>

            <div class="panel">
                <h2>Chaîne du froid</h2>
                <div class="card-metric">96%</div>
                <div class="small-note">Conformité température enregistrée</div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const lotMarkers = @json($lots ?? []);

            const map = L.map('traceability-map').setView([36.825, 10.18], 7);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            const routePoints = [];

            lotMarkers.forEach((lot, index) => {
                const point = [lot.lat, lot.lng];
                routePoints.push(point);

                const marker = L.marker(point, {
                    icon: L.divIcon({
                        className: 'custom-map-marker',
                        html: `
                            <div style="
                                width: 30px;
                                height: 30px;
                                border-radius: 50%;
                                background: #2e7d32;
                                border: 3px solid white;
                                box-shadow: 0 2px 8px rgba(0,0,0,0.25);
                                color: white;
                                font-weight: 700;
                                font-size: 11px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            ">${index + 1}</div>
                        `,
                        iconSize: [30, 30],
                        iconAnchor: [15, 30],
                        popupAnchor: [0, -24]
                    })
                }).addTo(map);

                marker.bindPopup(`
                    <strong>${lot.lot_number}</strong><br>
                    ${lot.product_name}<br>
                    <span>${lot.origin}</span>
                `);
            });

            if (routePoints.length > 1) {
                const route = L.polyline(routePoints, { color: '#2e7d32', weight: 4, opacity: 0.8 }).addTo(map);
                map.fitBounds(route.getBounds(), { padding: [24, 24] });
            } else if (routePoints.length === 1) {
                map.setView(routePoints[0], 10);
            }
        });
    </script>
</body>
</html>
