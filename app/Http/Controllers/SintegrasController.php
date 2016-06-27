<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Sintegra;
use DB;
use Auth;

class SintegrasController extends Controller
{
    /**
     * Check for an authenticated user before access any action
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $originalInput = Request::input();

        $request = Request::create('/api', 'POST');

        Request::replace($request->input());

        $sintegras = json_decode(\Route::dispatch($request)->getContent());

        Request::replace($originalInput);

        return view('sintegras.index', ['sintegras' => $sintegras->data]);
    }

    public function findCnpj()
    {
        $resultado_json = "Faça uma busca para ver aqui seu resultado.";
        return view('sintegras.find-cnpj', ['resultado_json' => $resultado_json]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $cnpj = \Input::get('cnpj');

            $originalInput = Request::input();

            $request = Request::create('/api/find-cnpj', 'POST', ['cnpj' => $cnpj]);

            Request::replace($request->input());

            $sintegras = json_decode(\Route::dispatch($request)->getContent());

            Request::replace($originalInput);

            if ($sintegras->status == 'error')
              return redirect()->route('sintegra/find-cnpj')->withInput()->with('error', $sintegras->message);

            return view('sintegras.find-cnpj', ['resultado_json' => $sintegras->data]);
        } catch (\Exception $e) {
            return redirect()->route('sintegra/find-cnpj')->withInput()->with('error', 'Falha ao tentar buscar CNPJ.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $originalInput = Request::input();

        $request = Request::create('/api/destroy', 'POST', ['id' => $id]);

        Request::replace($request->input());

        $instance = json_decode(\Route::dispatch($request)->getContent());

        Request::replace($originalInput);

        return redirect()->route('sintegra')->withInput()->with($instance->status, $instance->message);
    }
}
