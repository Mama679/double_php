<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function createTicket(Request $request)
    {
        $request->validate([
            "nota" =>"required|min:10",
            "estado" => "required"
        ]);

        $user_id = auth()->user()->id;//Toquen de usuario logueado

        $ticket = new Ticket();
        $ticket->nota = $request->nota;
        $ticket->usuario_id = $user_id;
        $ticket->estado = $request->estado;
       

        $ticket->save();

        return response()->json([
            "status" => 1,
            "mensaje" =>"Ticket creado exitosamente."
        ],Response::HTTP_CREATED);
    }

    public function listTicket()
    {
        $ticket = Ticket::with('usuario')->get();
        
        return response()->json([
            "status" => 1,
            "mensaje" =>"Listado de Tickets.",
            "data" => $ticket
        ],Response::HTTP_OK);
    }

    public function showTicket($id)
    {
        //$user_id = auth()->user()->id;
        if(Ticket::where(["id" => $id])->exists())
        {
            $ticket = Ticket::with('usuario')->find($id);
            return response()->json([
                "status" => 1,
                "data" => $ticket
            ],Response::HTTP_OK);
        }
        else
        {
            return response()->json([
                "status" => 0,
                "mensaje" =>"No se encontro ticket."
            ],Response::HTTP_NOT_FOUND);
        }
        
    }

    public function updateTicket(Request $request, $id)
    {
        $user_id = auth()->user()->id;//Toquen de usuario logueado
        if(Ticket::where(["id" => $id])->exists())
        {
            $ticket =  Ticket::find($id);
            $ticket->nota = isset($request->nota) ? $request->nota: $ticket->nota;
            $ticket->estado = isset($request->estado) ? $request->estado: $ticket->estado;
            $ticket->usuario_id = $user_id;
            $ticket->save();

            return response()->json([
                "status" => 1,
                "mensaje" =>"Ticket modificado con exito.",
                "data" => $ticket
            ],Response::HTTP_OK);
        }
        else
        {
            return response()->json([
                "status" => 0,
                "mensaje" =>"No se encontro Ticket."
            ],Response::HTTP_NOT_FOUND);
        }
    }

    public function deleteTicket($id)
    {
        if(Ticket::where(["id" => $id])->exists())
        {
            $ticket = Ticket::where(["id" => $id])->first();
            $ticket->delete();

            return response()->json([
                "status" => 1,
                "mensaje" =>"Ticket ha sido eliminado.",
                "data" => $ticket
            ],Response::HTTP_OK);
        }
        else
        {
            return response()->json([
                "status" => 0,
                "mensaje" =>"No se encontro Ticket."
            ],Response::HTTP_NOT_FOUND);
        }
    }
}
