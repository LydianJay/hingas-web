<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Reservation;

use Illuminate\Support\Facades\DB;
use Throwable;
use Carbon\Carbon;

class Rental extends Controller
{
    


    public function rooms(Request $request){

        $data       = [];
        $page       = $request->input('page', 1);
        $perPage    = 12;
        $search     = $request->input('search');



        $data['rooms']      = Room::when($search, function ($query) use ($search) {
                                $query->where('name','like','%'.$search.'%');
                            })
                            ->where('is_active', 1)
                            ->limit($perPage)
                            ->offset(($page - 1) * $perPage)
                            ->get();

        $data['page']       = $page;
        $data['perPage']    = $perPage;
        $data['count']      = Room::when($search, function ($query) use ($search) {
                                $query->where('name','like','%'.$search.'%');
                            })
                            ->where('is_active', 1)
                            ->count();
        $data['totalPages'] = ceil($data['count'] / $perPage);
        $data['search']     = $search;

        // dd($data['attendance']);
        
        return view('pages.rental.room', $data);


    }
    
    
    public function create_room(Request $request){
        $validated  = $request->validate([
                        'name'  => 'required',
                        'rate'  => 'required',
                    ]);

        db::beginTransaction();

        try {

            Room::create($validated);
            
        } catch(Throwable $e){
            db::rollBack();    

            return redirect()
            ->route('rooms')
            ->with('status', [
                'alert' => 'alert-danger',
                'msg'   => $e->getMessage(),
            ]);
        }

        db::commit();

        return redirect()
                ->route('rooms')
                ->with('status', [
                    'alert' => 'alert-success',
                    'msg'   => 'Room Created!',
                ]);

    }
    public function edit_room(Request $request){
        $validated  = $request->validate([
                        'id'    => 'required',
                        'name'  => 'required',
                        'rate'  => 'required',
                    ]);

        db::beginTransaction();

        try {

            Room::find( $validated['id'] )->update( $validated );
            
        } catch(Throwable $e){
            db::rollBack();    

            return redirect()
            ->route('rooms')
            ->with('status', [
                'alert' => 'alert-danger',
                'msg'   => $e->getMessage(),
            ]);
        }

        db::commit();

        return redirect()
                ->route('rooms')
                ->with('status', [
                    'alert' => 'alert-success',
                    'msg'   => 'Room Edited!',
                ]);
    }

    public function delete_room(Request $request){

        $validated = $request->validate([
            'id'=> 'required',
        ]);


        db::beginTransaction();

        try {
            Room::find( $validated['id'] )
            ->update(['is_active' => 0]);
        } catch(Throwable $e){
            db::rollBack();
            return redirect()
            ->route('rooms')
            ->with('status', [
                'alert' => 'alert-danger',
                'msg'   => $e->getMessage(),
            ]);
        }

        db::commit();
        return redirect()
                ->route('rooms')
                ->with('status', [
                    'alert' => 'alert-warning',
                    'msg'   => 'Room Deleted',
                ]);
    }


    public function reservations(Request $request){
        $data       = [];
        $page       = $request->input('page', 1);
        $perPage    = 12;
        $search     = $request->input('search');
        $date       = $request->input('date');
        $start      = $request->input('start');
        $end        = $request->input('end');


        $data['res']        = Reservation::when($search, function ($query) use ($search) {
                                $query->where('reservee','like','%'.$search.'%');
                            })
                            ->join('room', 'room.id', '=', 'reservation.room_id')
                            ->when($date, function ($query) use ($date) {
                                $query->whereDate('date', $date);
                            })
                            ->when($start != null && $end != null, function ($query) use ($start, $end) {
                                
                                $query->where(function ($q) use ($start, $end) {
                                    $q->whereBetween('time', [$start, $end])
                                    ->orWhereRaw("? BETWEEN time AND DATE_ADD(time, INTERVAL hours HOUR)", [$start]); 
                                });
                            })
                            ->select([
                                'reservation.*',
                                'room.name',
                            ])
                            ->limit($perPage)
                            ->offset(($page - 1) * $perPage)
                            ->get();
        // dd($data['res']);
        $data['rooms']      = Room::where('is_active', 1)->get();
        $data['page']       = $page;
        $data['perPage']    = $perPage;
        $data['count']      = Reservation::when($search, function ($query) use ($search) {
                                $query->where('reservee','like','%'.$search.'%');
                            })
                            ->when($date, function ($query) use ($date) {
                                $query->whereDate('date', $date);
                            })
                            ->when($start != null && $end != null, function ($query) use ($start, $end) {
                                
                                $query->where(function ($q) use ($start, $end) {
                                    $q->whereBetween('time', [$start, $end])
                                    ->orWhereRaw("? BETWEEN time AND DATE_ADD(time, INTERVAL hours HOUR)", [$start]); 
                                });
                            })
                            ->count();
        $data['totalPages'] = ceil($data['count'] / $perPage);
        $data['search']     = $search;

        // dd($data['attendance']);
        
        return view('pages.rental.reservations', $data);
    }


    public function reserve_room(Request $request){
        $validated  = $request->validate([
                        'room_id'   => 'required',
                        'reservee'  => 'required',
                        'hours'     => 'required|integer',
                        'date'      => 'required',
                        'contactno' => 'required',
                        'address'   => 'required',
                        'time'      => 'required',
                    ]);

        db::beginTransaction();

        try {

            $startTime  = Carbon::parse($validated['time']);
            $endTime    = (clone $startTime)->addHours((int) $validated['hours']);

            $conflict   = Reservation::where('room_id', $validated['room_id'])
                        ->whereDate('date', $validated['date'])
                        ->where(function ($q) use ($startTime, $endTime) {
                            $q->whereBetween('time', [$startTime, $endTime])
                            ->orWhereRaw("? BETWEEN time AND DATE_ADD(time, INTERVAL hours HOUR)", [$startTime]); // new start inside existing
                        })
                        ->exists();

            if($conflict) {
                db::rollBack();
                return redirect()
                ->route('reservations')
                ->with('status', [
                    'alert' => 'alert-danger',
                    'msg'   => 'The selected time and date is already been reserved!',
                ]);
            }

            Reservation::create($validated);
            
        } catch(Throwable $e){
            db::rollBack();    

            return redirect()
            ->route('reservations')
            ->with('status', [
                'alert' => 'alert-danger',
                'msg'   => $e->getMessage(),
            ]);
        }

        db::commit();

        return redirect()
                ->route('reservations')
                ->with('status', [
                    'alert' => 'alert-success',
                    'msg'   => 'Room Reserved!',
                ]);
    }


    public function edit_reserved_room(Request $request){
        $validated  = $request->validate([
                        'id'        => 'required',
                        'room_id'   => 'required',
                        'reservee'  => 'required',
                        'hours'     => 'required|integer',
                        'date'      => 'required',
                        'contactno' => 'required',
                        'address'   => 'required',
                        'time'      => 'required',
                    ]);

        db::beginTransaction();

        try {

            $startTime  = Carbon::parse($validated['time']);
            $endTime    = (clone $startTime)->addHours((int) $validated['hours']);

            $conflict   = Reservation::where('room_id', $validated['room_id'])
                        ->whereDate('date', $validated['date'])
                        ->whereNot('id', $validated['id'])
                        ->where(function ($q) use ($startTime, $endTime) {
                            $q->whereBetween('time', [$startTime, $endTime])
                            ->orWhereRaw("? BETWEEN time AND DATE_ADD(time, INTERVAL hours HOUR)", [$startTime]); // new start inside existing
                        })
                        ->exists();

            if($conflict) {
                db::rollBack();
                return redirect()
                ->route('reservations')
                ->with('status', [
                    'alert' => 'alert-danger',
                    'msg'   => 'The selected time and date is already been reserved!',
                ]);
            }

            Reservation::find($validated['id'])->update($validated);
            
        } catch(Throwable $e){
            db::rollBack();    

            return redirect()
            ->route('reservations')
            ->with('status', [
                'alert' => 'alert-danger',
                'msg'   => $e->getMessage(),
            ]);
        }

        db::commit();

        return redirect()
                ->route('reservations')
                ->with('status', [
                    'alert' => 'alert-success',
                    'msg'   => 'Room Edited!',
                ]);
    }


    public function delete_reservation(Request $request){

        $validated = $request->validate([
            'id'=> 'required',
        ]);


        db::beginTransaction();

        try {


            $reservation = Reservation::find($validated['id']);
            $reservation->delete();

        } catch(Throwable $e){

            db::rollBack();
            return redirect()
            ->route('reservations')
            ->with('status', [
                'alert' => 'alert-danger',
                'msg'   => $e->getMessage(),
            ]);

        }

        db::commit();
        return redirect()
                ->route('reservations')
                ->with('status', [
                    'alert' => 'alert-warning',
                    'msg'   => 'Reservation deleted',
                ]);

    }
    // ========================= API =================================


    public function get_reservations(Request $request){

        $date       = Carbon::parse($request->date);
        $room_id    = $request->room;

        
        $reservation    = Reservation::where('room_id', $room_id)
                        ->join('room', 'room.id', '=', 'reservation.room_id')
                        ->select([
                            'reservation.date', 
                            'reservation.time', 
                            'reservation.hours', 
                            'reservation.reservee', 
                            'room.name',
                        ])
                        ->whereDate('date', $date)
                        ->get();


        return response()->json($reservation);

    }
}
