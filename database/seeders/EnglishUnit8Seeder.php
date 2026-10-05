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

    /**
     * Pola soal:
     * - Lesson 1-4   : 5 soal (materi sendiri)
     * - Lesson 5     : 10 soal (5 materi sendiri + 5 review Lesson 1-4)
     * - Lesson 6-9   : 5 soal (materi sendiri)
     * - Lesson 10    : 10 soal (5 materi sendiri + 5 review Lesson 6-9)
     * - Lesson 11-14 : 5 soal (materi sendiri)
     * - Lesson 15    : 10 soal (Unit Review, seluruh unit)
     */
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
                    $this->fib('I study in the ___ every day. (perpustakaan)', 'library'),
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
                // 5 - Daily Work: 5 materi + 5 review (Lesson 1-4)
                $this->lesson('Daily Work', '<h3>Daily Work</h3><p><b>Meeting</b> (rapat), <b>Email</b> (surel), <b>Call</b> (panggilan), <b>Project</b> (proyek), <b>Report</b> (laporan).</p>', [
                    // Materi (5 Q)
                    $this->mc('What is a group discussion at work?', ['Meeting', 'Project', 'Report', 'Email'], 'Meeting'),
                    $this->mc('How do you send electronic letters?', ['Email', 'Call', 'Meeting', 'Project'], 'Email'),
                    $this->mc('What do you write to show progress?', ['Report', 'Meeting', 'Call', 'Project'], 'Report'),
                    $this->fib('I have a ___ with my boss. (rapat)', 'meeting'),
                    $this->fib('Send me an ___ about the project. (surel)', 'email'),
                    // Review Lesson 1-4 (5 Q)
                    $this->mc('Who teaches children at school?', ['Teacher', 'Doctor', 'Chef', 'Engineer'], 'Teacher'),                  // L1 Jobs & Professions
                    $this->mc('Where do nurses and doctors work?', ['Hospital', 'Studio', 'School', 'Workshop'], 'Hospital'),            // L2 Workplaces
                    $this->mc('Who teaches at university?', ['Professor', 'Student', 'Artist', 'Chef'], 'Professor'),                   // L3 School & University
                    $this->mc('Which subject involves numbers?', ['Math', 'History', 'Art', 'English'], 'Math'),                        // L4 Subjects & Classes
                    $this->fib('The ___ cooks great food. (koki)', 'chef'),                                                             // L1 Jobs & Professions
                ], 30),
                // 6
                $this->lesson('Work Schedule', '<h3>Work Schedule</h3><p><b>Shift</b> (giliran kerja), <b>Full-time</b> (penuh waktu), <b>Part-time</b> (paruh waktu), <b>Overtime</b> (lembur), <b>Deadline</b> (tenggat waktu).</p>', [
                    $this->mc('What do you call working more than normal?', ['Overtime', 'Shift', 'Full-time', 'Part-time'], 'Overtime'),
                    $this->mc('What is the last date to finish something?', ['Deadline', 'Shift', 'Full-time', 'Part-time'], 'Deadline'),
                    $this->mc('Working only part of the day is...', ['Part-time', 'Full-time', 'Overtime', 'Deadline'], 'Part-time'),
                    $this->fib('I work the night ___. (giliran kerja)', 'shift'),
                    $this->fib('I must finish before the ___. (tenggat waktu)', 'deadline'),
                ]),
                // 7
                $this->lesson('Studying', '<h3>Studying</h3><p><b>Research</b> (penelitian), <b>Essay</b> (esai), <b>Presentation</b> (presentasi), <b>Project</b> (proyek), <b>Grade</b> (nilai).</p>', [
                    $this->mc('What is a short writing task?', ['Essay', 'Presentation', 'Research', 'Project'], 'Essay'),
                    $this->mc('How do you share ideas with the class?', ['Presentation', 'Essay', 'Research', 'Grade'], 'Presentation'),
                    $this->mc('What is the result of your exam?', ['Grade', 'Essay', 'Project', 'Research'], 'Grade'),
                    $this->fib('I am doing ___ for my project. (penelitian)', 'research'),
                    $this->fib('My ___ is due tomorrow. (esai)', 'essay'),
                ]),
                // 8
                $this->lesson('Skills', '<h3>Skills</h3><p><b>Computers</b> (komputer), <b>Writing</b> (menulis), <b>Communication</b> (komunikasi), <b>Teamwork</b> (kerja tim), <b>Management</b> (manajemen).</p>', [
                    $this->mc('Which skill is about working together?', ['Teamwork', 'Writing', 'Computers', 'Management'], 'Teamwork'),
                    $this->mc('Which skill is about using technology?', ['Computers', 'Writing', 'Management', 'Teamwork'], 'Computers'),
                    $this->mc('Which skill is about leading people?', ['Management', 'Writing', 'Computers', 'Teamwork'], 'Management'),
                    $this->fib('Good ___ is important for success. (komunikasi)', 'communication'),
                    $this->fib('I am good at creative ___. (menulis)', 'writing'),
                ]),
                // 9
                $this->lesson('Responsibilities', '<h3>Responsibilities</h3><p><b>Task</b> (tugas), <b>Duties</b> (kewajiban), <b>Managing</b> (mengelola), <b>Leading</b> (memimpin), <b>Assisting</b> (membantu).</p>', [
                    $this->mc('What is a job you must do at work?', ['Task', 'Holiday', 'Salary', 'Shift'], 'Task'),
                    $this->mc('What does "assisting" mean?', ['Helping someone', 'Leading a team', 'Writing an essay', 'Taking an exam'], 'Helping someone'),
                    $this->mc('What does "leading" mean?', ['Guiding a team', 'Helping someone', 'Writing a report', 'Taking a call'], 'Guiding a team'),
                    $this->fib('What are your daily ___? (kewajiban)', 'duties'),
                    $this->fib('I am ___ the team. (mengelola)', 'managing'),
                ]),
                // 10 - Talking About Your Job: 5 materi + 5 review (Lesson 6-9)
                $this->lesson('Talking About Your Job', '<h3>Talking About Your Job</h3><p>Phrases: <b>I work as a...</b>, <b>My responsibilities include...</b>, <b>I love my job because...</b>.</p>', [
                    // Materi (5 Q)
                    $this->mc('How do you say your job?', ['I work as a teacher.', 'I love teacher.', 'I am teacher.', 'I need teacher.'], 'I work as a teacher.'),
                    $this->mc('What does "Tanggung jawab saya termasuk..." mean in English?', ['My responsibilities include...', 'I work as...', 'I love my job because...', 'I need assistance.'], 'My responsibilities include...'),
                    $this->mc('How do you explain why you love your job?', ['I love my job because...', 'My job is...', 'I need my job.', 'I am my job.'], 'I love my job because...'),
                    $this->fib('I ___ as an engineer.', 'work'),
                    $this->fib('My ___ include helping clients. (tanggung jawab)', 'responsibilities'),
                    // Review Lesson 6-9 (5 Q)
                    $this->mc('What is the last date to finish a task?', ['Deadline', 'Shift', 'Grade', 'Meeting'], 'Deadline'),                           // L6 Work Schedule
                    $this->mc('What is the result of an exam?', ['Grade', 'Presentation', 'Research', 'Essay'], 'Grade'),                                   // L7 Studying
                    $this->mc('Which skill is about working together?', ['Teamwork', 'Writing', 'Computers', 'Management'], 'Teamwork'),                    // L8 Skills
                    $this->mc('What does "managing" mean?', ['Running a team or project', 'Taking an exam', 'Writing an essay', 'Sending an email'], 'Running a team or project'), // L9 Responsibilities
                    $this->fib('I work the night ___. (giliran kerja)', 'shift'),                                                                          // L6 Work Schedule
                ], 30),
                // 11
                $this->lesson('Talking About Your Studies', '<h3>Talking About Your Studies</h3><p>Phrases: <b>I am studying...</b>, <b>My major is...</b>, <b>I want to learn...</b>.</p>', [
                    $this->mc('Which sentence is correct?', ['My major is Engineering.', 'My major are Engineering.', 'I major is Engineering.', 'Major my Engineering.'], 'My major is Engineering.'),
                    $this->mc('You are studying right now. You say...', ['I am studying English.', 'I studied English tomorrow.', 'I studies English.', 'I will studying English.'], 'I am studying English.'),
                    $this->mc('How do you say "Saya ingin belajar..."?', ['I want to learn...', 'I am learning...', 'I learned...', 'I learn...'], 'I want to learn...'),
                    $this->fib('I am ___ computer science. (sedang belajar)', 'studying'),
                    $this->fib('My ___ is History. (jurusan)', 'major'),
                ]),
                // 12
                $this->lesson('Goals & Plans', '<h3>Goals & Plans</h3><p><b>Goal</b> (tujuan), <b>Plan</b> (rencana), <b>Future</b> (masa depan), <b>Career</b> (karier).</p>', [
                    $this->mc('What do you want to achieve?', ['Goal', 'Plan', 'Future', 'Career'], 'Goal'),
                    $this->mc('What are the steps to achieve a goal?', ['Plan', 'Goal', 'Future', 'Career'], 'Plan'),
                    $this->mc('What is your professional life?', ['Career', 'Future', 'Goal', 'Plan'], 'Career'),
                    $this->fib('My ___ is to become a manager. (tujuan)', 'goal'),
                    $this->fib('I have a ___ for my career. (rencana)', 'plan'),
                ]),
                // 13
                $this->lesson('Workplace Conversations', '<h3>Workplace Conversations</h3><p><i>A: Good morning! Do you have a minute?</i><br><i>B: Sure. What do you need?</i><br><i>A: Can we schedule a meeting for Monday?</i><br><i>B: Yes. I will send the report today.</i></p>', [
                    $this->mc('How do you greet a colleague in the morning?', ['Good morning!', 'Good night!', 'See you!', 'Goodbye!'], 'Good morning!'),
                    $this->mc('You need to talk to your boss. You ask...', ['Do you have a minute?', 'Where is the bank?', 'Who are you?', 'I am hungry.'], 'Do you have a minute?'),
                    $this->mc('Your boss asks for the report. You answer...', ['I will send it today.', 'I like coffee.', 'It is raining.', 'Good night.'], 'I will send it today.'),
                    $this->fib('Can we ___ a meeting for Monday? (menjadwalkan)', 'schedule'),
                    $this->fib('Do you have a ___? I need your help. (sebentar)', 'minute'),
                ]),
                // 14
                $this->lesson('Interview Basics', '<h3>Interview Basics</h3><p><b>Tell me about yourself</b>, <b>What are your strengths?</b>, <b>Why do you want this job?</b>, <b>Thank you for your time</b>. Also: <b>Resume</b> (CV), <b>Interviewer</b> (pewawancara).</p>', [
                    $this->mc('An interviewer says "Tell me about yourself." You should...', ['Introduce yourself and your experience', 'Say goodbye', 'Ask for water', 'Stay silent'], 'Introduce yourself and your experience'),
                    $this->mc('What does "strengths" mean?', ['Kekuatan', 'Kelemahan', 'Jadwal', 'Gaji'], 'Kekuatan'),
                    $this->mc('What do you say at the end of an interview?', ['Thank you for your time.', 'Give me the job!', 'I am tired.', 'Where is the exit?'], 'Thank you for your time.'),
                    $this->fib('Please send your ___ before the interview. (CV)', 'resume'),
                    $this->fib('Why do you ___ this job? (ingin)', 'want'),
                ]),
                // 15 - UNIT REVIEW: 10 soal (seluruh unit)
                $this->lesson('Unit Review', '<h3>Unit 8 Review</h3><p>Reviewing all topics from Unit 8: Work & Study.</p>', [
                    $this->mc('Who teaches students?', ['Teacher', 'Doctor', 'Chef', 'Engineer'], 'Teacher'),                                               // L1
                    $this->mc('Where do students have classes?', ['Classroom', 'Library', 'Office', 'Workshop'], 'Classroom'),                              // L3
                    $this->mc('What is a group discussion at work?', ['Meeting', 'Report', 'Essay', 'Project'], 'Meeting'),                                 // L5
                    $this->mc('What do you call working more than normal?', ['Overtime', 'Shift', 'Full-time', 'Deadline'], 'Overtime'),                    // L6
                    $this->mc('Which skill is about technology?', ['Computers', 'Writing', 'Management', 'Teamwork'], 'Computers'),                         // L8
                    $this->mc('What does "assisting" mean?', ['Helping someone', 'Leading a team', 'Writing an essay', 'Taking an exam'], 'Helping someone'), // L9
                    $this->mc('What do you say at the end of an interview?', ['Thank you for your time.', 'Give me the job!', 'I am tired.', 'Where is the exit?'], 'Thank you for your time.'), // L14
                    $this->fib('I work ___ an engineer.', 'as'),                                                                                            // L10
                    $this->fib('My ___ is History. (jurusan)', 'major'),                                                                                    // L11
                    $this->fib('My ___ is to become a manager. (tujuan)', 'goal'),                                                                          // L12
                ], 30),
            ]
        ];
    }
}
