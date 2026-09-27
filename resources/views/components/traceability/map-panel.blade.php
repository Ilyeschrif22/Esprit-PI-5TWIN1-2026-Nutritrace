<div class="chart-card traceability-map-card">
    <div class="chart-card-header">
        <div>
            <h3>Carte de traçabilité territoriale</h3>
            <small style="color: #78919a; display: block; margin-top: 4px;">Parcours géographique des événements enregistrés</small>
        </div>
        <button type="button" class="chart-action-chip primary">Marquer en transit</button>
    </div>

    <div class="traceability-map-visual">
        <svg viewBox="0 0 800 420" preserveAspectRatio="xMidYMid meet" aria-label="Map de la Tunisie">
            <path d="M120 40 C170 60, 220 90, 280 90 L370 110 L420 180 L500 210 L560 170 L620 210 L660 330 L610 380 L500 350 L390 365 L290 330 L190 310 L120 245 L90 180 Z" fill="rgba(255,255,255,0.25)" stroke="rgba(12,66,56,0.15)" stroke-width="2"/>
            <path d="M180 100 L220 160 L260 200 L340 210 L390 170 L430 200 L470 250 L510 230 L550 270 L610 290" fill="none" stroke="rgba(23,107,82,0.55)" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M160 270 L250 160 L320 250 L400 300 L540 260" fill="none" stroke="rgba(57,120,168,0.75)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>

        <div class="map-pin" style="left: 30%; top: 35%;">
            <span class="map-pin-badge producer">1</span>
            <span class="map-pin-label">LOT-WAF3YGAH<small>Production</small></span>
        </div>
        <div class="map-pin" style="left: 42%; top: 48%;">
            <span class="map-pin-badge transformer">2</span>
            <span class="map-pin-label">Stockage<small>Transformation</small></span>
        </div>
        <div class="map-pin" style="left: 55%; top: 42%;">
            <span class="map-pin-badge distributor">3</span>
            <span class="map-pin-label">Transport<small>En transit</small></span>
        </div>
        <div class="map-pin" style="left: 66%; top: 55%;">
            <span class="map-pin-badge producer">4</span>
            <span class="map-pin-label">Distribution<small>Livraison</small></span>
        </div>

        <div class="map-info-panel">
            <div class="map-info-row">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6v12a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6"/><path d="M3 6l9 6 9-6"/></svg>
                <div>
                    <span>Parcours</span>
                    <strong>LOT-WAF3YGAH</strong>
                </div>
            </div>
        </div>

        <button type="button" class="map-locate-btn" aria-label="Localiser">◎</button>
        <div class="map-controls">
            <button type="button" class="map-control-btn" aria-label="Zoom +">+</button>
            <button type="button" class="map-control-btn" aria-label="Zoom -">−</button>
        </div>
    </div>
</div>
