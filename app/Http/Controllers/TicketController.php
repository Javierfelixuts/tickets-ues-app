<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::get();
        $user = Auth::user();
        return Inertia::render('Tickets/Index', [
            'tickets' => Ticket::with(['user:id,name', 'assignee'])->where('user_id', $user->id)->latest()->get(),
            'users' => $users,

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
        ]);
        
        try {
            // Actualizar el ticket con el estado validado
            $ticket->update($validatedData);
    
            // Responder con una redirección de Inertia
            return redirect()->back()->with('success', 'Ticket actualizado correctamente.');
        } catch(Exception $e) {
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
