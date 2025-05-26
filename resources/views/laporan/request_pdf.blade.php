<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Laporan Request Donasi (PDF)</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Laporan Request Donasi</h2>
    <table>
        <thead>
            <tr>
                <th>Deskripsi Request</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $request)
                <tr>
                    <td>{{ $request->desk_request }}</td>
                    <td>{{ $request->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="text-align:center;">Data request donasi tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
