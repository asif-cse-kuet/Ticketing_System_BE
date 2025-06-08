<?php

namespace App\Modules\Tickets\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Modules\Tickets\Requests\CreateTicketRequest;
use Illuminate\Support\Facades\Gate;
use App\Modules\Tickets\Requests\UpdateTicketRequest;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $tickets = $user->role === 'admin'
            ? Ticket::with('user')->latest()->get()
            : Ticket::where('user_id', $user->id)->latest()->get();

        return response()->json(['tickets' => $tickets]);
    }

    public function store(CreateTicketRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        $ticket = Ticket::create($data);

        return response()->json(['ticket' => $ticket], 201);
    }

    public function show(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($request->user()->role !== 'admin' && $ticket->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['ticket' => $ticket]);
    }

    public function update(UpdateTicketRequest $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($request->user()->role !== 'admin' && $ticket->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $ticket->update($request->validated());

        return response()->json(['ticket' => $ticket]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,open,in_progress,resolved,closed',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->status = $request->status;
        $ticket->save();

        return response()->json([
            'message' => 'Status updated successfully',
            'ticket' => $ticket
        ]);
    }


    public function destroy(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Only admins can delete tickets'], 403);
        }

        if ($ticket->attachment) {
            Storage::disk('public')->delete($ticket->attachment);
        }

        $ticket->delete();

        return response()->json(['message' => 'Ticket deleted successfully']);
    }
}
