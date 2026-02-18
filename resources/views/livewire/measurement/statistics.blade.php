<div>
    <div wire:ignore.self class="modal fade" id="statisticsModal" tabindex="-1" aria-labelledby="statisticsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">

                <div class="modal-header w-100 d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="modal-title fs-5" id="statisticsModalLabel">
                            Statistics
                            @if($this->device)
                                <span class="text-muted">— {{ $this->device->name }}</span>
                            @endif
                        </h1>
                        <div class="small text-muted">
                            Historical sensor charts
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="btn-group btn-group-sm" role="group" aria-label="Time range">
                            @foreach(['1h' => '1h', '6h' => '6h', '24h' => '24h', '7d' => '7d'] as $value => $label)
                                <button
                                    type="button"
                                    wire:click="$set('range', '{{ $value }}')"
                                    class="btn {{ $range === $value ? 'btn-primary' : 'btn-outline-primary' }}"
                                >{{ $label }}</button>
                            @endforeach
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>

                <div class="modal-body">
                    @if($this->deviceId)
                        <div class="row g-3">
                            @forelse($this->sensors as $sensor)
                                <div class="col-12 col-md-6 col-xl-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <div class="fw-semibold">{{ $sensor->name }}</div>
                                                    <div class="small text-muted">
                                                        key: {{ $sensor->key }}
                                                        @if($sensor->unit_type)
                                                            • unit: {{ $sensor->unit_type }}
                                                        @endif
                                                    </div>
                                                </div>
                                                <span class="badge text-bg-secondary">{{ $range }}</span>
                                            </div>

                                            <div style="position: relative; height: 200px;">
                                                <canvas id="chart-sensor-{{ $sensor->id }}"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center text-muted py-5">
                                        No sensors configured.
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            No device selected.
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
</div>
