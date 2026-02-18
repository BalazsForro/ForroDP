<div>

    @auth

        {{-- ── Welcome banner ─────────────────────────────────────────────── --}}
        <div class="rounded-3 border bg-light p-4 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="mb-1 fw-bold">Welcome back, {{ auth()->user()->name }}!</h2>
                <p class="text-muted mb-0">Monitor and manage all your IoT devices in one place.</p>
            </div>
            <div class="d-flex flex-column align-items-end gap-2">
                <div class="text-muted small">
                    <i class="bi bi-calendar3 me-1"></i>{{ now()->format('F j, Y') }}
                </div>
                <a href="{{ route('devices') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-cpu me-1"></i>Manage Devices
                </a>
            </div>
        </div>

        {{-- ── Stat cards ────────────────────────────────────────────────── --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-primary bg-opacity-10 p-3 flex-shrink-0">
                            <i class="bi bi-cpu text-primary fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold lh-1">{{ $this->stats['total_devices'] }}</div>
                            <div class="text-muted small mt-1">Total Devices</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-success bg-opacity-10 p-3 flex-shrink-0">
                            <i class="bi bi-lightning-charge text-success fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold lh-1">{{ $this->stats['active_devices'] }}</div>
                            <div class="text-muted small mt-1">Active Devices</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-info bg-opacity-10 p-3 flex-shrink-0">
                            <i class="bi bi-diagram-3 text-info fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold lh-1">{{ $this->stats['total_sensors'] }}</div>
                            <div class="text-muted small mt-1">Total Sensors</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-warning bg-opacity-10 p-3 flex-shrink-0">
                            <i class="bi bi-bar-chart text-warning fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold lh-1">{{ $this->stats['today_measurements'] }}</div>
                            <div class="text-muted small mt-1">Readings Today</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Device cards ──────────────────────────────────────────────── --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-semibold">My Devices</h5>
            <a href="{{ route('devices') }}" class="btn btn-sm btn-link text-decoration-none pe-0">
                View all <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-3 mb-4" wire:poll.30s>
            @forelse($this->devices as $device)
                @php
                    $latestMeasurementId = $device->latestState?->measurement_id;
                    $sensorValues        = $this->currentStates->get($latestMeasurementId, collect());
                    $measuredAt          = $device->latestState?->measurement?->created_at;
                    $typeIcons = [
                        1 => 'bi-cpu',
                        2 => 'bi-broadcast',
                        3 => 'bi-server',
                        4 => 'bi-gear',
                    ];
                    $typeIcon = $typeIcons[$device->type] ?? 'bi-gear';
                @endphp

                <div class="col-sm-6 col-xl-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex flex-column">

                            {{-- Device header --}}
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-2 bg-secondary bg-opacity-10 p-2 flex-shrink-0">
                                        <i class="bi {{ $typeIcon }} text-secondary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold lh-sm">{{ $device->name }}</div>
                                        <div class="text-muted small">
                                            {{ \App\Enums\DeviceType::from($device->type)->getDisplayName() }}
                                        </div>
                                    </div>
                                </div>
                                @if($device->is_active)
                                    <span class="badge text-bg-success">Active</span>
                                @else
                                    <span class="badge text-bg-secondary">Inactive</span>
                                @endif
                            </div>

                            {{-- Sensor value pills --}}
                            @if($sensorValues->isNotEmpty())
                                <div class="row g-2 mb-3">
                                    @foreach($sensorValues->take(4) as $val)
                                        <div class="{{ $sensorValues->count() === 1 ? 'col-12' : 'col-6' }}">
                                            <div class="bg-light rounded-2 p-2 text-center">
                                                <div class="fw-semibold lh-1" style="font-size: 1rem;">
                                                    {{ $val->value ?? '—' }}
                                                    @if($val->sensor?->unit_type && !is_null($val->value))
                                                        <span class="text-muted fw-normal" style="font-size: 0.72rem;">{{ $val->sensor->unit_type }}</span>
                                                    @endif
                                                </div>
                                                <div class="text-muted mt-1 text-truncate" style="font-size: 0.7rem;">
                                                    {{ $val->sensor?->name }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if($sensorValues->count() > 4)
                                        <div class="col-12 text-center text-muted small">
                                            +{{ $sensorValues->count() - 4 }} more sensor{{ $sensorValues->count() - 4 > 1 ? 's' : '' }}
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="flex-grow-1 d-flex align-items-center justify-content-center py-3 text-muted">
                                    <div class="text-center">
                                        <i class="bi bi-inbox fs-3 d-block mb-1 opacity-25"></i>
                                        <span class="small">No data yet</span>
                                    </div>
                                </div>
                            @endif

                            {{-- Last updated --}}
                            <div class="text-muted small mb-3 mt-auto">
                                <i class="bi bi-clock me-1"></i>
                                @if($measuredAt)
                                    {{ $measuredAt->diffForHumans() }}
                                @else
                                    Never measured
                                @endif
                            </div>

                            {{-- Action buttons --}}
                            <div class="d-flex gap-2">
                                <button
                                    class="btn btn-sm btn-outline-primary flex-fill"
                                    wire:click="$dispatch('open-measurement', { deviceId: {{ $device->id }} })"
                                >
                                    <i class="bi bi-activity"></i> Live
                                </button>
                                <button
                                    class="btn btn-sm btn-outline-secondary flex-fill"
                                    wire:click="$dispatch('open-statistics', { deviceId: {{ $device->id }} })"
                                >
                                    <i class="bi bi-bar-chart-line"></i> Stats
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center text-muted py-5">
                            <i class="bi bi-cpu fs-1 d-block mb-3 opacity-25"></i>
                            <p class="mb-3">No devices yet. Start by adding your first one.</p>
                            <a href="{{ route('devices') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-lg me-1"></i>Add Device
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- ── Recent activity ───────────────────────────────────────────── --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-semibold">Recent Activity</h5>
            <span class="text-muted small">Last 10 measurements</span>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:80px;">#</th>
                            <th style="width:210px;">Time</th>
                            <th>Device</th>
                            <th style="width:90px;">Values</th>
                            <th style="width:90px;">Valid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->recentActivity as $m)
                            <tr>
                                <td class="text-muted">{{ $m->id }}</td>
                                <td>
                                    <div>{{ $m->created_at->format('H:i:s') }}</div>
                                    <div class="text-muted small">{{ $m->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="fw-semibold">{{ $m->device?->name }}</td>
                                <td>
                                    <span class="badge text-bg-light border">{{ $m->values_count }}</span>
                                </td>
                                <td>
                                    @if($m->is_valid)
                                        <span class="badge text-bg-success">valid</span>
                                    @else
                                        <span class="badge text-bg-danger">invalid</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No measurements yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modals (separate Livewire islands, safe with wire:poll) --}}
        <livewire:measurement.show/>
        <livewire:measurement.statistics/>

    @else

        {{-- ── Guest landing ─────────────────────────────────────────────── --}}
        <div class="text-center py-5">
            <div class="rounded-3 bg-primary bg-opacity-10 d-inline-flex p-4 mb-4">
                <i class="bi bi-cpu text-primary" style="font-size: 3rem;"></i>
            </div>
            <h1 class="display-5 fw-bold mb-3">IoT Device Monitor</h1>
            <p class="lead text-muted mb-4 mx-auto" style="max-width: 480px;">
                Track, monitor, and visualize your IoT sensor data in real time. Connect any device — Arduino, ESP32, Raspberry Pi, and more.
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('login') }}" class="btn btn-primary px-5">Sign In</a>
                <a href="{{ route('register') }}" class="btn btn-outline-secondary px-5">Create Account</a>
            </div>
        </div>

    @endauth

</div>
