<div>
    <div wire:ignore.self class="modal fade" id="measurementModal" tabindex="-1" aria-labelledby="measurementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">

                <div class="modal-header">
                    <div>
                        <h1 class="modal-title fs-5" id="measurementModalLabel">
                            Measurements
                            @if($this->device)
                                <span class="text-muted">— {{ $this->device->name }}</span>
                            @endif
                        </h1>
                        <div class="small text-muted">
                            Current state of all sensors (auto refresh)
                        </div>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    {{-- Live: current state --}}
                    <div wire:poll.1s>
                        <div class="row g-3">
                            @forelse($this->currentState as $item)
                                <div class="col-12 col-md-6 col-xl-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <div class="fw-semibold">{{ $item->sensor->name }}</div>
                                                    <div class="small text-muted">
                                                        key: {{ $item->sensor->key }}
                                                        @if($item->sensor->unit_type)
                                                            • unit: {{ $item->sensor->unit_type }}
                                                        @endif
                                                    </div>
                                                </div>

                                                <div>
                                                    @if(is_null($item->is_valid))
                                                        <span class="badge text-bg-secondary">—</span>
                                                    @elseif($item->is_valid)
                                                        <span class="badge text-bg-success">valid</span>
                                                    @else
                                                        <span class="badge text-bg-danger">invalid</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="mt-3 d-flex align-items-baseline gap-2">
                                                <div class="display-6 mb-0 fw-semibold">
                                                    {{ $item->value ?? '—' }}
                                                </div>
                                                @if($item->sensor->unit_type && !is_null($item->value))
                                                    <div class="text-muted">{{ $item->sensor->unit_type }}</div>
                                                @endif
                                            </div>

                                            <div class="small text-muted mt-2">
                                                @if($item->measured_at)
                                                    measured: {{ $item->measured_at->format('Y-m-d H:i:s') }}
                                                    <span class="ms-2">({{ $item->measured_at->diffForHumans() }})</span>
                                                @else
                                                    no data yet
                                                @endif
                                            </div>

                                            <div class="small text-muted mt-2">
                                                @if($item->measurement_id)
                                                    measurement #{{ $item->measurement_id }}
                                                    <span class="ms-2">value #{{ $item->measurement_value_id }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center text-muted py-5">
                                        No sensors / no data.
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Optional: live feed table --}}
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-semibold">Live feed (latest {{ $limitLatestRows }} rows)</div>
                        <div class="small text-muted">refreshes with the cards</div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle">
                            <thead>
                            <tr>
                                <th style="width: 90px;">ID</th>
                                <th style="width: 220px;">Time</th>
                                <th>Sensor</th>
                                <th>Value</th>
                                <th style="width: 120px;">Valid</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($this->latestFeed as $row)
                                <tr>
                                    <td>#{{ $row->id }}</td>
                                    <td class="text-muted">{{ $row->measurement?->created_at?->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $row->sensor?->name ?? ('sensor #'.$row->sensor_id) }}</td>
                                    <td class="fw-semibold">{{ $row->value }}</td>
                                    <td>
                                        @php($valid = $row->measurement?->is_valid)
                                        @if(is_null($valid))
                                            <span class="badge text-bg-secondary">—</span>
                                        @elseif($valid)
                                            <span class="badge text-bg-success">valid</span>
                                        @else
                                            <span class="badge text-bg-danger">invalid</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No feed data.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
</div>
