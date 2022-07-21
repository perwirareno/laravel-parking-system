<!DOCTYPE html>
<html>

<head>
    <title>Laporan Parkir PDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <style type="text/css">
        table tr td,
        table tr th {
            font-size: 12pt;
            text-align: center;
            border: 1px solid;
            width: 100%;
            border-collapse: collapse;
        }

        .center {
            margin-left: auto;
            margin-right: auto;
        }

        h5 {
            font-size: 14pt;
        }
    </style>
    <center>
        @if($isset_data == 1)
        <h5>Laporan data parkir di tanggal {{$start_date}} sampai {{$end_date}}</h5>
        @else
        <h5>Laporan seluruh data parkir</h5>
        @endif
    </center>

    <table class='table table-bordered center'>
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Polisi</th>
                <th>Kode Unik</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Biaya</th>
            </tr>
        </thead>
        <tbody>
            @php $i=1 @endphp
            @foreach($dataparkir as $p)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{$p->no_polisi}}</td>
                <td>{{$p->kode_unik}}</td>

                <?php
                    $datetime = strtotime($p->jam_masuk);
                    $jam_masuk = date('d-m-Y | H:i:s', $datetime); 
                ?>
                <td>{{$jam_masuk}}</td>

                <?php
                    if (isset($p->jam_keluar)) {
                        $datetime = strtotime($p->jam_keluar);
                        $jam_keluar = date('d-m-Y | H:i:s', $datetime);
                    } else {
                        $jam_keluar = '-';
                    } 
                ?>
                <td>{{$jam_keluar}}</td>
                
                <?php
                    $biaya = "Rp " .number_format($p->biaya, 0, ',', '.'); 
                ?>
                <td style="text-align: left;">{{$biaya}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>