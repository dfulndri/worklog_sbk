<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Daily Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #222;
        }

        h1 {
            font-size: 16px;
            margin-bottom: 4px;
        }

        p.subtitle {
            margin-top: 0;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Laporan Aktivitas Harian — PT SBK</h1>
    <p class="subtitle">Dicetak pada {{ now()->format('d M Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Karyawan</th>
                <th>Klien</th>
                <th>Jenis Dokumen</th>
                <th>Progress</th>
                <th>Uraian</th>
                <th>Kendala</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reports as $report)
                <tr>
                    <td>{{ $report->report_date->format('d-m-Y') }}</td>
                    <td>{{ $report->user->name ?? '-' }}</td>
                    <td>{{ $report->jobTask->client->name ?? '-' }}</td>
                    <td>{{ $report->jobTask->documentType->name ?? '-' }}</td>
                    <td>{{ $report->progress }}%</td>
                    <td>{{ $report->description }}</td>
                    <td>{{ $report->obstacle ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
