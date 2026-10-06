<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnglishUnit10Seeder extends Seeder
{
    use CanSeedEnglishContent;

    public function run(): void
    {
        DB::transaction(function () {
            $course = Course::where('title', 'Complete English Mastery')->first();

            if (! $course) {
                return;
            }

            $unitOrder = 10;
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
     * - Lesson 15    : 10 soal (Final Review, seluruh unit)
     */
    private function getUnitData(): array
    {
        return [
            'title' => 'Unit 10: Real-World Communication',
            'lessons' => [
                // 1
                $this->lesson('Telling a Story', '<h3>Telling a Story</h3><p><b>Once upon a time</b> (pada suatu hari), <b>Suddenly</b> (tiba-tiba), <b>Finally</b> (akhirnya), <b>The end</b> (tamat).</p>', [
                    $this->mc('How do you start a story?', ['Once upon a time', 'The end', 'Suddenly', 'Finally'], 'Once upon a time'),
                    $this->mc('What means "tiba-tiba"?', ['Suddenly', 'Finally', 'Once', 'The end'], 'Suddenly'),
                    $this->mc('What is the last part of a story?', ['The end', 'Once upon a time', 'Suddenly', 'Finally'], 'The end'),
                    $this->fib('___ upon a time, there was a prince. (Pada)', 'Once'),
                    $this->fib('___! I found my keys. (Tiba-tiba)', 'Suddenly'),
                ]),
                // 2
                $this->lesson('Talking About Experiences', '<h3>Talking About Experiences</h3><p><b>I have been to...</b> (Saya pernah ke...), <b>I have visited...</b> (Saya pernah mengunjungi...), <b>I have tried...</b> (Saya pernah mencoba...).</p>', [
                    $this->mc('How do you say "Saya pernah ke Bali"?', ['I have been to Bali.', 'I went Bali.', 'I am Bali.', 'I was Bali.'], 'I have been to Bali.'),
                    $this->mc('Which sentence is correct?', ['I have been to Japan.', 'I have go to Japan.', 'I has been to Japan.', 'I have being to Japan.'], 'I have been to Japan.'),
                    $this->mc('Which word means "mengunjungi"?', ['Visited', 'Gone', 'Been', 'Arrived'], 'Visited'),
                    $this->fib('I have ___ to Japan. (pernah ke)', 'been'),
                    $this->fib('I have ___ Paris. (mengunjungi)', 'visited'),
                ]),
                // 3
                $this->lesson('Talking About the Past', '<h3>Talking About the Past</h3><p><b>Yesterday</b> (kemarin), <b>Last week</b> (minggu lalu), <b>Went</b> (pergi, lampau), <b>Did</b> (melakukan, lampau).</p>', [
                    $this->mc('What is the past of "go"?', ['Went', 'Gone', 'Going', 'Go'], 'Went'),
                    $this->mc('What is the past of "do"?', ['Did', 'Does', 'Done', 'Doing'], 'Did'),
                    $this->mc('What means "kemarin"?', ['Yesterday', 'Tomorrow', 'Today', 'Next week'], 'Yesterday'),
                    $this->fib('I ___ to the market yesterday. (pergi)', 'went'),
                    $this->fib('What ___ you eat? (lampau)', 'did'),
                ]),
                // 4
                $this->lesson('Talking About the Future', '<h3>Talking About the Future</h3><p><b>Will</b> (akan), <b>Going to</b> (akan / berencana), <b>Tomorrow</b> (besok), <b>Next week</b> (minggu depan).</p>', [
                    $this->mc('What is used for future plans?', ['Going to', 'Did', 'Went', 'Was'], 'Going to'),
                    $this->mc('What is "besok" in English?', ['Tomorrow', 'Yesterday', 'Today', 'Next week'], 'Tomorrow'),
                    $this->mc('How do you say "Saya akan pergi"?', ['I will go.', 'I going go.', 'I go.', 'I did go.'], 'I will go.'),
                    $this->fib('I ___ visit Bali next month. (akan)', 'will'),
                    $this->fib('I am ___ to travel. (berencana)', 'going'),
                ]),
                // 5 - Giving Opinions: 5 materi + 5 review (Lesson 1-4)
                $this->lesson('Giving Opinions', '<h3>Giving Opinions</h3><p><b>I think...</b> (Saya rasa...), <b>In my opinion...</b> (Menurut saya...), <b>I believe...</b> (Saya percaya...).</p>', [
                    // Materi (5 Q)
                    $this->mc('How do you start an opinion?', ['I think...', 'I am...', 'I have...', 'I go...'], 'I think...'),
                    $this->mc('What means "Saya percaya..."?', ['I believe...', 'I forget...', 'I follow...', 'I borrow...'], 'I believe...'),
                    $this->mc('How do you say "menurut saya"?', ['In my opinion', 'I am', 'I go', 'I have'], 'In my opinion'),
                    $this->fib('I ___ this movie is great. (rasa)', 'think'),
                    $this->fib('___ my opinion, it is good. (Menurut)', 'In'),
                    // Review Lesson 1-4 (5 Q)
                    $this->mc('Which word means "akhirnya"?', ['Finally', 'Suddenly', 'Yesterday', 'Tomorrow'], 'Finally'),                      // L1 Telling a Story
                    $this->mc('Which sentence is correct?', ['I have visited Rome.', 'I have visit Rome.', 'I has visited Rome.', 'I visiting Rome.'], 'I have visited Rome.'), // L2 Experiences
                    $this->mc('Which word is used for the past?', ['Yesterday', 'Tomorrow', 'Next week', 'Will'], 'Yesterday'),                  // L3 Past
                    $this->fib('We ___ to the beach last week. (pergi)', 'went'),                                                               // L3 Past
                    $this->fib('She ___ travel to Japan next year. (akan)', 'will'),                                                            // L4 Future
                ], 30),
                // 6
                $this->lesson('Explaining Ideas', '<h3>Explaining Ideas</h3><p><b>Because</b> (karena), <b>So</b> (jadi), <b>Therefore</b> (oleh karena itu).</p>', [
                    $this->mc('What word shows reason?', ['Because', 'So', 'Therefore', 'And'], 'Because'),
                    $this->mc('What means "jadi"?', ['So', 'Because', 'Therefore', 'But'], 'So'),
                    $this->mc('What is "oleh karena itu"?', ['Therefore', 'Because', 'So', 'But'], 'Therefore'),
                    $this->fib('I am tired, ___ I will rest. (jadi)', 'so'),
                    $this->fib('___ it is raining, I stay home. (Karena)', 'Because'),
                ]),
                // 7
                $this->lesson('Agreeing & Disagreeing', '<h3>Agreeing & Disagreeing</h3><p><b>I agree</b> (Saya setuju), <b>I disagree</b> (Saya tidak setuju), <b>You are right</b> (Kamu benar), <b>I think differently</b> (Saya berpikir berbeda).</p>', [
                    $this->mc('How do you say "setuju"?', ['I agree.', 'I disagree.', 'No way.', 'Wrong.'], 'I agree.'),
                    $this->mc('How do you say "tidak setuju"?', ['I disagree.', 'I agree.', 'You are right.', 'Sure.'], 'I disagree.'),
                    $this->mc('What means "Kamu benar"?', ['You are right.', 'You are wrong.', 'I disagree.', 'No way.'], 'You are right.'),
                    $this->fib('I ___ with you. (setuju)', 'agree'),
                    $this->fib('I do not ___. (setuju)', 'agree'),
                ]),
                // 8
                $this->lesson('Giving Advice', '<h3>Giving Advice</h3><p><b>You should...</b> (Kamu seharusnya...), <b>You could...</b> (Kamu bisa...), <b>If I were you...</b> (Kalau saya jadi kamu...).</p>', [
                    $this->mc('How do you give advice?', ['You should...', 'I am...', 'I have...', 'I go...'], 'You should...'),
                    $this->mc('What means "Kamu seharusnya"?', ['You should...', 'You could...', 'I am...', 'I have...'], 'You should...'),
                    $this->mc('What is a softer way to advise?', ['You could...', 'You must...', 'You will...', 'You should...'], 'You could...'),
                    $this->fib('You ___ see a doctor. (seharusnya)', 'should'),
                    $this->fib('You ___ try again. (bisa)', 'could'),
                ]),
                // 9
                $this->lesson('Making Decisions', '<h3>Making Decisions</h3><p><b>Let\'s</b> (ayo), <b>I decide</b> (saya memutuskan), <b>We agree</b> (kami sepakat).</p>', [
                    $this->mc('How do you suggest something?', ['Let\'s...', 'I decide...', 'We agree...', 'I think...'], 'Let\'s...'),
                    $this->mc('What means "mengambil keputusan"?', ['Make a decision', 'Agree', 'Suggest', 'Think'], 'Make a decision'),
                    $this->mc('What means "sepakat"?', ['Agree', 'Decide', 'Suggest', 'Think'], 'Agree'),
                    $this->fib('___\'s go now. (Ayo)', 'Let'),
                    $this->fib('I ___ to go home. (memutuskan)', 'decide'),
                ]),
                // 10 - Describing Problems: 5 materi + 5 review (Lesson 6-9)
                $this->lesson('Describing Problems', '<h3>Describing Problems</h3><p><b>Problem</b> (masalah), <b>Issue</b> (masalah), <b>Broken</b> (rusak), <b>Lost</b> (hilang).</p>', [
                    // Materi (5 Q)
                    $this->mc('What is another word for "problem"?', ['Issue', 'Solution', 'Answer', 'Help'], 'Issue'),
                    $this->mc('What means "rusak"?', ['Broken', 'Lost', 'Found', 'New'], 'Broken'),
                    $this->mc('What means "hilang"?', ['Lost', 'Broken', 'Found', 'New'], 'Lost'),
                    $this->fib('I have a ___. (masalah)', 'problem'),
                    $this->fib('My phone is ___. (rusak)', 'broken'),
                    // Review Lesson 6-9 (5 Q)
                    $this->mc('Which word means "karena"?', ['Because', 'So', 'Therefore', 'But'], 'Because'),                                   // L6 Explaining Ideas
                    $this->mc('Your friend says, "Pizza is the best." You think so too. You say...', ['I agree.', 'I disagree.', 'I am hungry.', 'No way.'], 'I agree.'), // L7 Agreeing & Disagreeing
                    $this->mc('Your friend has a headache. What advice is correct?', ['You should rest.', 'You are rest.', 'You resting.', 'You did rest.'], 'You should rest.'), // L8 Giving Advice
                    $this->fib('It is raining, ___ I will take an umbrella. (jadi)', 'so'),                                                     // L6 Explaining Ideas
                    $this->fib('___\'s go to the beach together. (Ayo)', 'Let'),                                                                // L9 Making Decisions
                ], 30),
                // 11
                $this->lesson('Solving Problems', '<h3>Solving Problems</h3><p><b>Fix</b> (memperbaiki), <b>Solve</b> (menyelesaikan), <b>Solution</b> (solusi).</p>', [
                    $this->mc('What means "memperbaiki"?', ['Fix', 'Solve', 'Break', 'Lose'], 'Fix'),
                    $this->mc('What means "menyelesaikan"?', ['Solve', 'Fix', 'Break', 'Lose'], 'Solve'),
                    $this->mc('What is the answer to a problem?', ['Solution', 'Problem', 'Issue', 'Question'], 'Solution'),
                    $this->fib('Can you ___ this? (memperbaiki)', 'fix'),
                    $this->fib('The ___ is to restart. (solusi)', 'solution'),
                ]),
                // 12
                $this->lesson('Expressing Feelings', '<h3>Expressing Feelings</h3><p><b>Happy</b> (senang), <b>Sad</b> (sedih), <b>Angry</b> (marah), <b>Worried</b> (khawatir), <b>Excited</b> (bersemangat).</p>', [
                    $this->mc('How do you say "senang"?', ['Happy', 'Sad', 'Angry', 'Worried'], 'Happy'),
                    $this->mc('How do you say "sedih"?', ['Sad', 'Happy', 'Angry', 'Excited'], 'Sad'),
                    $this->mc('How do you say "marah"?', ['Angry', 'Happy', 'Sad', 'Worried'], 'Angry'),
                    $this->fib('I am ___. I got good news. (senang)', 'happy'),
                    $this->fib('I am ___. I lost my keys. (khawatir)', 'worried'),
                ]),
                // 13
                $this->lesson('Long Conversations', '<h3>Long Conversations</h3><p><b>By the way</b> (omong-omong), <b>What about you?</b> (Bagaimana denganmu?), <b>Really?</b> (Benarkah?), <b>That sounds interesting</b> (Kedengarannya menarik).</p>', [
                    $this->mc('You want to ask the other person the same question. You say...', ['What about you?', 'Who are you?', 'Go away.', 'I don\'t know.'], 'What about you?'),
                    $this->mc('You are surprised by the news. You say...', ['Really?', 'Goodbye.', 'Thank you.', 'Sorry.'], 'Really?'),
                    $this->mc('What means "Omong-omong"?', ['By the way', 'Of course', 'At last', 'Not yet'], 'By the way'),
                    $this->fib('That sounds ___! (menarik)', 'interesting'),
                    $this->fib('I like tea. What ___ you? (bagaimana dengan)', 'about'),
                ]),
                // 14
                $this->lesson('Real-World Challenge', '<h3>Real-World Challenge</h3><p>Use everything you learned in this unit: tell a story, give an opinion, explain with <b>because</b> and <b>so</b>, give advice, and solve a problem.</p>', [
                    $this->mc('Your friend lost his wallet. What is good advice?', ['You should go to the police.', 'You should buy a car.', 'You should sleep.', 'You should dance.'], 'You should go to the police.'),
                    $this->mc('Your friend says, "My phone is broken." Which reply offers a solution?', ['Let\'s fix it together.', 'I am happy.', 'Once upon a time.', 'Tomorrow is Monday.'], 'Let\'s fix it together.'),
                    $this->mc('Which sentence gives an opinion with a reason?', ['I think it is a good idea because it is cheap.', 'I think because good idea cheap.', 'Good idea I am cheap.', 'It is cheap I think good because.'], 'I think it is a good idea because it is cheap.'),
                    $this->fib('I am sad ___ I lost my bag. (karena)', 'because'),
                    $this->fib('Yesterday, I ___ to the police station because I was lost. (pergi)', 'went'),
                ]),
                // 15 - FINAL REVIEW: 10 soal (seluruh unit)
                $this->lesson('Final Review', '<h3>Unit 10 Final Review</h3><p>Reviewing all topics from Unit 10: Real-World Communication.</p>', [
                    $this->mc('How do you start a fairy tale?', ['Once upon a time', 'The end', 'Finally', 'Suddenly'], 'Once upon a time'),                                  // L1
                    $this->mc('I have ___ to Bali. It was beautiful.', ['been', 'went', 'gone', 'go'], 'been'),                                                          // L2
                    $this->mc('Which sentence is about the future?', ['I will travel tomorrow.', 'I traveled yesterday.', 'I travel last week.', 'I was travel.'], 'I will travel tomorrow.'), // L4
                    $this->mc('Which phrase gives an opinion?', ['In my opinion...', 'Once upon a time...', 'Last week...', 'Next year...'], 'In my opinion...'),         // L5
                    $this->mc('We stayed home ___ it was raining.', ['because', 'so', 'but', 'and'], 'because'),                                                         // L6
                    $this->mc('Your friend is right. You say...', ['You are right.', 'I disagree.', 'No way.', 'Wrong.'], 'You are right.'),                             // L7
                    $this->mc('The answer to a problem is a ___.', ['solution', 'issue', 'question', 'feeling'], 'solution'),                                            // L11
                    $this->mc('You got a surprise gift. You feel...', ['excited', 'angry', 'sad', 'broken'], 'excited'),                                                 // L12
                    $this->fib('You ___ drink more water. (sebaiknya)', 'should'),                                                                                       // L8
                    $this->fib('My laptop is ___. I cannot use it. (rusak)', 'broken'),                                                                                  // L10
                ], 30),
            ],
        ];
    }
}
