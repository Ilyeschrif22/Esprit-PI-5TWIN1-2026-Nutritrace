<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>NutriTrace — Centre de traçabilité</title>

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        
        :root {
            --nt-primary: #176b52;
            --nt-primary-dark: #0d4939;
            --nt-primary-soft: #e8f4ef;

            --nt-blue: #3978a8;
            --nt-blue-soft: #eaf3f9;

            --nt-orange: #c88432;
            --nt-orange-soft: #fbf1e5;

            --nt-red: #c65353;
            --nt-red-soft: #faecec;

            --nt-purple: #7461a8;
            --nt-purple-soft: #f0edfa;

            --nt-text: #173b34;
            --nt-text-secondary: #667d77;
            --nt-text-muted: #93a39f;

            --nt-border: #e3ebe8;
            --nt-background: #f4f7f6;
            --nt-white: #ffffff;

            --nt-radius: 16px;
            --nt-shadow:
                0 8px 28px rgba(20, 55, 47, 0.055);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--nt-background);
            color: var(--nt-text);
            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        button,
        input {
            font: inherit;
        }

        button {
            cursor: pointer;
        }

        /* =========================================================
           PAGE
        ========================================================= */

        .traceability-page {
            width: 100%;
            max-width: 1560px;
            margin: 0 auto;
            padding: 32px 34px 48px;
        }

        /* =========================================================
           HEADER
        ========================================================= */

        .trace-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 28px;
        }

        .trace-heading {
            max-width: 780px;
        }

        .trace-eyebrow {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;

            color: var(--nt-primary);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .11em;
            text-transform: uppercase;
        }

        .trace-eyebrow-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--nt-primary);
            box-shadow: 0 0 0 5px var(--nt-primary-soft);
        }

        .trace-heading h1 {
            margin: 0;
            font-size: 32px;
            line-height: 1.15;
            letter-spacing: -.025em;
            font-weight: 750;
            color: #123b32;
        }

        .trace-heading p {
            margin: 9px 0 0;
            color: var(--nt-text-secondary);
            font-size: 14px;
            line-height: 1.6;
        }

        .trace-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .trace-date {
            display: flex;
            align-items: center;
            gap: 9px;
            height: 42px;
            padding: 0 14px;

            background: var(--nt-white);
            border: 1px solid var(--nt-border);
            border-radius: 11px;

            color: var(--nt-text-secondary);
            font-size: 13px;
            font-weight: 650;
        }

        .trace-live {
            display: flex;
            align-items: center;
            gap: 8px;
            height: 42px;
            padding: 0 14px;

            border-radius: 11px;
            background: var(--nt-primary-soft);
            color: var(--nt-primary);
            border: 1px solid #d5e9e1;

            font-size: 12px;
            font-weight: 750;
        }

        .live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #24a56f;
            box-shadow: 0 0 0 4px rgba(36,165,111,.12);
        }

        /* =========================================================
           KPI STRIP
        ========================================================= */

        .trace-kpis {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 18px;
        }

        .trace-kpi {
            position: relative;
            min-height: 132px;
            padding: 18px;

            background: var(--nt-white);
            border: 1px solid var(--nt-border);
            border-radius: var(--nt-radius);

            box-shadow: var(--nt-shadow);
            overflow: hidden;
        }

        .trace-kpi::after {
            content: "";
            position: absolute;
            right: -30px;
            bottom: -45px;

            width: 100px;
            height: 100px;

            border-radius: 50%;
            background: var(--nt-primary-soft);
            opacity: .45;
        }

        .trace-kpi-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 1;
        }

        .trace-kpi-label {
            color: var(--nt-text-secondary);
            font-size: 12px;
            font-weight: 650;
        }

        .trace-kpi-icon {
            width: 34px;
            height: 34px;

            display: grid;
            place-items: center;

            border-radius: 10px;
            background: var(--nt-primary-soft);
            color: var(--nt-primary);
        }

        .trace-kpi-value {
            position: relative;
            z-index: 1;

            display: block;
            margin-top: 15px;

            color: #123a32;
            font-size: 26px;
            line-height: 1;
            font-weight: 780;
            letter-spacing: -.025em;
        }

        .trace-kpi-meta {
            position: relative;
            z-index: 1;

            display: flex;
            align-items: center;
            gap: 5px;

            margin-top: 9px;

            font-size: 11px;
            color: var(--nt-text-muted);
        }

        .kpi-up {
            color: #23875e;
            font-weight: 750;
        }

        .kpi-warning {
            color: var(--nt-orange);
            font-weight: 750;
        }

        .kpi-danger {
            color: var(--nt-red);
            font-weight: 750;
        }

        /* =========================================================
           MAIN MAP SECTION
        ========================================================= */

        .trace-main-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.85fr) minmax(330px, .75fr);
            gap: 18px;
            margin-bottom: 18px;
        }

        .trace-panel {
            background: var(--nt-white);
            border: 1px solid var(--nt-border);
            border-radius: var(--nt-radius);
            box-shadow: var(--nt-shadow);
        }

        /* =========================================================
           MAP
        ========================================================= */

        .map-panel {
            overflow: hidden;
        }

        .map-panel-header {
            min-height: 76px;
            padding: 17px 19px;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;

            border-bottom: 1px solid var(--nt-border);
        }

        .map-title-area {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .map-title-icon {
            width: 40px;
            height: 40px;

            display: grid;
            place-items: center;

            border-radius: 11px;
            background: var(--nt-primary-soft);
            color: var(--nt-primary);
        }

        .map-title-area h2 {
            margin: 0;
            color: #173d35;
            font-size: 15px;
            font-weight: 760;
        }

        .map-title-area p {
            margin: 4px 0 0;
            color: var(--nt-text-muted);
            font-size: 11px;
        }

        .map-controls {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .map-control {
            height: 34px;
            padding: 0 10px;

            border: 1px solid var(--nt-border);
            border-radius: 9px;

            background: #fff;
            color: var(--nt-text-secondary);

            font-size: 11px;
            font-weight: 700;
        }

        .map-control:hover,
        .map-control.active {
            border-color: #c9dfd7;
            background: var(--nt-primary-soft);
            color: var(--nt-primary);
        }

        .map-container {
            position: relative;
            height: 540px;
            background: #e9efed;
        }

        #traceability-map {
            width: 100%;
            height: 100%;
        }

        /* Map overlay */

        .map-overlay {
            position: absolute;
            z-index: 900;

            top: 16px;
            left: 16px;

            width: 245px;

            background: rgba(255,255,255,.96);
            border: 1px solid rgba(224,234,230,.95);
            border-radius: 13px;

            box-shadow: 0 10px 30px rgba(25,55,48,.10);

            backdrop-filter: blur(10px);
        }

        .map-overlay-header {
            padding: 13px 14px;
            border-bottom: 1px solid var(--nt-border);
        }

        .map-overlay-header span {
            display: block;
            color: var(--nt-text-muted);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .map-overlay-header strong {
            display: block;
            margin-top: 4px;
            color: var(--nt-text);
            font-size: 14px;
        }

        .map-route-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            padding: 12px 14px;
            gap: 7px;
        }

        .route-summary-item {
            min-width: 0;
        }

        .route-summary-item span {
            display: block;
            color: var(--nt-text-muted);
            font-size: 9px;
        }

        .route-summary-item strong {
            display: block;
            margin-top: 3px;
            color: var(--nt-text);
            font-size: 12px;
        }

        .map-legend {
            position: absolute;
            z-index: 900;

            left: 16px;
            bottom: 16px;

            display: flex;
            align-items: center;
            gap: 13px;

            padding: 10px 13px;

            background: rgba(255,255,255,.95);
            border: 1px solid rgba(224,234,230,.95);
            border-radius: 11px;

            box-shadow: 0 8px 25px rgba(25,55,48,.09);
            backdrop-filter: blur(8px);
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;

            color: #526d67;
            font-size: 10px;
            font-weight: 650;
        }

        .legend-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .map-scale-info {
            position: absolute;
            z-index: 900;

            right: 16px;
            bottom: 16px;

            padding: 8px 10px;

            background: rgba(255,255,255,.93);
            border-radius: 8px;

            color: var(--nt-text-muted);
            font-size: 10px;
            font-weight: 650;
        }

        /* =========================================================
           JOURNEY PANEL
        ========================================================= */

        .journey-panel {
            padding: 0;
            overflow: hidden;
        }

        .journey-header {
            padding: 18px;
            border-bottom: 1px solid var(--nt-border);
        }

        .journey-header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .journey-header h2 {
            margin: 0;
            color: #173d35;
            font-size: 15px;
            font-weight: 760;
        }

        .journey-lot {
            margin-top: 5px;
            color: var(--nt-primary);
            font-size: 12px;
            font-weight: 750;
        }

        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;

            padding: 6px 8px;

            border-radius: 999px;
            background: var(--nt-primary-soft);
            color: var(--nt-primary);

            font-size: 10px;
            font-weight: 750;
        }

        .status-chip.status-active {
            background: rgba(23, 107, 82, 0.12);
            color: #176b52;
        }

        .status-chip.status-in-transit {
            background: rgba(57, 120, 168, 0.12);
            color: #3978a8;
        }

        .status-chip.status-stored {
            background: rgba(200, 132, 50, 0.12);
            color: #c88432;
        }

        .status-chip.status-distribution {
            background: rgba(116, 97, 168, 0.12);
            color: #7461a8;
        }

        .status-chip.status-blocked {
            background: rgba(180, 58, 58, 0.12);
            color: #b43a3a;
        }

        .status-chip-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
        }

        .journey-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .trace-action-button,
        .trace-action-button.secondary,
        .trace-action-button.primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 9px 12px;
            border: none;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .trace-action-button {
            background: linear-gradient(135deg, #176b52, #1d8d72);
            color: white;
            box-shadow: 0 8px 16px rgba(23, 107, 82, .18);
        }

        .trace-action-button:hover {
            transform: translateY(-1px);
        }

        .trace-action-button.secondary {
            background: #eef3f2;
            color: #1b3f3a;
            box-shadow: none;
        }

        .trace-action-button.primary {
            background: linear-gradient(135deg, #3978a8, #5c9fe0);
            color: white;
        }

        .transit-form-panel {
            display: none;
            padding: 0 18px 18px;
        }

        .transit-form-panel.visible {
            display: block;
        }

        .transit-form {
            background: #f8fbfa;
            border: 1px solid var(--nt-border);
            border-radius: 12px;
            padding: 12px;
        }

        .transit-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .transit-form-grid label {
            display: block;
            color: var(--nt-text-muted);
            font-size: 10px;
            font-weight: 700;
        }

        .transit-form-grid input,
        .transit-form-grid select {
            width: 100%;
            margin-top: 6px;
            padding: 9px 10px;
            border: 1px solid #dfe8e5;
            border-radius: 8px;
            background: white;
            color: var(--nt-text);
            font: inherit;
        }

        .transit-form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 12px;
        }

        .map-action-row {
            display: flex;
            justify-content: flex-end;
            margin-top: 12px;
        }

        .journey-body {
            padding: 18px;
        }

        .journey-route {
            position: relative;
            margin-top: 3px;
        }

        .journey-route::before {
            content: "";
            position: absolute;

            top: 9px;
            bottom: 9px;
            left: 8px;

            width: 1px;
            background: #dce7e3;
        }

        .journey-event {
            position: relative;

            display: grid;
            grid-template-columns: 17px minmax(0,1fr) auto;
            gap: 11px;

            padding-bottom: 20px;
        }

        .journey-event:last-child {
            padding-bottom: 0;
        }

        .journey-node {
            position: relative;
            z-index: 2;

            width: 17px;
            height: 17px;

            border-radius: 50%;
            background: white;

            border: 4px solid var(--nt-primary);
            box-shadow: 0 0 0 2px white;
        }

        .journey-node.storage {
            border-color: var(--nt-orange);
        }

        .journey-node.transport {
            border-color: var(--nt-blue);
        }

        .journey-node.distribution {
            border-color: var(--nt-purple);
        }

        .journey-event-content strong {
            display: block;
            color: #24463f;
            font-size: 11px;
            font-weight: 760;
        }

        .journey-event-content span {
            display: block;
            margin-top: 3px;
            color: var(--nt-text-muted);
            font-size: 10px;
            line-height: 1.4;
        }

        .journey-event-time {
            color: #91a19d;
            font-size: 9px;
            white-space: nowrap;
        }

        .journey-footer {
            padding: 14px 18px;
            border-top: 1px solid var(--nt-border);
            background: #fbfcfc;
        }

        .journey-footer-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .journey-stat {
            padding: 10px;

            border: 1px solid var(--nt-border);
            border-radius: 10px;
            background: white;
        }

        .journey-stat span {
            display: block;
            color: var(--nt-text-muted);
            font-size: 9px;
        }

        .journey-stat strong {
            display: block;
            margin-top: 4px;
            color: var(--nt-text);
            font-size: 14px;
        }

        /* =========================================================
           SECOND ROW
        ========================================================= */

        .trace-secondary-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1fr;
            gap: 18px;
            margin-bottom: 18px;
        }

        .secondary-panel {
            padding: 18px;
            min-height: 270px;
        }

        .panel-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;

            margin-bottom: 18px;
        }

        .panel-heading h2 {
            margin: 0;
            color: #173d35;
            font-size: 14px;
            font-weight: 760;
        }

        .panel-heading span {
            color: var(--nt-text-muted);
            font-size: 10px;
        }

        /* =========================================================
           ENVIRONMENT
        ========================================================= */

        .environment-main {
            display: flex;
            align-items: center;
            gap: 22px;
        }

        .environment-score {
            position: relative;

            width: 126px;
            height: 126px;
            flex: 0 0 126px;
        }

        .environment-ring {
            width: 100%;
            height: 100%;
            border-radius: 50%;

            background:
                conic-gradient(
                    var(--nt-primary) 0deg 318deg,
                    #e6efec 318deg 360deg
                );

            display: grid;
            place-items: center;
        }

        .environment-ring::after {
            content: "";
            width: 91px;
            height: 91px;

            border-radius: 50%;
            background: white;

            position: absolute;
        }

        .environment-score-content {
            position: absolute;
            inset: 0;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;

            z-index: 2;
        }

        .environment-score-content strong {
            font-size: 25px;
            color: var(--nt-text);
        }

        .environment-score-content span {
            margin-top: 2px;
            color: var(--nt-text-muted);
            font-size: 9px;
        }

        .environment-metrics {
            flex: 1;
            display: grid;
            gap: 13px;
        }

        .environment-metric {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .environment-metric-label {
            color: var(--nt-text-secondary);
            font-size: 11px;
        }

        .environment-metric-value {
            color: var(--nt-text);
            font-size: 12px;
            font-weight: 760;
        }

        .metric-progress {
            height: 5px;
            margin-top: 6px;

            overflow: hidden;
            border-radius: 999px;
            background: #edf2f0;
        }

        .metric-progress > span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: var(--nt-primary);
        }

        /* =========================================================
           RISK
        ========================================================= */

        .risk-grid {
            display: grid;
            gap: 9px;
        }

        .risk-item {
            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 11px 12px;

            border: 1px solid var(--nt-border);
            border-radius: 10px;
        }

        .risk-left {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .risk-icon {
            width: 30px;
            height: 30px;

            display: grid;
            place-items: center;

            border-radius: 8px;
        }

        .risk-icon.warning {
            background: var(--nt-orange-soft);
            color: var(--nt-orange);
        }

        .risk-icon.danger {
            background: var(--nt-red-soft);
            color: var(--nt-red);
        }

        .risk-icon.ok {
            background: var(--nt-primary-soft);
            color: var(--nt-primary);
        }

        .risk-name {
            color: #35554e;
            font-size: 11px;
            font-weight: 650;
        }

        .risk-value {
            font-size: 11px;
            font-weight: 780;
        }

        .risk-value.warning {
            color: var(--nt-orange);
        }

        .risk-value.danger {
            color: var(--nt-red);
        }

        .risk-value.ok {
            color: var(--nt-primary);
        }

        /* =========================================================
           ACTIVITY
        ========================================================= */

        .activity-list {
            display: grid;
            gap: 1px;
        }

        .activity-item {
            display: grid;
            grid-template-columns: 32px minmax(0,1fr) auto;
            align-items: center;
            gap: 10px;

            padding: 9px 0;

            border-bottom: 1px solid #edf2f0;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 30px;
            height: 30px;

            display: grid;
            place-items: center;

            border-radius: 8px;
            background: var(--nt-primary-soft);
            color: var(--nt-primary);
        }

        .activity-content strong {
            display: block;
            color: #315049;
            font-size: 10px;
            font-weight: 730;
        }

        .activity-content span {
            display: block;
            margin-top: 2px;
            color: var(--nt-text-muted);
            font-size: 9px;
        }

        .activity-time {
            color: #9aa9a5;
            font-size: 9px;
        }

        /* =========================================================
           BOTTOM ANALYTICS
        ========================================================= */

        .trace-bottom-grid {
            display: grid;
            grid-template-columns: 1.45fr .75fr;
            gap: 18px;
        }

        .chart-panel {
            padding: 18px;
            min-height: 285px;
        }

        .chart-wrapper {
            height: 210px;
        }

        .stage-list {
            display: grid;
            gap: 13px;
        }

        .stage-row {
            display: grid;
            grid-template-columns: 110px 1fr 45px;
            align-items: center;
            gap: 10px;
        }

        .stage-label {
            color: #536b66;
            font-size: 10px;
            font-weight: 650;
        }

        .stage-bar {
            height: 7px;
            overflow: hidden;
            border-radius: 999px;
            background: #edf2f0;
        }

        .stage-bar span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: var(--nt-primary);
        }

        .stage-value {
            color: #34564e;
            font-size: 10px;
            font-weight: 760;
            text-align: right;
        }

        /* =========================================================
           LEAFLET
        ========================================================= */

        .leaflet-container {
            font-family: inherit;
        }

        .leaflet-control-zoom {
            border: none !important;
            box-shadow: 0 5px 18px rgba(20,55,47,.12) !important;
        }

        .leaflet-control-zoom a {
            color: #315c52 !important;
            border: none !important;
        }

        .trace-popup {
            min-width: 210px;
        }

        .trace-popup-header {
            padding-bottom: 8px;
            margin-bottom: 8px;
            border-bottom: 1px solid #edf2f0;
        }

        .trace-popup-header strong {
            display: block;
            color: #163e35;
            font-size: 13px;
        }

        .trace-popup-header span {
            display: block;
            margin-top: 2px;
            color: #78908a;
            font-size: 10px;
        }

        .trace-popup-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 3px 0;

            color: #526d67;
            font-size: 10px;
        }

        .trace-popup-row strong {
            color: #234b42;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 1250px) {
            .trace-kpis {
                grid-template-columns: repeat(3, 1fr);
            }

            .trace-main-grid {
                grid-template-columns: 1fr;
            }

            .trace-secondary-grid {
                grid-template-columns: 1fr 1fr;
            }

            .trace-bottom-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 850px) {
            .traceability-page {
                padding: 22px 16px 35px;
            }

            .trace-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .trace-header-actions {
                width: 100%;
            }

            .trace-kpis {
                grid-template-columns: repeat(2, 1fr);
            }

            .trace-secondary-grid {
                grid-template-columns: 1fr;
            }

            .map-container {
                height: 430px;
            }

            .map-overlay {
                width: 210px;
            }

            .map-controls {
                display: none;
            }
        }

        @media (max-width: 520px) {
            .trace-kpis {
                grid-template-columns: 1fr;
            }

            .trace-heading h1 {
                font-size: 25px;
            }

            .map-overlay {
                display: none;
            }

            .map-legend {
                left: 10px;
                bottom: 10px;
                gap: 8px;
            }

            .legend-item {
                font-size: 9px;
            }
        }
    </style>
</head>

<body>

<div class="traceability-page">

    <!-- =========================================================
         HEADER
    ========================================================== -->

    <header class="trace-header">

        <div class="trace-heading">

            <div class="trace-eyebrow">
                <span class="trace-eyebrow-dot"></span>
                Centre de contrôle
            </div>

            <h1>Traçabilité &amp; chaîne logistique</h1>

            <p>
                Vue consolidée du parcours des lots, des acteurs,
                des transports et des événements de traçabilité.
            </p>

        </div>

        <div class="trace-header-actions">

            <div class="trace-live">
                <span class="live-dot"></span>
                Données synchronisées
            </div>

            <div class="trace-date">
                <svg width="15" height="15" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor"
                     stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>

                24 septembre 2026
            </div>

        </div>

    </header>


    <!-- =========================================================
         KPI STRIP
    ========================================================== -->

    <section class="trace-kpis">

        <article class="trace-kpi">

            <div class="trace-kpi-top">
                <span class="trace-kpi-label">Lots suivis</span>

                <div class="trace-kpi-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m12 3-8 4 8 4 8-4-8-4Z"/>
                        <path d="m4 12 8 4 8-4"/>
                        <path d="m4 17 8 4 8-4"/>
                    </svg>
                </div>
            </div>

            <strong class="trace-kpi-value">
                {{ number_format($stats['total_lots'] ?? 0) }}
            </strong>

            <div class="trace-kpi-meta">
                <span class="kpi-up">+12%</span>
                <span>vs période précédente</span>
            </div>

        </article>


        <article class="trace-kpi">

            <div class="trace-kpi-top">
                <span class="trace-kpi-label">Lots actifs</span>

                <div class="trace-kpi-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="m8 12 2.5 2.5L16 9"/>
                    </svg>
                </div>
            </div>

            <strong class="trace-kpi-value">
                {{ number_format($stats['active_lots'] ?? 0) }}
            </strong>

            <div class="trace-kpi-meta">
                <span class="kpi-up">+8%</span>
                <span>en circulation</span>
            </div>

        </article>


        <article class="trace-kpi">

            <div class="trace-kpi-top">
                <span class="trace-kpi-label">En transit</span>

                <div class="trace-kpi-icon"
                     style="background:var(--nt-blue-soft);color:var(--nt-blue);">

                    <svg width="17" height="17" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 7h11v10H3z"/>
                        <path d="M14 10h4l3 3v4h-7z"/>
                        <circle cx="7" cy="19" r="2"/>
                        <circle cx="18" cy="19" r="2"/>
                    </svg>

                </div>
            </div>

            <strong class="trace-kpi-value">
                {{ $stats['in_transit_lots'] ?? 0 }}
            </strong>

            <div class="trace-kpi-meta">
                <span class="kpi-up">Actuellement</span>
                <span>sur le réseau</span>
            </div>

        </article>


        <article class="trace-kpi">

            <div class="trace-kpi-top">
                <span class="trace-kpi-label">Distance tracée</span>

                <div class="trace-kpi-icon"
                     style="background:var(--nt-purple-soft);color:var(--nt-purple);">

                    <svg width="17" height="17" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M8 16 16 8"/>
                        <circle cx="8" cy="16" r="1"/>
                        <circle cx="16" cy="8" r="1"/>
                    </svg>

                </div>
            </div>

            <strong class="trace-kpi-value">
                {{ $stats['total_distance'] ?? '—' }}
            </strong>

            <div class="trace-kpi-meta">
                <span>km</span>
                <span>parcours enregistrés</span>
            </div>

        </article>


        <article class="trace-kpi">

            <div class="trace-kpi-top">
                <span class="trace-kpi-label">Émissions CO₂</span>

                <div class="trace-kpi-icon"
                     style="background:#eaf4ee;color:#287952;">

                    <svg width="17" height="17" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
                        <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
                    </svg>

                </div>
            </div>

            <strong class="trace-kpi-value">
                {{ $stats['co2_emissions'] ?? '—' }}
            </strong>

            <div class="trace-kpi-meta">
                <span class="kpi-up">CO₂</span>
                <span>transport enregistré</span>
            </div>

        </article>


        <article class="trace-kpi">

            <div class="trace-kpi-top">
                <span class="trace-kpi-label">Alertes actives</span>

                <div class="trace-kpi-icon"
                     style="background:var(--nt-red-soft);color:var(--nt-red);">

                    <svg width="17" height="17" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10.3 3.2 2.5 17a2 2 0 0 0 1.7 3h15.6a2 2 0 0 0 1.7-3L13.7 3.2a2 2 0 0 0-3.4 0Z"/>
                        <path d="M12 9v4"/>
                        <path d="M12 17h.01"/>
                    </svg>

                </div>
            </div>

            <strong class="trace-kpi-value">
                {{ $stats['active_alerts'] ?? \App\Models\TraceAlert::whereNull('resolved_at')->count() }}
            </strong>

            <div class="trace-kpi-meta">
                <span class="kpi-danger">Surveillance</span>
                <span>requise</span>
            </div>

        </article>

    </section>


    <!-- =========================================================
         MAP + JOURNEY
    ========================================================== -->

    <section class="trace-main-grid">

        <!-- MAP -->

        <article class="trace-panel map-panel">

            <div class="map-panel-header">

                <div class="map-title-area">

                    <div class="map-title-icon">

                        <svg width="19" height="19" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor"
                             stroke-width="2">

                            <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/>
                            <circle cx="12" cy="10" r="3"/>

                        </svg>

                    </div>

                    <div>
                        <h2>Carte de traçabilité territoriale</h2>
                        <p>
                            Parcours géographique des événements enregistrés
                        </p>
                    </div>

                </div>

                <div class="map-controls">

                    <button class="map-control active" type="button"
                            data-map-filter="all">
                        Tout
                    </button>

                    <button class="map-control" type="button"
                            data-map-filter="production">
                        Production
                    </button>

                    <button class="map-control" type="button"
                            data-map-filter="transport">
                        Transport
                    </button>

                    <button class="map-control" type="button"
                            data-map-filter="distribution">
                        Distribution
                    </button>

                </div>

            </div>


            <div class="map-container">

                <div id="traceability-map"></div>


                <!-- MAP SUMMARY -->

                <div class="map-overlay">

                    <div class="map-overlay-header">

                        <span>Parcours sélectionné</span>

                        <strong id="mapSelectedLot">
                            Vue réseau
                        </strong>

                    </div>

                    <div class="map-route-summary">

                        <div class="route-summary-item">
                            <span>Étapes</span>
                            <strong id="mapStepCount">—</strong>
                        </div>

                        <div class="route-summary-item">
                            <span>Distance</span>
                            <strong id="mapDistance">—</strong>
                        </div>

                        <div class="route-summary-item">
                            <span>CO₂</span>
                            <strong id="mapCo2">—</strong>
                        </div>

                    </div>

                    <div class="map-action-row">
                        <button type="button" class="trace-action-button" id="mapTransitButton">
                            Marquer en transit
                        </button>
                    </div>

                </div>


                <!-- LEGEND -->

                <div class="map-legend">

                    <div class="legend-item">
                        <span class="legend-dot"
                              style="background:#176b52;"></span>
                        Production
                    </div>

                    <div class="legend-item">
                        <span class="legend-dot"
                              style="background:#c88432;"></span>
                        Stockage
                    </div>

                    <div class="legend-item">
                        <span class="legend-dot"
                              style="background:#3978a8;"></span>
                        Transport
                    </div>

                    <div class="legend-item">
                        <span class="legend-dot"
                              style="background:#7461a8;"></span>
                        Distribution
                    </div>

                </div>


                <div class="map-scale-info">
                    OpenStreetMap • Données géographiques
                </div>

            </div>

        </article>


        <!-- JOURNEY -->

        <article class="trace-panel journey-panel">

            <div class="journey-header">

                <div class="journey-header-top">

                    <div>
                        <h2>Parcours du lot</h2>

                        <div class="journey-lot" id="journeyLot">
                            Sélectionnez un point
                        </div>
                    </div>

                    <div class="journey-actions">
                        <span class="status-chip" id="statusChip">
                            <span class="status-chip-dot"></span>
                            Suivi actif
                        </span>

                        <button type="button" class="trace-action-button" id="openTransitFormBtn">
                            Marquer en transit
                        </button>
                    </div>

                </div>

            </div>

            <div class="transit-form-panel" id="transitFormPanel">
                <div class="transit-form">
                    <div class="transit-form-grid">
                        <label>
                            Destination
                            <input id="transitDestination" type="text" placeholder="Sfax" value="Sfax" />
                        </label>

                        <label>
                            Mode de transport
                            <select id="transitMode">
                                <option value="road">Route</option>
                                <option value="refrigerated_road">Route réfrigérée</option>
                                <option value="air">Air</option>
                                <option value="sea">Mer</option>
                            </select>
                        </label>

                        <label style="grid-column: 1 / -1;">
                            Transporteur
                            <input id="transitCarrier" type="text" placeholder="Nom du transporteur" value="LogiTunis" />
                        </label>
                    </div>

                    <div class="transit-form-actions">
                        <button type="button" class="trace-action-button secondary" id="cancelTransitBtn">
                            Annuler
                        </button>
                        <button type="button" class="trace-action-button primary" id="submitTransitBtn">
                            Valider le transit
                        </button>
                    </div>
                </div>
            </div>


            <div class="journey-body">

                <div class="journey-route" id="journeyTimeline">

                    <div class="journey-event">

                        <div class="journey-node"></div>

                        <div class="journey-event-content">
                            <strong>Production</strong>
                            <span>Point de départ du lot</span>
                        </div>

                        <span class="journey-event-time">—</span>

                    </div>

                    <div class="journey-event">

                        <div class="journey-node storage"></div>

                        <div class="journey-event-content">
                            <strong>Transformation / stockage</strong>
                            <span>Événement intermédiaire</span>
                        </div>

                        <span class="journey-event-time">—</span>

                    </div>

                    <div class="journey-event">

                        <div class="journey-node transport"></div>

                        <div class="journey-event-content">
                            <strong>Transport</strong>
                            <span>Déplacement du lot</span>
                        </div>

                        <span class="journey-event-time">—</span>

                    </div>

                    <div class="journey-event">

                        <div class="journey-node distribution"></div>

                        <div class="journey-event-content">
                            <strong>Distribution</strong>
                            <span>Destination finale enregistrée</span>
                        </div>

                        <span class="journey-event-time">—</span>

                    </div>

                </div>

            </div>


            <div class="journey-footer">

                <div class="journey-footer-grid">

                    <div class="journey-stat">
                        <span>Acteurs impliqués</span>
                        <strong id="journeyActors">—</strong>
                    </div>

                    <div class="journey-stat">
                        <span>Dernier événement</span>
                        <strong id="journeyLastEvent">—</strong>
                    </div>

                    <div class="journey-stat">
                        <span>Distance</span>
                        <strong id="journeyDistance">—</strong>
                    </div>

                    <div class="journey-stat">
                        <span>Émissions</span>
                        <strong id="journeyCo2">—</strong>
                    </div>

                </div>

            </div>

        </article>

    </section>


    <!-- =========================================================
         ENVIRONMENT / RISKS / ACTIVITY
    ========================================================== -->

    <section class="trace-secondary-grid">


        <!-- ENVIRONMENT -->

        <article class="trace-panel secondary-panel">

            <div class="panel-heading">

                <h2>Empreinte environnementale</h2>

                <span>Transport &amp; logistique</span>

            </div>


            <div class="environment-main">

                <div class="environment-score">

                    <div class="environment-ring"></div>

                    <div class="environment-score-content">
                        <strong>{{ $stats['environment_score'] ?? 88 }}%</strong>
                        <span>performance</span>
                    </div>

                </div>


                <div class="environment-metrics">

                    <div>

                        <div class="environment-metric">
                            <span class="environment-metric-label">
                                Transport tracé
                            </span>

                            <strong class="environment-metric-value">
                                {{ $stats['transport_coverage'] ?? '94%' }}
                            </strong>
                        </div>

                        <div class="metric-progress">
                            <span style="width:94%;"></span>
                        </div>

                    </div>


                    <div>

                        <div class="environment-metric">
                            <span class="environment-metric-label">
                                Données CO₂
                            </span>

                            <strong class="environment-metric-value">
                                {{ $stats['co2_coverage'] ?? '91%' }}
                            </strong>
                        </div>

                        <div class="metric-progress">
                            <span style="width:91%;"></span>
                        </div>

                    </div>


                    <div>

                        <div class="environment-metric">
                            <span class="environment-metric-label">
                                Parcours complets
                            </span>

                            <strong class="environment-metric-value">
                                {{ $stats['complete_traceability'] ?? '87%' }}
                            </strong>
                        </div>

                        <div class="metric-progress">
                            <span style="width:87%;"></span>
                        </div>

                    </div>

                </div>

            </div>

        </article>


        <!-- RISKS -->

        <article class="trace-panel secondary-panel">

            <div class="panel-heading">

                <h2>Contrôle &amp; anomalies</h2>

                <span>État du réseau</span>

            </div>


            <div class="risk-grid">

                <div class="risk-item">

                    <div class="risk-left">

                        <div class="risk-icon warning">

                            <svg width="15" height="15"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">

                                <path d="M10.3 3.2 2.5 17a2 2 0 0 0 1.7 3h15.6a2 2 0 0 0 1.7-3L13.7 3.2a2 2 0 0 0-3.4 0Z"/>
                                <path d="M12 9v4"/>
                                <path d="M12 17h.01"/>

                            </svg>

                        </div>

                        <span class="risk-name">
                            Alertes de traçabilité
                        </span>

                    </div>

                    <strong class="risk-value warning">
                        {{ $stats['traceability_alerts'] ?? 0 }}
                    </strong>

                </div>


                <div class="risk-item">

                    <div class="risk-left">

                        <div class="risk-icon danger">

                            <svg width="15" height="15"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">

                                <circle cx="12" cy="12" r="9"/>
                                <path d="M8 12h8"/>

                            </svg>

                        </div>

                        <span class="risk-name">
                            Lots bloqués
                        </span>

                    </div>

                    <strong class="risk-value danger">
                        {{ $stats['blocked_lots'] ?? 0 }}
                    </strong>

                </div>


                <div class="risk-item">

                    <div class="risk-left">

                        <div class="risk-icon ok">

                            <svg width="15" height="15"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">

                                <path d="m5 12 4 4L19 6"/>

                            </svg>

                        </div>

                        <span class="risk-name">
                            Lots conformes
                        </span>

                    </div>

                    <strong class="risk-value ok">
                        {{ $stats['compliant_lots'] ?? '—' }}
                    </strong>

                </div>


                <div class="risk-item">

                    <div class="risk-left">

                        <div class="risk-icon ok">

                            <svg width="15" height="15"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">

                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>
                                <path d="m9 12 2 2 4-4"/>

                            </svg>

                        </div>

                        <span class="risk-name">
                            Certifications vérifiées
                        </span>

                    </div>

                    <strong class="risk-value ok">
                        {{ $stats['verified_certifications'] ?? '—' }}
                    </strong>

                </div>

            </div>

        </article>


        <!-- ACTIVITY -->

        <article class="trace-panel secondary-panel">

            <div class="panel-heading">

                <h2>Activité de traçabilité</h2>

                <span>Derniers événements</span>

            </div>


            <div class="activity-list">

                <div class="activity-item">

                    <div class="activity-icon">

                        <svg width="14" height="14"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">

                            <path d="M12 3v18"/>
                            <path d="M3 12h18"/>

                        </svg>

                    </div>

                    <div class="activity-content">

                        <strong>Nouveau lot enregistré</strong>

                        <span>
                            Production ajoutée à la chaîne
                        </span>

                    </div>

                    <span class="activity-time">
                        Récent
                    </span>

                </div>


                <div class="activity-item">

                    <div class="activity-icon">

                        <svg width="14" height="14"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">

                            <path d="M3 7h11v10H3z"/>
                            <path d="M14 10h4l3 3v4h-7z"/>
                            <circle cx="7" cy="19" r="2"/>
                            <circle cx="18" cy="19" r="2"/>

                        </svg>

                    </div>

                    <div class="activity-content">

                        <strong>Transport enregistré</strong>

                        <span>
                            Nouveau trajet ajouté
                        </span>

                    </div>

                    <span class="activity-time">
                        Récent
                    </span>

                </div>


                <div class="activity-item">

                    <div class="activity-icon">

                        <svg width="14" height="14"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">

                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>
                            <path d="m9 12 2 2 4-4"/>

                        </svg>

                    </div>

                    <div class="activity-content">

                        <strong>Contrôle validé</strong>

                        <span>
                            Événement de traçabilité vérifié
                        </span>

                    </div>

                    <span class="activity-time">
                        Récent
                    </span>

                </div>


                <div class="activity-item">

                    <div class="activity-icon">

                        <svg width="14" height="14"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">

                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 8v4l3 2"/>

                        </svg>

                    </div>

                    <div class="activity-content">

                        <strong>Parcours actualisé</strong>

                        <span>
                            Nouvelle position enregistrée
                        </span>

                    </div>

                    <span class="activity-time">
                        Récent
                    </span>

                </div>

            </div>

        </article>

    </section>


    <!-- =========================================================
         ANALYTICS
    ========================================================== -->

    <section class="trace-bottom-grid">


        <!-- TRACEABILITY VOLUME -->

        <article class="trace-panel chart-panel">

            <div class="panel-heading">

                <div>
                    <h2>Activité de traçabilité</h2>
                    <span>Événements enregistrés par période</span>
                </div>

                <span>6 mois</span>

            </div>

            <div class="chart-wrapper">
                <canvas id="traceActivityChart"></canvas>
            </div>

        </article>


        <!-- PROCESS COVERAGE -->

        <article class="trace-panel chart-panel">

            <div class="panel-heading">

                <div>
                    <h2>Couverture de la chaîne</h2>
                    <span>Étapes correctement documentées</span>
                </div>

            </div>


            <div class="stage-list">

                <div class="stage-row">

                    <span class="stage-label">
                        Production
                    </span>

                    <div class="stage-bar">
                        <span style="width:96%;"></span>
                    </div>

                    <strong class="stage-value">
                        96%
                    </strong>

                </div>


                <div class="stage-row">

                    <span class="stage-label">
                        Transformation
                    </span>

                    <div class="stage-bar">
                        <span style="width:91%;"></span>
                    </div>

                    <strong class="stage-value">
                        91%
                    </strong>

                </div>


                <div class="stage-row">

                    <span class="stage-label">
                        Stockage
                    </span>

                    <div class="stage-bar">
                        <span style="width:88%;"></span>
                    </div>

                    <strong class="stage-value">
                        88%
                    </strong>

                </div>


                <div class="stage-row">

                    <span class="stage-label">
                        Transport
                    </span>

                    <div class="stage-bar">
                        <span style="width:94%;"></span>
                    </div>

                    <strong class="stage-value">
                        94%
                    </strong>

                </div>


                <div class="stage-row">

                    <span class="stage-label">
                        Distribution
                    </span>

                    <div class="stage-bar">
                        <span style="width:86%;"></span>
                    </div>

                    <strong class="stage-value">
                        86%
                    </strong>

                </div>

            </div>

        </article>

    </section>

</div>


<!-- =========================================================
     LEAFLET
========================================================= -->

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
     * Backend data.
     *
     * The dashboard remains compatible with the current
     * $lots structure:
     *
     * lat
     * lng
     * lot_number
     * product_name
     * origin
     *
     * Additional properties are supported when available:
     *
     * stage
     * actor
     * city
     * occurred_at
     * distance
     * co2
     */

    const lots = @json($lots ?? []);
    let selectedLot = null;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    function getStatusClass(status) {
        const normalized = String(status || 'active').toLowerCase();

        if (normalized === 'in_transit') return 'status-in-transit';
        if (normalized === 'stored' || normalized === 'storage') return 'status-stored';
        if (normalized === 'distribution' || normalized === 'distributed') return 'status-distribution';
        if (normalized === 'blocked' || normalized === 'recalled') return 'status-blocked';

        return 'status-active';
    }

    const map = L.map('traceability-map', {
        zoomControl: false,
        preferCanvas: true
    });


    L.control.zoom({
        position: 'bottomright'
    }).addTo(map);


    /*
     * Clean, neutral OpenStreetMap base layer.
     */

    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 18
        }
    ).addTo(map);


    /*
     * Tunisia default.
     */

    map.setView([34.8, 9.5], 6);


    /*
     * Stage configuration.
     */

    const stageConfig = {

        production: {
            label: 'Production',
            color: '#176b52'
        },

        transformation: {
            label: 'Transformation',
            color: '#7461a8'
        },

        stockage: {
            label: 'Stockage',
            color: '#c88432'
        },

        storage: {
            label: 'Stockage',
            color: '#c88432'
        },

        transport: {
            label: 'Transport',
            color: '#3978a8'
        },

        distribution: {
            label: 'Distribution',
            color: '#7461a8'
        },

        default: {
            label: 'Étape',
            color: '#176b52'
        }

    };


    function normalizeStage(stage) {

        if (!stage) {
            return 'default';
        }

        return String(stage)
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');

    }


    function getStageConfig(stage) {

        const normalized = normalizeStage(stage);

        return stageConfig[normalized] || stageConfig.default;

    }


    /*
     * Marker layer.
     */

    const markerLayer = L.layerGroup().addTo(map);

    const markers = [];


    /*
     * Route coordinates.
     */

    const routePoints = [];
    const routeMeta = {
        color: '#176b52',
        isRouteReady: false
    };


    /*
     * Sort only when explicit sequence / date information exists.
     * Otherwise preserve backend order.
     */

    const orderedLots = [...lots];

    if (
        orderedLots.some(
            item => item.sequence !== undefined ||
                    item.occurred_at !== undefined
        )
    ) {

        orderedLots.sort(function (a, b) {

            if (
                a.sequence !== undefined &&
                b.sequence !== undefined
            ) {
                return Number(a.sequence) - Number(b.sequence);
            }

            return String(a.occurred_at || '')
                .localeCompare(String(b.occurred_at || ''));

        });

    }


    /*
     * Create markers.
     */

    orderedLots.forEach(function (lot, index) {

        if (
            lot.lat === undefined ||
            lot.lng === undefined ||
            lot.lat === null ||
            lot.lng === null
        ) {
            return;
        }


        const lat = Number(lot.lat);
        const lng = Number(lot.lng);

        if (
            Number.isNaN(lat) ||
            Number.isNaN(lng)
        ) {
            return;
        }


        const stage = normalizeStage(lot.stage || lot.type);
        const config = getStageConfig(stage);
        const status = String(lot.status || '').toLowerCase();
        const isRouteLot = status === 'in_transit' || stage === 'transport' || stage === 'distribution';

        if (isRouteLot) {
            routePoints.push([lat, lng]);
            routeMeta.isRouteReady = true;
            routeMeta.color = stage === 'distribution' ? '#7461a8' : '#3978a8';
        }


        const markerHtml = `
            <div
                class="nt-map-marker"
                style="
                    width:32px;
                    height:32px;
                    border-radius:50%;
                    background:${config.color};
                    border:3px solid #ffffff;
                    box-shadow:
                        0 3px 12px rgba(20,55,47,.24);
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    color:#ffffff;
                    font-size:10px;
                    font-weight:800;
                "
            >
                ${index + 1}
            </div>
        `;


        const marker = L.marker(
            [lat, lng],
            {
                icon: L.divIcon({
                    className: 'nt-marker-wrapper',
                    html: markerHtml,
                    iconSize: [32, 32],
                    iconAnchor: [16, 16],
                    popupAnchor: [0, -17]
                })
            }
        );


        const popup = `
            <div class="trace-popup">

                <div class="trace-popup-header">

                    <strong>
                        ${escapeHtml(
                            lot.lot_number || 'Lot'
                        )}
                    </strong>

                    <span>
                        ${escapeHtml(
                            lot.product_name || 'Produit'
                        )}
                    </span>

                </div>


                <div class="trace-popup-row">

                    <span>Étape</span>

                    <strong>
                        ${escapeHtml(config.label)}
                    </strong>

                </div>


                <div class="trace-popup-row">

                    <span>Origine</span>

                    <strong>
                        ${escapeHtml(
                            lot.origin || lot.city || '—'
                        )}
                    </strong>

                </div>


                <div class="trace-popup-row">

                    <span>Acteur</span>

                    <strong>
                        ${escapeHtml(
                            lot.actor || '—'
                        )}
                    </strong>

                </div>


                <div class="trace-popup-row">

                    <span>Date</span>

                    <strong>
                        ${escapeHtml(
                            lot.occurred_at || '—'
                        )}
                    </strong>

                </div>

            </div>
        `;


        marker.bindPopup(popup, {
            maxWidth: 280
        });


        marker.__stage = stage;
        marker.__lot = lot;


        marker.on('click', function () {

            updateSelectedLot(lot, index);

        });


        marker.addTo(markerLayer);

        markers.push(marker);

    });


    /*
     * Draw route.
     *
     * The route is only drawn when there are multiple
     * geographical traceability points.
     */

    let routeLine = null;


    if (routeMeta.isRouteReady && routePoints.length > 1) {

        routeLine = L.polyline(
            routePoints,
            {
                color: routeMeta.color,
                weight: 5,
                opacity: .78,
                lineJoin: 'round',
                lineCap: 'round'
            }
        ).addTo(map);


        const routeBounds = routeLine.getBounds();

        if (routeBounds.isValid()) {

            map.fitBounds(
                routeBounds,
                {
                    padding: [55, 55]
                }
            );

        }

    } else if (routePoints.length === 1) {

        map.setView(
            routePoints[0],
            10
        );

    }


    /*
     * Map filters.
     */

    document
        .querySelectorAll('[data-map-filter]')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    document
                        .querySelectorAll('[data-map-filter]')
                        .forEach(
                            item => item.classList.remove('active')
                        );

                    button.classList.add('active');


                    const filter =
                        button.dataset.mapFilter;


                    markers.forEach(function (marker) {

                        const visible =
                            filter === 'all' ||
                            marker.__stage === filter;

                        if (visible) {

                            marker.addTo(markerLayer);

                        } else {

                            markerLayer.removeLayer(marker);

                        }

                    });

                }
            );

        });


    /*
     * Selected lot information.
     */

    function updateSelectedLot(lot, index) {
        selectedLot = lot;

        const statusChip = document.getElementById('statusChip');
        if (statusChip) {
            const status = String(lot.status || 'active').toLowerCase();
            const label = status === 'in_transit' ? 'En transit' : status === 'stored' ? 'Stocké' : status === 'distribution' || status === 'distributed' ? 'Distribution' : status === 'blocked' ? 'Bloqué' : 'Suivi actif';
            statusChip.className = 'status-chip ' + getStatusClass(status);
            statusChip.innerHTML = '<span class="status-chip-dot"></span>' + label;
        }

        const lotNumber =
            lot.lot_number ||
            'LOT-' + String(index + 1).padStart(3, '0');


        document.getElementById(
            'mapSelectedLot'
        ).textContent = lotNumber;


        document.getElementById(
            'journeyLot'
        ).textContent =
            `${lotNumber} • ${lot.product_name || 'Produit'}`;


        document.getElementById(
            'mapStepCount'
        ).textContent =
            lot.step_count ||
            lot.steps ||
            '—';


        document.getElementById(
            'mapDistance'
        ).textContent =
            lot.distance
                ? `${lot.distance} km`
                : '—';


        document.getElementById(
            'mapCo2'
        ).textContent =
            lot.co2
                ? `${lot.co2} kg`
                : '—';


        document.getElementById(
            'journeyActors'
        ).textContent =
            lot.actor_count ||
            '—';


        document.getElementById(
            'journeyLastEvent'
        ).textContent =
            lot.occurred_at ||
            '—';


        document.getElementById(
            'journeyDistance'
        ).textContent =
            lot.distance
                ? `${lot.distance} km`
                : '—';


        document.getElementById(
            'journeyCo2'
        ).textContent =
            lot.co2
                ? `${lot.co2} kg`
                : '—';

    }


    /*
     * Escape server data before injecting it into popup HTML.
     */

    function escapeHtml(value) {

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    /*
     * Initialize the first lot.
     */

    if (orderedLots.length > 0) {

        updateSelectedLot(
            orderedLots[0],
            0
        );

    }

    function openTransitForm() {
        if (!selectedLot || !selectedLot.id) {
            alert('Sélectionnez d\'abord un lot pour le mettre en transit.');
            return;
        }

        document.getElementById('transitFormPanel').classList.add('visible');
        document.getElementById('transitDestination').value = selectedLot.origin || 'Sfax';
    }

    function closeTransitForm() {
        document.getElementById('transitFormPanel').classList.remove('visible');
    }

    async function submitTransit() {
        if (!selectedLot || !selectedLot.id) {
            alert('Aucun lot sélectionné.');
            return;
        }

        const destination = document.getElementById('transitDestination').value.trim() || selectedLot.origin || 'Sfax';
        const transportMode = document.getElementById('transitMode').value;
        const carrier = document.getElementById('transitCarrier').value.trim() || 'LogiTunis';

        try {
            const response = await fetch(`/traceability/lots/${selectedLot.id}/transit`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    destination,
                    origin: selectedLot.origin || 'Tunisie',
                    transport_mode: transportMode,
                    carrier,
                    departed_at: new Date().toISOString()
                })
            });

            const result = await response.json();

            if (!response.ok || result.success === false) {
                throw new Error(result.message || 'Impossible de mettre le lot en transit.');
            }

            closeTransitForm();
            alert(result.message || 'Lot mis en transit avec succès.');
            window.location.reload();
        } catch (error) {
            alert(error.message || 'Une erreur est survenue.');
        }
    }

    document.getElementById('openTransitFormBtn').addEventListener('click', openTransitForm);
    document.getElementById('mapTransitButton').addEventListener('click', openTransitForm);
    document.getElementById('cancelTransitBtn').addEventListener('click', closeTransitForm);
    document.getElementById('submitTransitBtn').addEventListener('click', submitTransit);

    /*
     * Chart.js analytics.
     *
     * This is intentionally isolated from the map.
     */

    const chartElement =
        document.getElementById(
            'traceActivityChart'
        );


    if (
        chartElement &&
        typeof Chart !== 'undefined'
    ) {

        new Chart(
            chartElement.getContext('2d'),
            {
                type: 'line',

                data: {

                    labels: [
                        'Avr.',
                        'Mai',
                        'Juin',
                        'Juil.',
                        'Août',
                        'Sept.'
                    ],

                    datasets: [
                        {
                            label: 'Événements',

                            data: [
                                420,
                                510,
                                470,
                                620,
                                710,
                                790
                            ],

                            borderColor: '#176b52',

                            backgroundColor:
                                'rgba(23,107,82,.08)',

                            borderWidth: 2.5,

                            fill: true,

                            tension: .35,

                            pointRadius: 3,

                            pointBackgroundColor:
                                '#ffffff',

                            pointBorderColor:
                                '#176b52',

                            pointBorderWidth: 2
                        }
                    ]

                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    plugins: {

                        legend: {
                            display: false
                        },

                        tooltip: {

                            backgroundColor:
                                '#173d35',

                            displayColors: false,

                            padding: 10,

                            cornerRadius: 8,

                            callbacks: {

                                label: function (context) {

                                    return `${context.parsed.y} événements`;

                                }

                            }

                        }

                    },

                    scales: {

                        x: {

                            grid: {
                                display: false
                            },

                            border: {
                                display: false
                            },

                            ticks: {
                                color: '#91a19d',
                                font: {
                                    size: 10,
                                    weight: '600'
                                }
                            }

                        },

                        y: {

                            beginAtZero: true,

                            grid: {
                                color: '#edf2f0'
                            },

                            border: {
                                display: false
                            },

                            ticks: {
                                color: '#91a19d',
                                font: {
                                    size: 10
                                }
                            }

                        }

                    }

                }

            }
        );

    }

});

</script>

</body>
</html>