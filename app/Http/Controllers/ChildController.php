<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\GrowthRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChildController extends Controller
{
    public function index()
    {
        $children = Auth::user()->children()->orderBy('created_at', 'desc')->get();
        return view('user.input', compact('children'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date|before_or_equal:today',
            'gender' => 'required|in:L,P',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['name', 'birth_date', 'gender']);
        $data['user_id'] = Auth::id();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('children', 'public');
        }

        Child::create($data);

        return redirect('/input')->with('success', 'Data anak berhasil ditambahkan!');
    }

    public function show(Child $child)
    {
        $this->authorizeChild($child);
        $child->load('growthRecords', 'vaccinations');
        return view('user.child-detail', compact('child'));
    }

    public function destroy(Child $child)
    {
        $this->authorizeChild($child);
        if ($child->photo) {
            Storage::disk('public')->delete($child->photo);
        }
        $child->delete();
        return redirect('/input')->with('success', 'Data anak berhasil dihapus!');
    }

    public function storeGrowth(Request $request, Child $child)
    {
        $this->authorizeChild($child);

        $request->validate([
            'weight' => 'required|numeric|min:0.1|max:200',
            'height' => 'required|numeric|min:1|max:250',
            'head_circumference' => 'nullable|numeric|min:1|max:100',
            'recorded_at' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000',
        ]);

        GrowthRecord::create([
            'child_id' => $child->id,
            'weight' => $request->weight,
            'height' => $request->height,
            'head_circumference' => $request->head_circumference,
            'recorded_at' => $request->recorded_at,
            'notes' => $request->notes,
        ]);

        return redirect('/input')->with('success', 'Data pertumbuhan berhasil disimpan!');
    }

    private function authorizeChild(Child $child)
    {
        if ($child->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
