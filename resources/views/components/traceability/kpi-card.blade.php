<div class="stat-card">
    <div class="stat-icon" style="background: {{ $iconBg ?? '#e1f4e5' }}; color: {{ $iconColor ?? '#14733c' }};">
        {!! $icon ?? '' !!}
    </div>
    <span class="stat-title">{{ $title }}</span>
    <span class="stat-number">{{ $value }}</span>
    <span class="stat-change positive">{{ $trend ?? '+12%' }}</span>
    <span class="stat-period">{{ $period ?? 'vs mois dernier' }}</span>
</div>
