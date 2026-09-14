<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProjectTemplateController extends Controller
{
    public function index()
    {
        $templates = ProjectTemplate::orderBy('created_at', 'desc')->get();

        return view('admin.project-templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.project-templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'link' => 'nullable|url',
            'tags' => 'nullable|string',
            'software_used' => 'nullable|array',
            'thumbnail_image' => 'nullable|string',
            'brief_content' => 'nullable|string',
            'default_deadline_days' => 'nullable|integer|min:1|max:365',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['software_used']) && is_array($validated['software_used'])) {
            $validated['software_used'] = json_encode($validated['software_used']);
        }

        $validated['is_active'] = $request->has('is_active');

        ProjectTemplate::create($validated);

        return redirect()->route('admin.project-templates.index')
            ->with('success', 'Modèle de projet créé avec succès.');
    }

    public function edit(ProjectTemplate $projectTemplate)
    {
        return view('admin.project-templates.edit', compact('projectTemplate'));
    }

    public function update(Request $request, ProjectTemplate $projectTemplate)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'link' => 'nullable|url',
            'tags' => 'nullable|string',
            'software_used' => 'nullable|array',
            'thumbnail_image' => 'nullable|string',
            'brief_content' => 'nullable|string',
            'default_deadline_days' => 'nullable|integer|min:1|max:365',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['software_used']) && is_array($validated['software_used'])) {
            $validated['software_used'] = json_encode($validated['software_used']);
        }

        $validated['is_active'] = $request->has('is_active');

        $projectTemplate->update($validated);

        return redirect()->route('admin.project-templates.index')
            ->with('success', 'Modèle de projet mis à jour avec succès.');
    }

    public function destroy(ProjectTemplate $projectTemplate)
    {
        $projectTemplate->delete();

        return redirect()->route('admin.project-templates.index')
            ->with('success', 'Modèle de projet supprimé avec succès.');
    }

    public function assignToStudent(Request $request, $studentId)
    {
        $validated = $request->validate([
            'template_id' => 'required|exists:project_templates,id',
        ]);

        $template = ProjectTemplate::findOrFail($validated['template_id']);

        // Récupérer l'étudiant et son user_id
        $student = DB::table('students')->where('id', $studentId)->first();
        if (!$student) {
            return back()->with('error', 'Étudiant non trouvé.');
        }

        $userId = $student->user_id;
        if (!$userId) {
            return back()->with('error', 'Utilisateur associé non trouvé.');
        }

        // Vérifier si le projet existe déjà pour cet étudiant
        $existingProject = DB::table('projects')
            ->where('user_id', $userId)
            ->where('title', $template->title)
            ->where('category', $template->category)
            ->first();

        if ($existingProject) {
            return back()->with('error', 'Ce projet existe déjà pour cet étudiant.');
        }

        // Créer le projet à partir du template
        $projectData = [
            'user_id' => $userId,
            'title' => $template->title,
            'category' => $template->category,
            'description' => $template->description,
            'link' => $template->link,
            'tags' => $template->tags,
            'software_used' => $template->software_used,
            'thumbnail_image' => $template->thumbnail_image,
            'status' => 'en_cours',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Ajouter deadline si spécifié
        if ($template->default_deadline_days) {
            $projectData['deadline'] = now()->addDays($template->default_deadline_days);
        }

        $projectId = DB::table('projects')->insertGetId($projectData);

        // Notification in-app (database)
        try {
            $user = \App\Models\User::find($userId);
            if ($user) {
                $formationSlug = 'design-graphique';
                if (!empty($student->program)) {
                    $prog = strtolower((string) $student->program);
                    if (str_contains($prog, 'community')) {
                        $formationSlug = 'community-management';
                    } elseif (str_contains($prog, 'informatique')) {
                        $formationSlug = 'gestion-informatique';
                    } elseif (str_contains($prog, 'intelligence')) {
                        $formationSlug = 'intelligence-artificielle';
                    }
                }

                $user->notify(new \App\Notifications\ProjectAssignedNotification([
                    'category' => 'project',
                    'event' => 'assigned',
                    'title' => 'Nouveau projet assigné',
                    'message' => 'Un nouveau projet a été assigné : ' . ($template->title ?? 'Projet'),
                    'project_id' => $projectId,
                    'project_title' => $template->title ?? null,
                    'created_at' => now()->toIso8601String(),
                    'url' => url("/evc/compte/{$formationSlug}/todo/traiter/{$projectId}"),
                ]));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Notification in-app projet assigné échouée (assignToStudent)', [
                'project_id' => $projectId,
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);
        }

        return back()->with('success', 'Projet assigné avec succès.');
    }
}
