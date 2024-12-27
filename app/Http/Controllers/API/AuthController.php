<?php

namespace App\Http\Controllers\API;

use App\Models\Agen;
use App\Models\Desa;
use App\Models\User;
use App\Models\Laporan;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use App\Models\Kagetorikejadian;
use App\Models\Kategorikejadian;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class AuthController extends Controller
{
    public function loginLevel1(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->where('role','Level1')->first();
        if (!$user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Kombinasi email dan password tidak sesuai',
            ], 422);
        }

        $token = $user->createToken('API Token')->plainTextToken;
        $namaAgen = Agen::where('id', $user->id_model)->first()->nama;

        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
            'message' => 'Selamat datang kembali '.$namaAgen,
        ]);
    }

    public function profilAgen(Request $request)
    {
        $user = User::where('id', $request->user()->id)->where('role','Level1')->where('status','Active')->first();
        $dataAgen = Agen::join('kecamatans','kecamatans.id','agens.kecamatan_id')
            ->select('agens.*','kecamatans.nama_kecamatan')
            ->where('agens.id', $request->user()->id_model)->first();
        return response()->json([
            'success' => true,
            'data' => $dataAgen,
            'message' => 'Profil Agen'
        ]);
    }

    public function listPengumuman(Request $request)
    {
      $pengumunans = Pengumuman::where('status','aktif')->get();
      return response()->json([
            'success' => true,
            'data' => $pengumunans,
            'message' => 'Data pengumuman berhasil diambil',
        ]);
    }

    public function listKategoriKejadian(Request $request)
    {
        $kategori = Kategorikejadian::orderBy('nama_kategori', 'ASC')->get();
        return response()->json([
            'success' => true,
            'data' => $kategori,
            'message' => 'Data kategori kejadian berhasil diambil',
        ]);
    }

    public function listDesa(Request $request)
    {
       $kecamatanId = Agen::where('id', $request->user()->id_model)->first()->kecamatan_id;
       $desa = Desa::where('kecamatan_id', $kecamatanId)->get();
        return response()->json([
              'success' => true,
              'data' => $desa,
              'message' => 'Data desa berhasil diambil',
          ]);
    }

    public function kirimLaporan(Request $request)
    {
      $user = $request->user();
      $agen = Agen::where('id', $user->id_model)->first();

      $request->validate([
          'kategorikejadian_id' => 'required',
          'desa_id' => 'required',
          'tanggalkejadian' => 'required',
          'lokuskejadian' => 'required',
          'isilaporan' => 'required',
      ]);

      //simpan laporan ke database
      try {
        $laporan = new Laporan();
        $laporan->id_model = $user->id_model;
        $laporan->kategorikejadian_id = $request->kategorikejadian_id;
        $laporan->kecamatan_id = $agen->kecamatan_id;
        $laporan->desa_id = $request->desa_id;
        $laporan->tanggal_kejadian = $request->tanggalkejadian;
        $laporan->tanggal_laporan = date('Y-m-d');
        $laporan->lokus_kejadian = $request->lokuskejadian;
        $laporan->isi_laporan = $request->isilaporan;
        $laporan->status = 'Menunggu Konfirmasi';
        $laporan->save();
  
        return response()->json([
            'success' => true,
            'data' => $laporan,
            'message' => 'Laporan berhasil dikirim',
        ])        ;
      } catch (\Throwable $th) {
        return response()->json([
            'success' => false,
            'data' => null,
            'message' => 'Laporan gagal dikirim',
        ], 422);
      }
    }

    public function listLaporan(Request $request)
    {
        $user = $request->user();
        $agen = Agen::where('id', $user->id_model)->first();
        $laporan = Laporan::join('kategorikejadians','kategorikejadians.id','laporans.kategorikejadian_id')
            ->join('desas','desas.id','laporans.desa_id')
            ->select('laporans.*','kategorikejadians.nama_kategori','desas.nama_desa')
            ->where('laporans.id_model', $user->id_model)
            ->orderBy('laporans.tanggal_kejadian', 'DESC')
            ->get();
        return response()->json([
            'success' => true,
            'data' => $laporan,
            'message' => 'Data laporan berhasil diambil',
        ]);
    }


}
