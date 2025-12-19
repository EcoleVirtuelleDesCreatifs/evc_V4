<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

class PaymentAdminController extends Controller
{
    /**
     * Afficher la liste de tous les paiements
     */
    public function index(): View
    {
        $payments = DB::table('payments')
            ->join('pre_registrations', 'payments.pre_registration_id', '=', 'pre_registrations.id')
            ->leftJoin('students', 'pre_registrations.email', '=', 'students.email')
            ->select(
                'payments.*',
                'pre_registrations.prenom',
                'pre_registrations.nom',
                'pre_registrations.email',
                'pre_registrations.choix_formation',
                'students.student_id'
            )
            ->orderBy('payments.created_at', 'desc')
            ->paginate(20);

        // Statistiques
        $stats = [
            'total' => DB::table('payments')->sum('amount'),
            'completed' => DB::table('payments')->where('status', 'completed')->sum('amount'),
            'pending' => DB::table('payments')->where('status', 'pending')->sum('amount'),
            'cancelled' => DB::table('payments')->where('status', 'cancelled')->sum('amount'),
            'count_completed' => DB::table('payments')->where('status', 'completed')->count(),
            'count_pending' => DB::table('payments')->where('status', 'pending')->count(),
            'count_cancelled' => DB::table('payments')->where('status', 'cancelled')->count(),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    /**
     * Afficher les détails d'un paiement
     */
    public function show($id): View
    {
        $payment = DB::table('payments')
            ->join('pre_registrations', 'payments.pre_registration_id', '=', 'pre_registrations.id')
            ->leftJoin('students', 'pre_registrations.email', '=', 'students.email')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->select(
                'payments.*',
                'pre_registrations.prenom',
                'pre_registrations.nom',
                'pre_registrations.email',
                'pre_registrations.whatsapp',
                'pre_registrations.choix_formation',
                'pre_registrations.ville',
                'pre_registrations.pays',
                'students.student_id',
                'students.status as student_status',
                'users.id as user_id'
            )
            ->where('payments.id', $id)
            ->first();

        if (!$payment) {
            abort(404, 'Paiement non trouvé');
        }

        return view('admin.payments.show', compact('payment'));
    }
}
