<div class="chart-card">
    <div class="chart-card-header">
        <div>
            <h3>{{ $title }}</h3>
            @if (!empty($subtitle))
                <small style="color: #78919a; display: block; margin-top: 4px;">{{ $subtitle }}</small>
            @endif
        </div>
        @if (!empty($action))
            <button type="button" class="chart-action-chip {{ $actionClass ?? '' }}">{{ $action }}</button>
        @endif
    </div>
    {{ $slot }}
</div>
