<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     */
    public function export(Request $req) 
    {
        $request = null;
        if($req->start_date && $req->end_date){
            $request = new UsersExport($req);
        }
        else{
            $request = new UsersExport;
        }
        return Excel::download($request, 'tickets.xlsx');
    }
    
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Ticket::with(['user:id,name', 'assignee'])->where('user_id', $user->id)->latest();
        if($request->start_date && $request->end_date){
            $query->whereDate('created_at','>=',$request->start_date)
            ->whereDate('created_at','<=',$request->end_date);
        }
        
        $result =$query->get();
        $users = User::get();
        return Inertia::render('Tickets/Index', [
            'tickets' => $result,
            'users' => $users,
            'start_date' => isset($request->start_date) ? $request->start_date : '',
            'end_date' => isset($request->end_date) ? $request->end_date : '',

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        /* $ticket = new Ticket(); */
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'string|max:255',
            'priority' => 'required',
            'status' => 'required',
            'assignee_id' => 'required',
        ]);

        $request->user()->tickets()->create($validated)->with(['message' => [
            'type' => 'success',
            'message' => 'your message here'
        ]]);



        /* $ticket->create([
            "title"=> $request->title,
            "description" => $request->description,
            "status" => $request->status,
            "priority" => $request->priority,
            "assignee_id" => $request->assignee_id,
        ]); */

        return redirect(route('tickets.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //dd($request->all());
        $validatedData = $request->validate([
            'status' => 'nullable|in:Abierto,Pendiente,Resuelto,En proceso', // Asegúrate de que los valores sean correctos
            'priority' => 'nullable|in:Baja,Media,Alta,Urgente', // Asegúrate de que los valores sean correctos
            'satisfied' => 'nullable|in:Excelente,Buena,Regular,Mal', // Asegúrate de que los valores sean correctos
        ]);
        try {

            // Actualizar el ticket con el estado validado
            $dataToUpdate = $validatedData;


            // Verifica si el estado no es 'Resuelto' y establece el valor de 'satisfied'
            if (isset($request->status) && $request->status !== 'Resuelto') {
                //dd("true");
                $dataToUpdate['satisfied'] = 'Sin responder';
            }
            //dd($dataToUpdate);
            // Realiza una única actualización
            $ticket->update($dataToUpdate);



            // Responder con una redirección de Inertia
            return redirect()->back()->with('success', 'Ticket actualizado correctamente.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
