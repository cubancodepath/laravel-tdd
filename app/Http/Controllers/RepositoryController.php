<?php

namespace App\Http\Controllers;

use App\Http\Requests\RepositoryRequest;
use App\Models\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepositoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $repositories = $request->user()->repositories;
        return view("repositories.index", compact("repositories"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("repositories.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RepositoryRequest $request)
    {
        $request
            ->user()
            ->repositories()
            ->create($request->all());

        return redirect("repositories");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Repository  $repository
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Repository $repository)
    {
        if ($request->user()->id != $repository->user->id) {
            abort(403);
        }

        return view("repositories.show", compact("repository"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Repository  $repository
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Repository $repository)
    {
        if ($request->user()->id != $repository->user->id) {
            abort(403);
        }

        return view("repositories.edit", compact("repository"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Repository  $repository
     * @return \Illuminate\Http\Response
     */
    public function update(RepositoryRequest $request, Repository $repository)
    {
        if ($request->user()->id != $repository->user->id) {
            abort(403);
        }

        $repository->update($request->all());

        return redirect()->route("repositories.edit", $repository);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Repository  $repository
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Repository $repository)
    {
        if ($request->user()->id != $repository->user->id) {
            abort(403);
        }
        $repository->delete();

        return redirect()->route("repositories.index");
    }
}
