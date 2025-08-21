<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dance;
class Studio extends Controller
{
    public function dance(Request $request) {

        $search         = $request->input('search');
        $page           = $request->input('page', 1);
        $perPage        = 12;
        
        $data['dances'] = Dance::when($search, function($q, $search){
                            $q->where('name', 'LIKE', '%' . $search . '%');
                        })
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
}
