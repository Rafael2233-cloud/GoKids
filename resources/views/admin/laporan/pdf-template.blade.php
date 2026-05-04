<!DOCTYPE html>
<html>

<head>
    <title>Laporan Pertumbuhan Anak</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            color: #1f2937;
        }

        .summary {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f3f4f6;
        }
    </style>
</head>

<body>

    <h2>Laporan Tumbuh Kembang Anak - GoKids</h2>

    <div class="summary">
        <p><strong>Total Data:</strong> {{ $totalRecords }} rekaman</p>
        <p><strong>Rata-rata Berat:</strong> {{ number_format($avgWeight, 1) }} kg | <strong>Rata-rata Tinggi:</strong>
            {{ number_format($avgHeight, 1) }} cm</p>
        <p><strong>Tanggal Cetak:</strong> {{ $tanggalCetak }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Anak</th>
                <th>Orang Tua</th>
                <th>Berat (kg)</th>
                <th>Tinggi (cm)</th>
                <th>L. Kepala (cm)</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $i => $r)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $r->child->name ?? '-' }}</td>
                    <td>{{ $r->child->user->name ?? '-' }}</td>
                    <td>{{ $r->weight }}</td>
                    <td>{{ $r->height }}</td>
                    <td>{{ $r->head_circumference ?? '-' }}</td>
                    <td>{{ $r->recorded_at->format('d M Y') }}</td>
                    <td>{{ $r->nutritional_status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
