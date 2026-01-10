<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ProfilePhotoHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use setasign\Fpdf\Fpdf;

class BadgeAdminController extends Controller
{
    public function active(Request $request)
    {
        $students = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('students.status', 'active')
            ->select(
                'students.id',
                'students.user_id',
                'students.first_name',
                'students.last_name',
                'students.country',
                'students.program',
                'students.profile_photo',
                'users.email'
            )
            ->orderBy('students.created_at', 'desc')
            ->paginate(24)
            ->withQueryString();

        return view('admin.badges.students', [
            'title' => 'Étudiants Actifs',
            'status' => 'active',
            'students' => $students,
        ]);
    }

    public function inactive(Request $request)
    {
        $students = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('students.status', 'inactive')
            ->select(
                'students.id',
                'students.user_id',
                'students.first_name',
                'students.last_name',
                'students.country',
                'students.program',
                'students.profile_photo',
                'users.email'
            )
            ->orderBy('students.updated_at', 'desc')
            ->paginate(24)
            ->withQueryString();

        return view('admin.badges.students', [
            'title' => 'Étudiants Inactifs',
            'status' => 'inactive',
            'students' => $students,
        ]);
    }

    public function generate(int $id)
    {
        $student = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('students.id', $id)
            ->select(
                'students.id',
                'students.student_id',
                'students.first_name',
                'students.last_name',
                'students.country',
                'students.program',
                'students.profile_photo',
                'users.email'
            )
            ->first();

        abort_unless($student, 404);

        $fullName = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
        $formation = (string) ($student->program ?? '');
        $country = (string) ($student->country ?? '');
        $studentNumber = (string) ($student->student_id ?? '');

        $pdf = new Fpdf('P', 'mm', 'A4');
        $pdf->AddPage();

        $pdf->SetFont('Helvetica', 'B', 18);
        $pdf->Cell(0, 12, $this->toLatin('Badge Étudiant'), 0, 1, 'C');

        $pdf->Ln(4);
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->Cell(0, 8, $this->toLatin('Nom : ' . $fullName), 0, 1);

        if ($studentNumber !== '') {
            $pdf->Cell(0, 8, $this->toLatin('ID : ' . $studentNumber), 0, 1);
        }

        if ($formation !== '') {
            $pdf->Cell(0, 8, $this->toLatin('Formation : ' . $formation), 0, 1);
        }

        if ($country !== '') {
            $pdf->Cell(0, 8, $this->toLatin('Pays : ' . $country), 0, 1);
        }

        if (!empty($student->email)) {
            $pdf->Cell(0, 8, $this->toLatin('Email : ' . $student->email), 0, 1);
        }

        $photoUrl = null;
        if (!empty($student->profile_photo)) {
            $photoUrl = ProfilePhotoHelper::getUrl($student->profile_photo);
        }

        if ($photoUrl) {
            $localPath = $this->downloadTempFile($photoUrl);
            if ($localPath) {
                try {
                    $pdf->Image($localPath, 160, 40, 35, 35);
                } catch (\Throwable $e) {
                }
            }
        }

        $filename = 'badge_etudiant_' . ($student->id ?? 'unknown') . '.pdf';

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function toLatin(string $text): string
    {
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $text);
    }

    private function downloadTempFile(string $url): ?string
    {
        try {
            $contents = @file_get_contents($url);
            if ($contents === false) {
                return null;
            }

            $tmp = tempnam(sys_get_temp_dir(), 'evc_badge_');
            if (!$tmp) {
                return null;
            }

            file_put_contents($tmp, $contents);
            return $tmp;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
