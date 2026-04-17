<h2>Data Pelanggan</h2>

<a href="/admin">< HOME</a>
<a href="/instalasi">+ Tambah Pelanggan</a>

<table border="1" cellpadding="10">
    <tr>
        <th>Nama</th>
        <th>Site</th>
        <th>Layanan</th>
    </tr>

    @foreach($pelanggan as $p)
    <tr>
        <td>{{ $p->nama }}</td>
        <td>{{ $p->site->nama_site ?? '-' }}</td>
        <td>{{ $p->layanan->nama_paket ?? '-' }}</td>
    </tr>
    @endforeach
</table>