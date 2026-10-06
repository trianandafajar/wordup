<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnglishUnit5Seeder extends Seeder
{
    use CanSeedEnglishContent;

    public function run(): void
    {
        DB::transaction(function () {
            $course = Course::where('title', 'Complete English Mastery')->first();

            if (! $course) {
                return;
            }

            $unitOrder = 5;
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
            'title' => 'Unit 5: Time & Routine',
            'lessons' => [
                // 1
                $this->lesson('Numbers & Counting', '<h3>Numbers & Counting</h3><p><b>Twenty</b> (20), <b>Thirty</b> (30), <b>Forty</b> (40), <b>Fifty</b> (50), <b>Sixty</b> (60), <b>Seventy</b> (70), <b>Eighty</b> (80), <b>Ninety</b> (90), <b>One hundred</b> (100).</p>', [
                    $this->mc('What is 30 in English?', ['Twenty', 'Thirty', 'Forty', 'Fifty'], 'Thirty'),
                    $this->mc('What is 100 in English?', ['Ten', 'One hundred', 'Thousand', 'Hundred'], 'One hundred'),
                    $this->mc('What is 80?', ['Seventy', 'Eighty', 'Ninety', 'Sixty'], 'Eighty'),
                    $this->fib('Twenty plus thirty is ___.', 'fifty'),
                    $this->fib('Ninety minus ten is ___.', 'eighty'),
                ]),
                // 2
                $this->lesson('Telling the Time', '<h3>Telling the Time</h3><p><b>O\'clock</b> (tepat), <b>Half past</b> (setengah), <b>Quarter past</b> (seperempat lewat), <b>Quarter to</b> (seperempat sebelum).</p>', [
                    $this->mc('What time is 3:00?', ['Three o\'clock', 'Half past three', 'Quarter past three', 'Quarter to three'], 'Three o\'clock'),
                    $this->mc('30 minutes past the hour is...', ['Quarter past', 'Half past', 'O\'clock', 'Quarter to'], 'Half past'),
                    $this->mc('What is "seperempat lewat 4"?', ['Quarter to four', 'Quarter past four', 'Half past four', 'Four o\'clock'], 'Quarter past four'),
                    $this->fib('It is ___. (tepat jam 6)', 'six o\'clock'),
                    $this->fib('It is half past ___. (jam 9)', 'nine'),
                ]),
                // 3
                $this->lesson('Days of the Week', '<h3>Days of the Week</h3><p>Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday.</p>', [
                    $this->mc('Which day comes after Tuesday?', ['Monday', 'Wednesday', 'Thursday', 'Friday'], 'Wednesday'),
                    $this->mc('Which days are weekend?', ['Monday and Tuesday', 'Saturday and Sunday', 'Wednesday and Thursday', 'Friday and Saturday'], 'Saturday and Sunday'),
                    $this->mc('How many days in a week?', ['5', '6', '7', '8'], '7'),
                    $this->fib('The day before Friday is ___.', 'Thursday'),
                    $this->fib('The day after Saturday is ___.', 'Sunday'),
                ]),
                // 4
                $this->lesson('Months', '<h3>Months</h3><p>January, February, March, April, May, June, July, August, September, October, November, December.</p>', [
                    $this->mc('How many months in a year?', ['10', '11', '12', '13'], '12'),
                    $this->mc('Which month comes after March?', ['February', 'April', 'May', 'June'], 'April'),
                    $this->mc('Which month comes before December?', ['October', 'November', 'January', 'September'], 'November'),
                    $this->fib('The first month of the year is ___.', 'January'),
                    $this->fib('The month after August is ___.', 'September'),
                ]),
                // 5 - Dates: 5 materi + 5 review (Lesson 1-4)
                $this->lesson('Dates', '<h3>Dates</h3><p>How to say dates: <b>May 5th</b> (5 Mei), <b>January 1st</b> (1 Januari), <b>20th of March</b> (20 Maret). <b>Date</b> = tanggal.</p>', [
                    // Materi (5 Q)
                    $this->mc('What is "tanggal" in English?', ['Date', 'Day', 'Month', 'Year'], 'Date'),
                    $this->mc('What is the short form of "second"?', ['2nd', '2th', '2rd', '2st'], '2nd'),
                    $this->mc('How do you say "20 Maret"?', ['20th of March', 'March 20', 'March 20th', 'All correct'], 'All correct'),
                    $this->fib('Today is the ___ of June. (tanggal 15)', '15th'),
                    $this->fib('New Year is on January ___. (tanggal 1)', '1st'),
                    // Review Lesson 1-4 (5 Q)
                    $this->mc('What is 60?', ['Fifty', 'Sixty', 'Seventy', 'Forty'], 'Sixty'),                                        // L1 Numbers & Counting
                    $this->mc('What is 9:30?', ['Half past nine', 'Quarter past nine', 'Nine o\'clock', 'Quarter to nine'], 'Half past nine'), // L2 Telling the Time
                    $this->mc('How many days in a week?', ['5', '6', '7', '8'], '7'),                                                 // L3 Days of the Week
                    $this->fib('Ninety plus ten is ___.', 'one hundred'),                                                             // L1 Numbers & Counting
                    $this->fib('The month before March is ___.', 'February'),                                                         // L4 Months
                ], 30),
                // 6
                $this->lesson('Morning, Afternoon & Night', '<h3>Morning, Afternoon & Night</h3><p><b>Morning</b> (pagi), <b>Afternoon</b> (siang), <b>Evening</b> (sore), <b>Night</b> (malam).</p>', [
                    $this->mc('What time is morning?', ['6 AM - 12 PM', '12 PM - 6 PM', '6 PM - 12 AM', '12 AM - 6 AM'], '6 AM - 12 PM'),
                    $this->mc('Good evening is used when...', ['Waking up', 'Evening greeting', 'Going to bed', 'Having lunch'], 'Evening greeting'),
                    $this->mc('What time is night?', ['After sunset', 'After sunrise', 'Noon', 'Morning'], 'After sunset'),
                    $this->fib('Good ___, everyone!', 'morning'),
                    $this->fib('I go to bed at ___.', 'night'),
                ]),
                // 7
                $this->lesson('Daily Schedule', '<h3>Daily Schedule</h3><p><b>Wake up</b> (bangun), <b>Go to work</b> (pergi kerja), <b>Have lunch</b> (makan siang), <b>Go to bed</b> (tidur).</p>', [
                    $this->mc('What do you do at 7 AM?', ['Wake up', 'Go to bed', 'Have dinner', 'Watch TV'], 'Wake up'),
                    $this->mc('When do you eat lunch?', ['12 PM', '7 AM', '10 PM', '3 AM'], '12 PM'),
                    $this->mc('What do you do before sleeping?', ['Go to bed', 'Wake up', 'Have breakfast', 'Go to work'], 'Go to bed'),
                    $this->fib('I ___ up at 6 AM.', 'wake'),
                    $this->fib('I have ___ at noon.', 'lunch'),
                ]),
                // 8
                $this->lesson('Frequency', '<h3>Frequency</h3><p><b>Always</b> (selalu), <b>Often</b> (sering), <b>Sometimes</b> (kadang), <b>Never</b> (tidak pernah).</p>', [
                    $this->mc('Which means "selalu"?', ['Often', 'Always', 'Sometimes', 'Never'], 'Always'),
                    $this->mc('Which means "tidak pernah"?', ['Never', 'Always', 'Often', 'Sometimes'], 'Never'),
                    $this->mc('Which is more frequent: often or sometimes?', ['Often', 'Sometimes', 'Never', 'Always'], 'Often'),
                    $this->fib('I ___ eat breakfast.', 'always'),
                    $this->fib('I ___ go to the gym.', 'sometimes'),
                ]),
                // 9
                $this->lesson('How Often?', '<h3>How Often?</h3><p>Asking frequency: <b>How often do you...?</b> (Seberapa sering Anda...?), <b>Every day</b> (setiap hari), <b>Twice a week</b> (dua kali seminggu).</p>', [
                    $this->mc('How do you ask about frequency?', ['How often?', 'What time?', 'Where?', 'Who?'], 'How often?'),
                    $this->mc('Twice means...', ['Two times', 'One time', 'Three times', 'Never'], 'Two times'),
                    $this->mc('Every day means...', ['Daily', 'Weekly', 'Monthly', 'Yearly'], 'Daily'),
                    $this->fib('___ do you exercise? (seberapa sering)', 'How often'),
                    $this->fib('I go to the gym ___ a week.', 'twice'),
                ]),
                // 10 - Making Plans: 5 materi + 5 review (Lesson 6-9)
                $this->lesson('Making Plans', '<h3>Making Plans</h3><p><b>Tomorrow</b> (besok), <b>Next week</b> (minggu depan), <b>Later</b> (nanti), <b>Let\'s</b> (ayo).</p>', [
                    // Materi (5 Q)
                    $this->mc('What does "tomorrow" mean?', ['Yesterday', 'Tomorrow', 'Today', 'Next week'], 'Tomorrow'),
                    $this->mc('How do you suggest something?', ['Let\'s...', 'I am...', 'I was...', 'I have...'], 'Let\'s...'),
                    $this->mc('What is "nanti" in English?', ['Later', 'Yesterday', 'Tomorrow', 'Today'], 'Later'),
                    $this->fib('Let\'s ___ tomorrow!', 'meet'),
                    $this->fib('I will see you ___. (nanti)', 'later'),
                    // Review Lesson 6-9 (5 Q)
                    $this->mc('What time is the afternoon?', ['12 PM - 6 PM', '6 AM - 12 PM', '6 PM - 12 AM', '12 AM - 6 AM'], '12 PM - 6 PM'), // L6 Morning, Afternoon & Night
                    $this->mc('What do you do before sleeping?', ['Go to bed', 'Wake up', 'Have breakfast', 'Go to work'], 'Go to bed'), // L7 Daily Schedule
                    $this->mc('Which means "kadang"?', ['Always', 'Often', 'Sometimes', 'Never'], 'Sometimes'),                       // L8 Frequency
                    $this->fib('___ do you exercise? (seberapa sering)', 'How often'),                                                // L9 How Often?
                    $this->fib('I ___ eat breakfast. I never skip it. (selalu)', 'always'),                                           // L8 Frequency
                ], 30),
                // 11
                $this->lesson('Appointments', '<h3>Appointments</h3><p><b>Appointment</b> (janji), <b>Schedule</b> (jadwal), <b>Meeting</b> (rapat), <b>Available</b> (tersedia).</p>', [
                    $this->mc('What is "janji" in English?', ['Appointment', 'Promise', 'Plan', 'Wish'], 'Appointment'),
                    $this->mc('Are you free on Monday?', ['Are you available?', 'Are you busy?', 'Are you tired?', 'Are you hungry?'], 'Are you available?'),
                    $this->mc('A meeting is a...', ['Scheduled gathering', 'Party', 'Game', 'Vacation'], 'Scheduled gathering'),
                    $this->fib('I have an ___ at 3 PM.', 'appointment'),
                    $this->fib('Are you ___ on Friday? (tersedia)', 'available'),
                ]),
                // 12
                $this->lesson('Schedules', '<h3>Schedules</h3><p><b>Schedule</b> (jadwal), <b>Calendar</b> (kalender), <b>Agenda</b> (agenda), <b>Timetable</b> (jadwal waktu).</p>', [
                    $this->mc('What is a list of planned events?', ['Schedule', 'Menu', 'Map', 'List'], 'Schedule'),
                    $this->mc('Where do you check dates?', ['Calendar', 'Clock', 'Watch', 'Timer'], 'Calendar'),
                    $this->mc('A train timetable shows...', ['Departure times', 'Recipes', 'Prices', 'Directions'], 'Departure times'),
                    $this->fib('Check your ___.', 'calendar'),
                    $this->fib('The bus ___ says it arrives at 8.', 'timetable'),
                ]),
                // 13
                $this->lesson('Talking About Yesterday', '<h3>Talking About Yesterday</h3><p><b>Yesterday</b> (kemarin), <b>Last night</b> (tadi malam), <b>Last week</b> (minggu lalu), <b>Ago</b> (yang lalu). Past verbs: <b>went</b> (pergi), <b>ate</b> (makan), <b>watched</b> (menonton).</p>', [
                    $this->mc('What is "kemarin" in English?', ['Yesterday', 'Tomorrow', 'Today', 'Later'], 'Yesterday'),
                    $this->mc('I ___ to school yesterday.', ['went', 'go', 'goes', 'going'], 'went'),
                    $this->mc('"Three days ___" means 3 days in the past.', ['ago', 'later', 'next', 'soon'], 'ago'),
                    $this->fib('I ___ TV last night. (menonton)', 'watched'),
                    $this->fib('We ___ pizza yesterday. (makan)', 'ate'),
                ]),
                // 14
                $this->lesson('Talking About Tomorrow', '<h3>Talking About Tomorrow</h3><p>Future plans: <b>I will...</b> (Saya akan...), <b>I am going to...</b> (Saya akan / berencana...), <b>Next week</b> (minggu depan), <b>Tomorrow</b> (besok).</p>', [
                    $this->mc('I ___ visit my grandmother tomorrow.', ['will', 'went', 'was', 'did'], 'will'),
                    $this->mc('I am ___ to study next week.', ['going', 'went', 'go', 'goes'], 'going'),
                    $this->mc('Which sentence is about the future?', ['I will go to Bali.', 'I went to Bali.', 'I go to Bali every year.', 'I was in Bali.'], 'I will go to Bali.'),
                    $this->fib('I ___ meet my friend tomorrow. (akan)', 'will'),
                    $this->fib('She is ___ to cook dinner tonight. (akan)', 'going'),
                ]),
                // 15 - UNIT REVIEW: 10 soal (seluruh unit, fokus Lesson 11-14)
                $this->lesson('Unit Review', '<h3>Unit 5 Review</h3><p>Reviewing all topics from Unit 5: Time & Routine.</p>', [
                    $this->mc('Which day is after Friday?', ['Thursday', 'Saturday', 'Sunday', 'Monday'], 'Saturday'),                 // L3
                    $this->mc('How do you say "15 April"?', ['April 15th', '15 April', 'April 15', 'All correct'], 'All correct'),     // L5
                    $this->mc('Which means "selalu"?', ['Often', 'Always', 'Sometimes', 'Never'], 'Always'),                          // L8
                    $this->mc('How do you ask "seberapa sering"?', ['How often?', 'What time?', 'Where?', 'Who?'], 'How often?'),     // L9
                    $this->mc('What is "janji" in English?', ['Appointment', 'Promise', 'Plan', 'Wish'], 'Appointment'),              // L11
                    $this->mc('Where do you check dates?', ['Calendar', 'Clock', 'Watch', 'Timer'], 'Calendar'),                      // L12
                    $this->mc('I ___ to the market yesterday.', ['went', 'go', 'goes', 'going'], 'went'),                             // L13
                    $this->mc('Which sentence is about the future?', ['I will go to Bali.', 'I went to Bali.', 'I go to Bali every year.', 'I was in Bali.'], 'I will go to Bali.'), // L14
                    $this->fib('I go to the gym ___ a week.', 'twice'),                                                               // L9
                    $this->fib('Let\'s meet ___. (besok)', 'tomorrow'),                                                               // L10
                ], 30),
            ],
        ];
    }
}
