<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JuryMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JuryMembersAdminController extends Controller
{
    private function ensureAllowed(): void
    {
        if (!in_array(session('admin_role'), ['super_admin', 'manager'], true)) {
            abort(403);
        }
    }

    public function index(): View
    {
        $this->ensureAllowed();

        $members = JuryMember::query()
            ->withCount('evaluations')
            ->with(['evaluations' => fn($q) => $q->where('status', 'submitted')->select('id', 'jury_member_id', 'group_name')])
            ->orderByDesc('is_active')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.jury_members.index', compact('members'));
    }

    public function create(): View
    {
        $this->ensureAllowed();

        return view('admin.jury_members.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureAllowed();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'unique_identifier' => ['required', 'string', 'max:100', 'unique:jury_members,unique_identifier'],
            'title' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'flag' => ['nullable', 'string', 'max:20'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
            'is_visible' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $path = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('jury_members', 'public');
        }

        $member = JuryMember::query()->create([
            'name' => $validated['name'],
            'unique_identifier' => $validated['unique_identifier'],
            'title' => $validated['title'] ?? null,
            'country' => $validated['country'] ?? null,
            'flag' => $validated['flag'] ?? null,
            'image_path' => $path,
            'image_url' => $validated['image_url'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? true),
            'is_visible' => (bool) ($validated['is_visible'] ?? true),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ]);

        return redirect()
            ->route('admin.jury-members.edit', $member)
            ->with('success', 'Membre du jury ajouté.');
    }

    public function edit(JuryMember $juryMember): View
    {
        $this->ensureAllowed();

        return view('admin.jury_members.edit', compact('juryMember'));
    }

    public function update(Request $request, JuryMember $juryMember): RedirectResponse
    {
        $this->ensureAllowed();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'unique_identifier' => ['required', 'string', 'max:100', Rule::unique('jury_members', 'unique_identifier')->ignore($juryMember->id)],
            'title' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'flag' => ['nullable', 'string', 'max:20'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
            'is_visible' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('photo')) {
            if (!empty($juryMember->image_path)) {
                Storage::disk('public')->delete($juryMember->image_path);
            }

            $juryMember->image_path = $request->file('photo')->store('jury_members', 'public');
        }

        $juryMember->name = $validated['name'];
        $juryMember->unique_identifier = $validated['unique_identifier'];
        $juryMember->title = $validated['title'] ?? null;
        $juryMember->country = $validated['country'] ?? null;
        $juryMember->flag = $validated['flag'] ?? null;
        $juryMember->image_url = $validated['image_url'] ?? null;
        $juryMember->is_active  = (bool) ($validated['is_active'] ?? false);
        $juryMember->is_visible  = (bool) ($validated['is_visible'] ?? false);
        $juryMember->sort_order  = (int) ($validated['sort_order'] ?? 0);
        $juryMember->save();

        return redirect()
            ->route('admin.jury-members.index')
            ->with('success', 'Membre du jury mis à jour.');
    }

    public function destroy(JuryMember $juryMember): RedirectResponse
    {
        $this->ensureAllowed();

        DB::transaction(function () use ($juryMember) {
            $juryMember->evaluations()
                ->with('scores')
                ->get()
                ->each(function ($evaluation) {
                    $evaluation->scores()->delete();
                    $evaluation->delete();
                });

            $juryMember->delete();
        });

        if (!empty($juryMember->image_path)) {
            Storage::disk('public')->delete($juryMember->image_path);
        }

        return redirect()
            ->route('admin.jury-members.index')
            ->with('success', 'Membre du jury supprimé.');
    }
}
