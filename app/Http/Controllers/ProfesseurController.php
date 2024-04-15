<?php

namespace App\Http\Controllers;

use App\Models\Professeur;
use App\Models\Setting;
use Illuminate\Http\Request;
use PDF;

class ProfesseurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('professeur.index');
    }

    public function data()
    {
        $professeur = Professeur::orderBy('kode_professeur')->get();

        return datatables()
            ->of($professeur)
            ->addIndexColumn()
            ->addColumn('select_all', function ($salle) {
                return '
                    <input type="checkbox" name="id_professeur[]" value="'. $salle->id_professeur .'">
                ';
            })
            ->addColumn('kode_professeur', function ($professeur) {
                return '<span class="label label-success">'. $professeur->kode_professeur .'<span>';
            })
            ->addColumn('aksi', function ($professeur) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('member.update', $professeur->id_professeur) .'`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('member.destroy', $member->id_professeur) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'select_all', 'kode_professeur'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $professeur = Professeur::latest()->first() ?? new Member();
        $kode_professeur = (int) $member->kode_member +1;

        $professeur = new Professeur();
        $professeur->kode_professeur = tambah_nol_didepan($kode_professeur, 5);
        $professeur->nama = $request->nama;
        $professeur->telepon = $request->telepon;
        $professeur->alamat = $request->alamat;
        $professeur->save();

        return response()->json('Data saved successfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $professeur = Professeur::find($id);

        return response()->json($professeur);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $professeur = professeur::find($id)->update($request->all());

        return response()->json('Data saved successfully', 200);
    }
    // visit "codeastro" for more projects!
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $professeur = Professeur::find($id);
        $professeur->delete();

        return response(null, 204);
    }

    public function cetakprofesseur(Request $request)
    {
        $dataprofesseur = collect(array());
        foreach ($request->id_professeur as $id) {
            $professeur = Professeur::find($id);
            $dataprofesseur[] = $professeur;
        }

        $dataprofesseur = $dataprofesseur->chunk(2);
        $setting    = Setting::first();

        $no  = 1;
        $pdf = PDF::loadView('professeur.cetak', compact('dataprofesseur', 'no', 'setting'));
        $pdf->setPaper(array(0, 0, 566.93, 850.39), 'potrait');
        return $pdf->stream('professeur.pdf');
    }
}