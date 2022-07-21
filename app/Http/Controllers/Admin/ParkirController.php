<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use App\Models\Parkir;
use Carbon\Carbon;
use DataTables;
use PDF;

class ParkirController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::table('master_parkir')->select('*')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jam_masuk', function ($data) {
                    $jam_masuk = strtotime($data->jam_masuk);
                    $datetime = date('d-m-Y | H:i:s', $jam_masuk);

                    return $datetime;
                })
                ->addColumn('jam_keluar', function ($data) {
                    if (isset($data->jam_keluar)) {
                        $jam_keluar = strtotime($data->jam_keluar);
                        $datetime = date('d-m-Y | H:i:s', $jam_keluar);
                    } else {
                        $datetime = '-';
                    }

                    return $datetime;
                })
                ->addColumn('biaya', function ($data) {
                    $biaya = "Rp " . number_format($data->biaya, 0, ',', '.');

                    return $biaya;
                })
                ->addColumn('kode_unik', function ($data) {
                    $kodeunik = "";
                    if ($data->inorout == 0) {
                        $kodeunik = "-";
                    } else {
                        $kodeunik = $data->kode_unik;
                    }
                    return $kodeunik;
                })
                ->addColumn('action', function ($row) {
                    $btn_update = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="btn bg-success btn-sm editParkir">Update <i class="fas fa-edit"></i></a>';
                    $btn_delete = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="btn bg-danger btn-sm hapusParkir">Hapus <i class="fas fa-trash"></i></a>';

                    if ($row->inorout == 0) {
                        $btn = '<center>' . $btn_update . '&nbsp' . $btn_delete . '</center>';
                    } else {
                        $btn = '<center>' . $btn_delete . '</center>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $judul = "Master Parkir";
        return view('admin/parkir/index', compact('judul'));
    }

    public function cetakLaporan($daterange)
    {
        // Output daterange:
        // "start_date=01-07-2022&end_date=09-07-2022"

        $count_str = strlen($daterange);

        if ($count_str > 21) {
            $isset_data = 1; // Data exist
            $startdate = substr($daterange, 11, 10);
            $enddate = substr($daterange, -10);

            $start_date = date("Y-m-d", strtotime($startdate));
            $end_date = date("Y-m-d", strtotime($enddate));

            $dataparkir = Parkir::whereDate('jam_masuk', '>=', $start_date)
                ->whereDate('jam_masuk', '<=', $end_date)
                ->orderBy('jam_masuk', 'ASC')
                ->get();
        } else {
            $isset_data = 0; // Data didn't exist
            $startdate = null;
            $enddate = null;
            $dataparkir = Parkir::all();
        }

        $pdf = PDF::loadview('admin/parkir/report', [
            'dataparkir' => $dataparkir,
            'isset_data' => $isset_data,
            'start_date' => $startdate,
            'end_date' => $enddate
        ]);

        return $pdf->stream('Laporan Parkir.pdf');
    }

    public function store(Request $request)
    {
        $jam = Carbon::now()->toDateTimeString();
        if (!empty($request->no_polisi)) {
            Parkir::updateOrCreate(
                [
                    'id' => $request->id
                ],
                [
                    'no_polisi' => $request->no_polisi,
                    'kode_unik' => 0,
                    'inorout' => $request->inorout,
                    'jam_masuk' => $jam,
                    'jam_keluar' => null,
                    'biaya' => 3000
                ]
            );
            return response()->json(['success' => 'Parkir saved successfully.']);
        } else {
            Parkir::updateOrCreate(
                [
                    'id' => $request->id
                ],
                [
                    'no_polisi' => $request->no_polisi,
                    'kode_unik' => 1,
                    'inorout' => $request->inorout,
                    'jam_masuk' => $jam,
                    'jam_keluar' => null,
                    'biaya' => 3000
                ]
            );
            return response()->json(['success' => 'Parkir saved successfully.']);
        }
    }

    private function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function update($id)
    {
        $jam = Carbon::now()->toDateTimeString();

        $Parkir = Parkir::find($id);

        $biaya = 0;
        $jammasuk = $Parkir->jam_masuk;
        $jamkeluar = $Parkir->jam_keluar;

        $date_in = date_create($jammasuk);
        $date_out = date_create($jamkeluar);

        $diff = date_diff($date_in, $date_out)->format('%h');
        $additional = $diff * 3000;

        if ($diff == 0 || $diff == 1) {
            $biaya = $Parkir->biaya;
        } else {
            $biaya = $additional;
        }

        $Parkir = Parkir::where('id', $id)->update([
            'kode_unik' => $this->generateRandomString(),
            'inorout' => 1,
            'jam_keluar' => $jam,
            'biaya' => $biaya
        ]);

        return response()->json(['success' => 'Data Parkir updated successfully.']);
    }

    public function destroy($id)
    {
        Parkir::find($id)->delete();
        return response()->json(['success' => 'Data Parkir deleted successfully.']);
    }
}
