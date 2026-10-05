<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnglishUnit8Seeder extends Seeder
{
    use CanSeedEnglishContent;

    public function run(): void
    {
        DB::transaction(function () {
            $course = Course::where('title', 'Complete English Mastery')->first();

            if (!$course) {
                return;
            }

            $unitOrder = 8;
            $unitData = $this->getUnitData();

            $unit = Unit::query()->updateOrCreate(
                ['course_id' => $course->id, 'order' => $unitOrder],
                ['title' => $unitData['title']]
            );

            foreach ($unitData['lessons'] as $lessonIndex => $lessonData) {
                $lessonOrder = $lessonIndex + 1;

                $lessonImagePath = "units/unit-{$unitOrder}/lesson-{$lessonOrder}/main.png";
                $this->smartCopyImage($unitOrder, $lessonOrder, 'main.png', $lessonImagePath);

                $lesson = Lesson::query()->updateOrCreate(
                    ['unit_id' => $unit->id, 'order' => $lessonOrder],
                    [
                        'title' => $lessonData['title'],
                        'type' => $lessonData['type'] ?? 'reading',
                        'xp_reward' => $lessonData['xp'] ?? 20,
                        'explanation' => $lessonData['explanation'],
                        'image_url' => $lessonImagePath,
                    ]
                );

                foreach ($lessonData['questions'] as $questionIndex => $q) {
                    $questionOrder = $questionIndex + 1;
                    $questionImagePath = "units/unit-{$unitOrder}/lesson-{$lessonOrder}/q-{$questionOrder}.png";
                    $this->smartCopyImage($unitOrder, $lessonOrder, "q-{$questionOrder}.png", $questionImagePath);

                    $question = Question::query()->updateOrCreate(
                        ['lesson_id' => $lesson->id, 'order' => $questionOrder],
                        [
                            'type' => $q['type'],
                            'difficulty_level' => $q['difficulty'],
                            'question_text' => $q['text'],
                            'image_url' => $questionImagePath,
                        ]
                    );

                    $this->syncAnswers($question, $q);
                }

                Question::where('lesson_id', $lesson->id)
                    ->where('order', '>', count($lessonData['questions']))
                    ->delete();
            }
        });
    }

    private function getUnitData(): array
    {
        return [
            'title' => 'Unit 8: Work & Study',
            'lessons' => [
                // 1
                $this->lesson('Jobs & Professions', '<h3>Jobs & Professions</h3><p><b>Teacher</b> (guru), <b>Doctor</b> (dokter), <b>Engineer</b> (insinyur), <b>Artist</b> (seniman), <b>Chef</b> (koki).</p>', [
                    $this->mc('Who teaches students?', ['Teacher', 'Doctor', 'Chef', 'Engineer'], 'Teacher'),
                    $this->mc('Who works in a hospital?', ['Doctor', 'Artist', 'Teacher', 'Chef'], 'Doctor'),
                    $this->mc('Who builds things?', ['Engineer', 'Chef', 'Artist', 'Doctor'], 'Engineer'),
                    $this->fib('My brother is an ___. (insinyur)', 'engineer'),
                    $this->fib('The ___ cooks great food.', 'chef'),
                ]),
                // 2
                $this->lesson('Workplaces', '<h3>Workplaces</h3><p><b>Office</b> (kantor), <b>School</b> (sekolah), <b>Hospital</b> (rumah sakit), <b>Studio</b> (studio), <b>Workshop</b> (bengkel).</p>', [
                    $this->mc('Where do artists work?', ['Studio', 'Hospital', 'School', 'Office'], 'Studio'),
                    $this->mc('Where do doctors work?', ['Hospital', 'Studio', 'School', 'Office'], 'Hospital'),
                    $this->mc('Where do engineers work?', ['Workshop', 'Studio', 'Hospital', 'School'], 'Workshop'),
                    $this->fib('I work in a big ___ downtown.', 'office'),
                    $this->fib('She studies in a ___.', 'school'),
                ]),
                // 3
                $this->lesson('School & University', '<h3>School & University</h3><p><b>Classroom</b> (ruang kelas), <b>Library</b> (perpustakaan), <b>Student</b> (murid), <b>Professor</b> (profesor), <b>Exam</b> (ujian).</p>', [
                    $this->mc('Where do students have classes?', ['Classroom', 'Library', 'Office', 'Workshop'], 'Classroom'),
                    $this->mc('Who teaches at university?', ['Professor', 'Student', 'Artist', 'Chef'], 'Professor'),
                    $this->mc('What do students take to test knowledge?', ['Exam', 'Homework', 'Class', 'Book'], 'Exam'),
                    $this->fib('I study in the ___ every day.', 'library'),
                    $this->fib('I am a university ___.', 'student'),
                ]),
                // 4
                $this->lesson('Subjects & Classes', '<h3>Subjects & Classes</h3><p><b>Math</b> (matematika), <b>Science</b> (sains), <b>History</b> (sejarah), <b>English</b> (bahasa Inggris), <b>Art</b> (seni).</p>', [
                    $this->mc('Which subject involves numbers?', ['Math', 'History', 'Art', 'English'], 'Math'),
                    $this->mc('Which subject involves the past?', ['History', 'Math', 'Art', 'Science'], 'History'),
                    $this->mc('Which subject involves paintings?', ['Art', 'Math', 'History', 'English'], 'Art'),
                    $this->fib('I love learning ___. (sains)', 'science'),
                    $this->fib('I practice speaking ___.', 'English'),
                ]),
                // 5 - REVIEW (10 Q)
                $this->lesson('Review: Jobs & Education', '<h3>Review 1</h3><p>Reviewing Jobs, Workplaces, and Education.</p>', [
                    $this->mc('Who teaches at university?', ['Professor', 'Teacher', 'Doctor', 'Chef'], 'Professor'),
                    $this->mc('Where do artists work?', ['Studio', 'Office', 'School', 'Hospital'], 'Studio'),
                    $this->mc('What subject involves numbers?', ['Math', 'Art', 'History', 'English'], 'Math'),
                    $this->mc('Who works in a hospital?', ['Doctor', 'Teacher', 'Engineer', 'Artist'], 'Doctor'),
                    $this->mc('Where do students have classes?', ['Classroom', 'Library', 'Office', 'Workshop'], 'Classroom'),
                    $this->mc('Who works in a workshop?', ['Engineer', 'Chef', 'Teacher', 'Doctor'], 'Engineer'),
                    $this->mc('Which subject involves the past?', ['History', 'Math', 'Science', 'Art'], 'History'),
                    $this->fib('I study in the ___. (perpustakaan)', 'library'),
                    $this->fib('I am a university ___.', 'student'),
                    $this->fib('The ___ cooks great food.', 'chef'),
                ], 30),
                // 6
                $this->lesson('Daily Work', '<h3>Daily Work</h3><p><b>Meeting</b> (rapat), <b>Email</b> (surel), <b>Call</b> (panggilan), <b>Project</b> (proyek), <b>Report</b> (laporan).</p>', [
                    $this->mc('What is a group discussion at work?', ['Meeting', 'Project', 'Report', 'Email'], 'Meeting'),
                    $this->mc('How do you send electronic letters?', ['Email', 'Call', 'Meeting', 'Project'], 'Email'),
                    $this->mc('What do you write to show progress?', ['Report', 'Meeting', 'Call', 'Project'], 'Report'),
                    $this->fib('I have a ___ with my boss.', 'meeting'),
                    $this->fib('Send me an ___ about the project.', 'email'),
                ]),
                // 7
                $this->lesson('Work Schedule', '<h3>Work Schedule</h3><p><b>Shift</b> (giliran kerja), <b>Full-time</b> (penuh waktu), <b>Part-time</b> (paruh waktu), <b>Overtime</b> (lembur), <b>Deadline</b> (tenggat waktu).</p>', [
                    $this->mc('What do you call working more than normal?', ['Overtime', 'Shift', 'Full-time', 'Part-time'], 'Overtime'),
                    $this->mc('What is the last date to finish something?', ['Deadline', 'Shift', 'Full-time', 'Part-time'], 'Deadline'),
                    $this->mc('Which means working every day?', ['Full-time', 'Part-time', 'Overtime', 'Shift'], 'Full-time'),
                    $this->fib('I work the night ___.', 'shift'),
                    $this->fib('I must finish before the ___.', 'deadline'),
                ]),
                // 8
                $this->lesson('Studying', '<h3>Studying</h3><p><b>Research</b> (penelitian), <b>Essay</b> (esai), <b>Presentation</b> (presentasi), <b>Project</b> (proyek), <b>Grade</b> (nilai).</p>', [
                    $this->mc('What is a short writing task?', ['Essay', 'Presentation', 'Research', 'Project'], 'Essay'),
                    $this->mc('How do you share ideas with the class?', ['Presentation', 'Essay', 'Research', 'Grade'], 'Presentation'),
                    $this->mc('What is the result of your exam?', ['Grade', 'Essay', 'Project', 'Research'], 'Grade'),
                    $this->fib('I am doing ___ for my project.', 'research'),
                    $this->fib('My ___ is due tomorrow.', 'essay'),
                ]),
                // 9
                $this->lesson('Skills', '<h3>Skills</h3><p><b>Computers</b> (komputer), <b>Writing</b> (menulis), <b>Communication</b> (komunikasi), <b>Teamwork</b> (kerja tim), <b>Management</b> (manajemen).</p>', [
                    $this->mc('Which skill is about working together?', ['Teamwork', 'Writing', 'Computers', 'Management'], 'Teamwork'),
                    $this->mc('Which skill is about using technology?', ['Computers', 'Writing', 'Management', 'Teamwork'], 'Computers'),
                    $this->mc('Which skill is about leading people?', ['Management', 'Writing', 'Computers', 'Teamwork'], 'Management'),
                    $this->fib('Good ___ is important for success.', 'communication'),
                    $this->fib('I am good at creative ___.', 'writing'),
                ]),
                // 10 - REVIEW (10 Q)
                $this->lesson('Review: Work Tasks & Skills', '<h3>Review 2</h3><p>Reviewing Daily Work, Work Schedule, Studying, and Skills.</p>', [
                    $this->mc('What is a group discussion?', ['Meeting', 'Report', 'Essay', 'Project'], 'Meeting'),
                    $this->mc('What is working more than normal?', ['Overtime', 'Shift', 'Full-time', 'Deadline'], 'Overtime'),
                    $this->mc('What is the result of an exam?', ['Grade', 'Presentation', 'Research', 'Essay'], 'Grade'),
                    $this->mc('Which skill is about leading people?', ['Management', 'Teamwork', 'Computers', 'Writing'], 'Management'),
                    $this->mc('What do you send electronically?', ['Email', 'Meeting', 'Call', 'Project'], 'Email'),
                    $this->mc('Working part of the day is...', ['Part-time', 'Full-time', 'Overtime', 'Shift'], 'Part-time'),
                    $this->mc('What is a short writing task?', ['Essay', 'Project', 'Report', 'Research'], 'Essay'),
                    $this->fib('I have a ___ with my boss.', 'meeting'),
                    $this->fib('Good ___ is key to teamwork.', 'communication'),
                    $this->fib('I must finish before the ___.', 'deadline'),
                ], 30),
                // 11
                $this->lesson('Responsibilities', '<h3>Responsibilities</h3><p><b>Task</b> (tugas), <b>Duties</b> (tugas), <b>Managing</b> (mengelola), <b>Leading</b> (memimpin), <b>Assisting</b> (membantu).</p>', [
                    $this->mc('What is something you must do?', ['Task', 'Duty', 'All correct', 'None'], 'All correct'),
                    $this->mc('What means to help someone?', ['Assisting', 'Managing', 'Leading', 'Task'], 'Assisting'),
                    $this->mc('What means to lead a team?', ['Leading', 'Managing', 'Assisting', 'Task'], 'Leading'),
                    $this->fib('What are your daily ___?', 'duties'),
                    $this->fib('I am ___ the team.', 'managing'),
                ]),
                // 12
                $this->lesson('Talking About Your Job', '<h3>Talking About Your Job</h3><p>Phrases: <b>I work as a...</b>, <b>My responsibilities include...</b>, <b>I love my job because...</b>.</p>', [
                    $this->mc('How do you say your job?', ['I work as a teacher.', 'I love teacher.', 'I am teacher.', 'I need teacher.'], 'I work as a teacher.'),
                    $this->mc('What means "Tanggung jawab saya termasuk..."?', ['My responsibilities include...', 'I work as...', 'I love my job because...', 'I need assistance.'], 'My responsibilities include...'),
                    $this->mc('How to explain why you love your job?', ['I love my job because...', 'My job is...', 'I need my job.', 'I am my job.'], 'I love my job because...'),
                    $this->fib('I ___ as an engineer.', 'work'),
                    $this->fib('My ___ include helping clients.', 'responsibilities'),
                ]),
                // 13
                $this->lesson('Talking About Your Studies', '<h3>Talking About Your Studies</h3><p>Phrases: <b>I am studying...</b>, <b>My major is...</b>, <b>I want to learn...</b>.</p>', [
                    $this->mc('How do you say your field of study?', ['My major is Engineering.', 'I am Engineering.', 'I need Engineering.', 'I study Engineering.'], 'My major is Engineering.'),
                    $this->mc('How do you talk about current studies?', ['I am studying...', 'I study...', 'I want to study...', 'I need to study...'], 'I am studying...'),
                    $this->mc('How to talk about future goals?', ['I want to learn...', 'I study...', 'I am studying...', 'I need to study...'], 'I want to learn...'),
                    $this->fib('I am ___ computer science.', 'studying'),
                    $this->fib('My ___ is History.', 'major'),
                ]),
                // 14
                $this->lesson('Goals & Plans', '<h3>Goals & Plans</h3><p><b>Goal</b> (tujuan), <b>Plan</b> (rencana), <b>Future</b> (masa depan), <b>Career</b> (karier).</p>', [
                    $this->mc('What do you want to achieve?', ['Goal', 'Plan', 'Future', 'Career'], 'Goal'),
                    $this->mc('What is a steps to achieve a goal?', ['Plan', 'Goal', 'Future', 'Career'], 'Plan'),
                    $this->mc('What is your professional life?', ['Career', 'Future', 'Goal', 'Plan'], 'Career'),
                    $this->fib('My ___ is to become a manager.', 'goal'),
                    $this->fib('I have a ___ for my career.', 'plan'),
                ]),
                // 15 - FINAL REVIEW (10 Q)
                $this->lesson('Unit Review', '<h3>Unit 8 Final Review</h3><p>Reviewing all topics from Unit 8: Work & Study.</p>', [
                    $this->mc('Who teaches students?', ['Teacher', 'Doctor', 'Chef', 'Engineer'], 'Teacher'),
                    $this->mc('Where do students have classes?', ['Classroom', 'Library', 'Office', 'Workshop'], 'Classroom'),
                    $this->mc('What is a group discussion?', ['Meeting', 'Report', 'Essay', 'Project'], 'Meeting'),
                    $this->mc('What is working more than normal?', ['Overtime', 'Shift', 'Full-time', 'Deadline'], 'Overtime'),
                    $this->mc('Which skill is about technology?', ['Computers', 'Writing', 'Management', 'Teamwork'], 'Computers'),
                    $this->mc('What is a short writing task?', ['Essay', 'Presentation', 'Research', 'Project'], 'Essay'),
                    $this->mc('What means helping someone?', ['Assisting', 'Managing', 'Leading', 'Task'], 'Assisting'),
                    $this->fib('My ___ is to become a manager.', 'goal'),
                    $this->fib('I work ___ an engineer.', 'as'),
                    $this->fib('My ___ is History.', 'major'),
                ], 30),
            ]
        ];
    }
}
