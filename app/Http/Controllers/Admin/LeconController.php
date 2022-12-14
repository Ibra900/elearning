<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lecon;
use App\Models\Chapitre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class LeconController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nbre = Lecon::all()->count();
        $i = 0;

        $lecons = DB::table('lecons')
                ->join('chapitres', 'chapitres.id', 'lecons.chapitre_id')
                ->select('lecons.*', 'chapitres.name as chapitre', 'chapitres.id as idchapitre')
                ->paginate(20);

        return view('admin.lecons.liste-lecons',compact('lecons','nbre', 'i'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $chapitres = Chapitre::all();
        return view('admin.lecons.create-lecon',[
            'chapitres' =>$chapitres
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate ([
            'name' => ['required', 'string', 'max:255'],
            'content' =>'required'
        ]);

        Lecon::create([
            'name' => $request->name,
            'chapitre_id' => $request->chapitre,
            'content' => $request->content
        ]);

        return redirect()->route('admin.lecons.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lecon  $lecon
     * @return \Illuminate\Http\Response
     */
    public function show(Lecon $lecon)
    {
        $data = DB::table('lecons')
                ->join('chapitres', 'chapitres.id', 'lecons.chapitre_id')
                ->select('lecons.*', 'chapitres.name as chapitre', 'chapitres.id as idchapitre')
                ->where('lecons.id', $lecon->id)
                ->get();
                // dd($data);
        return view('admin.lecons.detail-lecon',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lecon  $lecon
     * @return \Illuminate\Http\Response
     */
    public function edit(Lecon $lecon)
    {
        return view('admin.lecons.edit-lecon',[
            'lecon' => $lecon
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lecon  $lecon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lecon $lecon)
    {
        $lecon->name = $request->name;
        $lecon->content = $request->content;
        $lecon->save();

        return redirect()->route('admin.lecons.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lecon  $lecon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lecon $lecon)
    {
        if( Gate::denies('delete')){
            return redirect()->route('admin.lecons.index');
        }

        $lecon->delete();

        return redirect()->route('admin.lecons.index');
    }
}
