<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\GrowthRecord;
use App\Models\Vaccination;
use App\Models\Milestone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChildController extends Controller
{
    public function index()
    {
        $children = Auth::user()->children()->with(['latestGrowth', 'vaccinations'])->orderBy('created_at', 'desc')->get();
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

        return redirect()->route('input')->with('success', 'Data anak berhasil ditambahkan!');
    }

    public function show(Child $child)
    {
        $this->authorizeChild($child);
        $child->load(['growthRecords' => function ($query) {
            $query->orderBy('recorded_at', 'desc');
        }, 'vaccinations' => function ($query) {
            $query->orderBy('scheduled_date', 'desc');
        }, 'milestones']);
        return view('user.child-detail', compact('child'));
    }

    public function update(Request $request, Child $child)
    {
        $this->authorizeChild($child);

        $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date|before_or_equal:today',
            'gender' => 'required|in:L,P',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['name', 'birth_date', 'gender']);

        if ($request->hasFile('photo')) {
            if ($child->photo) {
                Storage::disk('public')->delete($child->photo);
            }
            $data['photo'] = $request->file('photo')->store('children', 'public');
        }

        $child->update($data);

        return redirect()->route('input')->with('success', 'Data anak berhasil diperbarui!');
    }

    public function destroy(Child $child)
    {
        $this->authorizeChild($child);
        if ($child->photo) {
            Storage::disk('public')->delete($child->photo);
        }
        $child->delete();
        return redirect()->route('input')->with('success', 'Data anak berhasil dihapus!');
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

        return redirect()->route('child.show', $child)->with('success', 'Data pertumbuhan berhasil disimpan!');
    }

    public function updateGrowth(Request $request, Child $child, GrowthRecord $growth)
    {
        $this->authorizeChild($child);

        if ($growth->child_id !== $child->id) {
            abort(403);
        }

        $request->validate([
            'weight' => 'required|numeric|min:0.1|max:200',
            'height' => 'required|numeric|min:1|max:250',
            'head_circumference' => 'nullable|numeric|min:1|max:100',
            'recorded_at' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000',
        ]);

        $growth->update([
            'weight' => $request->weight,
            'height' => $request->height,
            'head_circumference' => $request->head_circumference,
            'recorded_at' => $request->recorded_at,
            'notes' => $request->notes,
        ]);

        return redirect()->route('child.show', $child)->with('success', 'Data pertumbuhan berhasil diperbarui!');
    }

    public function destroyGrowth(Child $child, GrowthRecord $growth)
    {
        $this->authorizeChild($child);

        if ($growth->child_id !== $child->id) {
            abort(403);
        }

        $growth->delete();

        return redirect()->route('child.show', $child)->with('success', 'Data pertumbuhan berhasil dihapus!');
    }

    public function storeVaccination(Request $request, Child $child)
    {
        $this->authorizeChild($child);

        $request->validate([
            'vaccine_name' => 'required|string|max:255',
            'scheduled_date' => 'required|date',
            'status' => 'required|in:upcoming,done,missed',
            'actual_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $child->vaccinations()->create([
            'vaccine_name' => $request->vaccine_name,
            'scheduled_date' => $request->scheduled_date,
            'actual_date' => $request->actual_date,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Data vaksinasi berhasil disimpan!');
    }

    public function updateVaccinationStatus(Child $child, Vaccination $vaccination)
    {
        $this->authorizeChild($child);

        if ($vaccination->child_id !== $child->id) {
            abort(403);
        }

        $statusMap = [
            'upcoming' => 'done',
            'done' => 'missed',
            'missed' => 'upcoming',
        ];

        $newStatus = $statusMap[$vaccination->status] ?? 'upcoming';
        $vaccination->update([
            'status' => $newStatus,
            'actual_date' => $newStatus === 'done' ? now() : $vaccination->actual_date,
        ]);

        return redirect()->back()->with('success', 'Status vaksinasi berhasil diperbarui!');
    }

    public function storeMilestone(Request $request, Child $child)
    {
        $this->authorizeChild($child);

        $request->validate([
            'category' => 'required|in:motorik,kognitif,sosial,bicara',
            'milestone_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_achieved' => 'boolean',
            'achieved_at' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $child->milestones()->create([
            'category' => $request->category,
            'milestone_name' => $request->milestone_name,
            'description' => $request->description,
            'is_achieved' => $request->boolean('is_achieved', false),
            'achieved_at' => $request->is_achieved ? ($request->achieved_at ?? now()) : null,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Milestone berhasil ditambahkan!');
    }

    public function updateMilestone(Request $request, Milestone $milestone)
    {
        $this->authorizeChild($milestone->child);

        $request->validate([
            'is_achieved' => 'boolean',
            'achieved_at' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $milestone->update([
            'is_achieved' => $request->boolean('is_achieved'),
            'achieved_at' => $request->boolean('is_achieved') ? ($request->achieved_at ?? now()) : null,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Milestone berhasil diperbarui!');
    }

    public function destroyMilestone(Milestone $milestone)
    {
        $this->authorizeChild($milestone->child);
        $milestone->delete();
        return redirect()->back()->with('success', 'Milestone berhasil dihapus!');
    }

    private function authorizeChild(Child $child)
    {
        if ($child->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
