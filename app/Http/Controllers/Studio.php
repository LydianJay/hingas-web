<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dance;
use Illuminate\Support\Facades\DB;
use Throwable;

class Studio extends Controller
{
    public function dance(Request $request) {

        $search         = $request->input('search');
        $page           = $request->input('page', 1);
        $perPage        = 12;
        
        $data['dances'] = Dance::when($search, function($q, $search){
                            $q->where('name', 'LIKE', '%' . $search . '%');
                        })
                        ->where('is_active', 1)
                        ->offset(($page - 1) * $perPage)
                        ->limit($perPage)
                        ->get();
        
        $data['page']               = $page;
        $data['perPage']            = $perPage;
        $data['count']              = Dance::count();
        $data['totalPages']         = ceil($data['count'] / $perPage);
        $data['search']             = $search;


            

            
        return view('pages.studio.danceview', $data);
    }


    public function create_dance(Request $request) {
        
        $validated = $request->validate([
            'name'          => 'required',
            'session_count' => 'required',
            'price'         => 'required',
        ]);


        DB::beginTransaction();


        try {

            Dance::create([
                'name'              => $validated['name'],
                'session_count'     => $validated['session_count'],
                'price'             => $validated['price'],
            ]);

            
        } catch(Throwable $e) {
            
            DB::rollBack();

            return redirect()
                ->route('dance')
                        ->with('status', [
                    'alert' => 'alert-danger',
                    'msg'   => 'Error Creating Dance | ' . $e->getMessage(),
                ]);
        }
        
        DB::commit();


        return redirect()
                ->route('dance')
                ->with('status', [
                    'alert' => 'alert-success',
                    'msg'   => 'Dance created!',
                ]);

    }


    public function edit_dance(Request $request) {
        $validated = $request->validate([
            'id'            => 'required',
            'name'          => 'required',
            'session_count' => 'required',
            'price'         => 'required',
        ]);



        
        DB::beginTransaction();


        try {

            $dance = Dance::find($validated['id']);

            if(!$dance) {
                DB::rollBack();
                return redirect()
                ->route('dance')
                        ->with('status', [
                    'alert' => 'alert-danger',
                    'msg'   => 'Invalid dance!'
                ]);
            } else {
                $dance->update([
                    'name'              => $validated['name'],
                    'session_count'     => $validated['session_count'],
                    'price'             => $validated['price'],
                ]);
            }

            
            
        } catch(Throwable $e) {
            
            DB::rollBack();

            return redirect()
                ->route('dance')
                        ->with('status', [
                    'alert' => 'alert-danger',
                    'msg'   => 'Error Editing Dance | ' . $e->getMessage(),
                ]);
        }
        
        DB::commit();


        return redirect()
                ->route('dance')
                ->with('status', [
                    'alert' => 'alert-success',
                    'msg'   => 'Dance Edited!',
                ]);


    }


    public function delete_dance(Request $request) {
        $validated = $request->validate([
            'id'            => 'required',
        ]);



        DB::beginTransaction();


        try {

            $dance = Dance::find($validated['id']);

            if(!$dance) {
                DB::rollBack();
                return redirect()
                ->route('dance')
                        ->with('status', [
                    'alert' => 'alert-danger',
                    'msg'   => 'Invalid dance!'
                ]);
            } else {
                $dance->update([
                    'is_active'              => 0,
                ]);
            }

            
            
        } catch(Throwable $e) {
            
            DB::rollBack();

            return redirect()
                ->route('dance')
                        ->with('status', [
                    'alert' => 'alert-danger',
                    'msg'   => 'Error Deleting Dance | ' . $e->getMessage(),
                ]);
        }
        
        DB::commit();


        return redirect()
                ->route('dance')
                ->with('status', [
                    'alert' => 'alert-warning',
                    'msg'   => 'Dance Deleted!',
                ]);
    }

}
