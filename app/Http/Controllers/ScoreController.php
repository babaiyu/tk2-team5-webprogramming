<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

use function PHPSTORM_META\map;

class ScoreController extends Controller
{
    private function calculateScore(
        $score_quis,
        $score_tugas,
        $score_presensi,
        $score_praktek,
        $score_uas
    ) {
        $calculated = ($score_quis * 15 / 100) +
            ($score_tugas * 20 / 100) +
            ($score_presensi * 10 / 100) +
            ($score_praktek * 25 / 100) +
            ($score_uas * 30 / 100);

        $grade = '';

        if ($calculated <= 100 && $calculated > 85) {
            $grade = 'A';
        } else if ($calculated <= 85 && $calculated > 75) {
            $grade = 'B';
        } else if ($calculated <= 75 && $calculated > 65) {
            $grade = 'C';
        } else {
            $grade = 'D';
        }

        return [
            'score' => $calculated,
            'grade' => $grade,
        ];
    }

    private function calculateAverage($total_score, $total)
    {
        if ($total_score > 0 && $total > 0) {
            $result = $total_score / $total;
            return $result;
        }

        return 0;
    }

    public function getStudentsScore()
    {
        $students = Student::all();
        $students = $students->map(function ($item) {
            $calculated = $this->calculateScore(
                $item->score_quis,
                $item->score_tugas,
                $item->score_presensi,
                $item->score_praktek,
                $item->score_uas
            );
            return [
                'id' => $item->id,
                'fullname' => $item->fullname,
                'nim' => $item->nim,
                'score_quis' => $item->score_quis,
                'score_tugas' => $item->score_tugas,
                'score_presensi' => $item->score_presensi,
                'score_praktek' => $item->score_praktek,
                'score_uas' => $item->score_uas,
                'total_score' => $calculated['score'],
                'grade' => $calculated['grade'],
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        });

        return response()
            ->json([
                'data' => $students,
                'message' => 'Success get all Students',
            ]);
    }

    public function getStudentScoreByID(Request $request, string $nim)
    {
        $student = Student::where('nim', $nim)->get();
        if ($student->count() > 0) {
            $calculated = $this->calculateScore(
                $student[0]->score_quis,
                $student[0]->score_tugas,
                $student[0]->score_presensi,
                $student[0]->score_praktek,
                $student[0]->score_uas
            );
            return response()
                ->json([
                    'data' => [
                        'id' => $student[0]->id,
                        'fullname' => $student[0]->fullname,
                        'nim' => $student[0]->nim,
                        'score_quis' => $student[0]->score_quis,
                        'score_tugas' => $student[0]->score_tugas,
                        'score_presensi' => $student[0]->score_presensi,
                        'score_praktek' => $student[0]->score_praktek,
                        'score_uas' => $student[0]->score_uas,
                        'total_score' => $calculated['score'],
                        'grade' => $calculated['grade'],
                        'created_at' => $student[0]->created_at,
                        'updated_at' => $student[0]->updated_at,
                    ],
                    'message' => 'Success get student score by nim ' . $nim,
                ]);
        }

        return response()
            ->json([
                'data' => [],
                'message' => 'Cannot find nim ' . $nim . '! ' . 'Please try another way',
            ])
            ->setStatusCode(404);
    }

    public function postStudentScore(Request $request)
    {
        $findStudent = Student::where('nim', $request->input('nim'))->get();

        if ($findStudent->count() > 0) {
            return response()
                ->json(['message' => 'NIM ' . $request->input('nim') . ' already exist! Please add another NIM'])
                ->setStatusCode(400);
        }

        $student = Student::create([
            'fullname' => $request->input('fullname'),
            'nim' => $request->input('nim'),
            'score_quis' => $request->input('score_quis'),
            'score_tugas' => $request->input('score_tugas'),
            'score_presensi' => $request->input('score_presensi'),
            'score_praktek' => $request->input('score_praktek'),
            'score_uas' => $request->input('score_uas'),
        ]);

        if ($student) {
            return response()
                ->json([
                    'message' => 'Success post student score',
                ]);
        }

        return response()
            ->json([
                'message' => 'Failed to post student score! Please try another way'
            ])
            ->setStatusCode(400);
    }

    public function updateStudentScore(Request $request, string $nim)
    {
        $student = Student::where('nim', $nim)->get();

        if ($student->count() > 0) {
            $studentUpdate = Student::findOrFail($student[0]->id);

            $studentUpdate->update([
                'fullname' => $request->input('fullname'),
                'score_quis' => $request->input('score_quis'),
                'score_tugas' => $request->input('score_tugas'),
                'score_presensi' => $request->input('score_presensi'),
                'score_praktek' => $request->input('score_praktek'),
                'score_uas' => $request->input('score_uas'),
            ]);

            if ($studentUpdate) {
                return response()
                    ->json([
                        'message' => 'Success update student score by nim ' . $nim
                    ]);
            }

            return response()
                ->json([
                    'message' => 'Failed to update student score! Please try another way',
                ])
                ->setStatusCode(400);
        }

        return response()
            ->json([
                'message' => 'Cannot find nim ' . $nim . '! ' . 'Please try another way',
            ])
            ->setStatusCode(404);
    }

    public function deleteStudentScore(Request $request, string $nim)
    {
        $student = Student::where('nim', $nim)->get();

        if ($student->count() > 0) {
            $studentDelete = Student::findOrFail($student[0]->id);
            $studentDelete->delete();

            if ($studentDelete) {
                return response()
                    ->json([
                        'message' => 'Success delete nim ' . $nim,
                    ]);
            }

            return response()
                ->json([
                    'message' => 'Failed to delete student score! Please try another way',
                ])
                ->setStatusCode(400);
        }

        return response()
            ->json([
                'message' => 'Cannot find nim ' . $nim . '! ' . 'Please try another way',
            ])
            ->setStatusCode(404);
    }

    public function gradeChart()
    {
        $students = Student::all();
        $students = $students->map(function ($item) {
            $calculated = $this->calculateScore(
                $item->score_quis,
                $item->score_tugas,
                $item->score_presensi,
                $item->score_praktek,
                $item->score_uas
            );
            return $calculated;
        });

        $gradeLabel = ['A', 'B', 'C', 'D'];
        $grades = array();

        foreach ($gradeLabel as $value) {
            $sumGrade = 0;
            $sumScore = 0;
            $average = 0;

            foreach ($students as $sKey => $sValue) {
                if ($sValue['grade'] == $value) {
                    $sumGrade++;
                    $sumScore += $sValue['score'];
                }
            }

            $average = $this->calculateAverage($sumScore, $sumGrade);
            $result = [
                'grade' => $value,
                'total_student_grade' => $sumGrade,
                'total_score' => $sumScore,
                'total_average' => $average,
            ];
            array_push($grades, $result);
        }

        return response()
            ->json([
                'data' => $grades,
                'message' => 'Success get all Students',
            ]);
    }
}
