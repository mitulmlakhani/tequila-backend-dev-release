@extends('layouts.master')
@section('title', 'Sync Dashboard')

@section('content')
<div class="wrapper home-section d-flex flex-column" style="height:100vh;">
    <div class="container-fluid p-4 flex-grow-1 d-flex flex-column">

        {{-- Dashboard Info --}}
        <div class="mb-4">
            <h3>Sync Dashboard</h3>
            <p>Last Synced: <span id="last-sync">{{ $lastSyncedAt ?? 'Never' }}</span></p>
            <p>Total Records: {{ $totalRecords }}, Synced: {{ $syncedRecords }}, Completion: {{ $percentage }}%</p>
        </div>

        {{-- Sync Controls --}}
        <div class="mb-4 d-flex align-items-center">
            <button id="start-sync" class="btn btn-primary mr-3">Start Full Sync</button>
            <span id="sync-status" class="text-info">Idle</span>
        </div>

        {{-- Logs Section --}}
        <div class="flex-grow-1 d-flex flex-column">
            <h5>Sync Logs</h5>
            <div id="sync-logs" class="sync-log-box flex-grow-1">
                Loading logs...
            </div>
        </div>

    </div>
</div>

{{-- Styles --}}
<style>
/* Full-page wrapper */
html, body {
    height: 100%;
    margin: 0;
}

/* Logs box */
.sync-log-box {
    background-color: #1e1e1e; /* Dark console-like background */
    color: #d4d4d4; /* Default text color */
    font-family: 'Courier New', Courier, monospace;
    font-size: 13px;
    padding: 10px;
    border: 1px solid #333;
    overflow-y: auto;
    white-space: pre-wrap; /* Preserve line breaks and spacing */
}

/* Colored log levels */
.sync-log-box .info { color: #9cdcfe; }
.sync-log-box .error { color: #f44747; }
.sync-log-box .warning { color: #ff8800; }
.sync-log-box .success { color: #6a9955; }

/* Make the logs fill the remaining space */
.wrapper.home-section { display: flex; flex-direction: column; height: 100%; }
.container-fluid.flex-grow-1 { display: flex; flex-direction: column; flex-grow: 1; }
.flex-grow-1.d-flex.flex-column { flex-grow: 1; display: flex; flex-direction: column; }
</style>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const startBtn = document.getElementById('start-sync');
    const statusText = document.getElementById('sync-status');
    const logsDiv = document.getElementById('sync-logs');
    const lastSyncSpan = document.getElementById('last-sync');

    if (!startBtn) return;

    // Start Sync Button
    startBtn.addEventListener('click', function (e) {
        e.preventDefault();
        statusText.textContent = 'Queueing sync...';

        fetch("{{ route('sync.dashboard.start') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
        })
        .then(res => res.json())
        .then(data => {
            statusText.textContent = data.message;
        })
        .catch(err => {
            statusText.textContent = 'Error starting sync!';
        });
    });

    // Poll logs every 3 seconds
    setInterval(() => {
        fetch("{{ route('sync.dashboard.logs') }}")
            .then(res => res.json())
            .then(logs => {
                // Reverse logs so latest is at the top and limit to 200
                const latestLogs = logs.reverse().slice(0, 200);

                // Format logs by type
                const formattedLogs = latestLogs.map(line => {
                    if (line.includes('[ERROR]')) return `<span class="error">${line}</span>`;
                    if (line.includes('[WARNING]')) return `<span class="warning">${line}</span>`;
                    if (line.includes('[INFO]')) return `<span class="info">${line}</span>`;
                    if (line.includes('[SUCCESS]')) return `<span class="success">${line}</span>`;
                    return line;
                });

                logsDiv.innerHTML = formattedLogs.join('<br>');
                logsDiv.scrollTop = 0; // Keep scroll at top so latest logs are visible

                // Update status and last sync
                const lastLog = latestLogs[0] || '';
                if (lastLog.includes('Full sync completed')) {
                    statusText.textContent = 'Sync Completed';
                    lastSyncSpan.textContent = new Date().toLocaleString();
                }
            });
    }, 3000);

});
</script>
@endsection
