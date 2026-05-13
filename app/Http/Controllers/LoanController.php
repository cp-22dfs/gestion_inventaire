<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['item', 'user'])->orderBy('start_date', 'desc')->paginate(10);
        return view('admin.loans.index', compact('loans'));
    }

    public function edit(Loan $loan)
    {
        return view('admin.loans.edit', compact('loan'));
    }

    public function update(Request $request, Loan $loan)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date_planned' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'status' => 'required|string|in:reserved,borrowed,returned',
            'end_date' => 'nullable|date|after_or_equal:start_date|required_if:status,returned'
        ], [
            'end_date.required_if' => 'La date de retour réelle est obligatoire lorsque le statut est "rendu".',
        ]);

        $conflit = Loan::where('item_id', $loan->item_id)
            ->where('id', '!=', $loan->id)
            ->whereNotIn('status', ['returned'])
            ->where('start_date', '<', $request->end_date_planned)
            ->where('end_date_planned', '>', $request->start_date)
            ->exists();

        if ($conflit) {
            return back()->withErrors([
                'conflict' => 'Cette modification crée un conflit avec une réservation existante.'
            ])->withInput();
        }

        $data = $request->all();

        if ($request->status === 'returned') {
            $data['end_date'] = $request->end_date ?? now();
        } else {
            $data['end_date'] = null;
        }

        $loan->update($data);

        return redirect()->route('admin.loans.index')->with('success', 'Prêt mis à jour avec succès.');
    }

    public function destroy(Loan $loan)
    {
        $loan->delete();
        return back()->with('success', 'Réservation annulée.');
    }
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date_planned' => 'required|date|after:start_date',
            'location' => 'nullable|string|max:255',
        ]);

        $item = Item::find($request->item_id);
        $currentLoan = $item->currentLoan();

        if ($currentLoan && $currentLoan->status === Loan::STATUS_RESERVED) {
            $currentLoan->update([
                'status' => Loan::STATUS_BORROWED,
                'location' => $request->location ?? $currentLoan->location,
                'end_date_planned' => $currentLoan->end_date_planned,
            ]);

            return redirect()->route('user.dashboard')->with('success', 'Prêt enregistré avec succès !');
        }

        $conflit = Loan::where('item_id', $request->item_id)
            ->whereNotIn('status', ['returned'])
            ->where('start_date', '<', $request->end_date_planned)
            ->where('end_date_planned', '>', $request->start_date)
            ->exists();

        if ($conflit) {
            return back()->withErrors(['conflict' => 'Cet objet est déjà réservé sur cette période.'])
                ->withInput();
        }

        Loan::create([
            'item_id' => $request->item_id,
            'user_id' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date_planned' => $request->end_date_planned,
            'location' => $request->location,
            'status' => $request->status === 'borrowed' ? Loan::STATUS_BORROWED : Loan::STATUS_RESERVED,
        ]);

        return redirect()->route('items.show', $request->item_id)->with('success', 'Prêt enregistré avec succès !');
    }
}
