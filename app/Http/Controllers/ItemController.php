<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ItemController extends Controller
{
    public function create()
    {
        return view('admin.items.create');
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:items',
            'description' => 'nullable|string',
            'manufacturer' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Le nom de l\'article est requis.',
            'serial_number.required' => 'Le numéro de série est requis.',
            'serial_number.unique' => 'Ce numéro de série existe déjà.',
        ]);

        $item = Item::create($validatedData);

        return redirect()->route('admin.items.show', $item->id)
            ->with('success', 'Produit créé ! Vous pouvez maintenant générer son QR Code.');
    }

    public function edit(Item $item)
    {
        return view('admin.items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:items,serial_number,' . $item->id, // On ignore l'ID actuel pour l'unique
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $item->update($request->all());

        return redirect()->route('admin.items.show', $item->id)->with('success', 'Objet mis à jour !');
    }

    public function destroy(Item $item)
    {
        if ($item->qr_code && Storage::disk('public')->exists($item->qr_code)) {
            Storage::disk('public')->delete($item->qr_code);
        }

        $item->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Objet supprimé de l\'inventaire.');
    }

    public function adminShow(Item $item)
    {
        return view('admin.items.show', compact('item'));
    }

    public function userShow(Item $item)
    {
        return view('user.items.show', compact('item'));
    }

    public function generateQrCode(Item $item)
    {
        $fileName = 'qrcodes/qr-' . $item->id . '.svg';

        $qrContent = $item->serial_number;

        $image = QrCode::format('svg')
            ->size(300)
            ->margin(1)
            ->generate($qrContent);

        Storage::disk('public')->put($fileName, $image);

        $item->update([
            'qr_code' => $fileName
        ]);

        return back()->with('success', 'QR Code généré avec succès.');
    }

    public function scan(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string',
        ]);

        $item = Item::where('serial_number', $request->serial_number)->first();

        if (!$item) {
            return back()->withErrors([
                'serial_number' => 'Aucun objet trouvé avec ce numéro de série.'
            ]);
        }

        $currentLoan = $item->currentLoan();

        if ($currentLoan && $currentLoan->status === Loan::STATUS_BORROWED) {
            if ($currentLoan->user_id === Auth::id()) {
                $currentLoan->update([
                    'status' => 'returned',
                    'end_date' => now(),
                ]);
                return redirect()->route('user.dashboard')->with('returned', $item->name);
            }
            return redirect()->route('user.dashboard')->with('occupied', $item->name);
        }
        return redirect()->route('borrow.show', $item->id);
    }

    public function borrowShow(Item $item)
    {
        $currentLoan = $item->currentLoan();
        return view('user.borrow', compact('item', 'currentLoan'));
    }
}