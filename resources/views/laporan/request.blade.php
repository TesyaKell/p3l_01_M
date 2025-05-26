<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Laporan Request Donasi</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        a.button {
            display: inline-block;
            padding: 8px 16px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        a.button:hover {
            background-color: #1e7e34;
        }
    </style>
</head>

<body>
    <h2>Laporan Request Donasi</h2>
    <a href="{{ route('laporan.request.pdf') }}" class="button">Unduh PDF</a>
    <table>
        <thead>
            <tr>
                <th>Organisasi</th>
                <th>Deskripsi Request</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $request)
                <tr>
                    <td>{{ $request->organisasi->nama_organisasi ?? '-' }}</td>
                    <td>{{ $request->desk_request }}</td>
                    <td>{{ $request->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Data request donasi tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
