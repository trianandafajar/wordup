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
            if (!$course) { return; }

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

    private function getUnitData(): array
    {
        return [
            'title' => 'Unit 10: Real-World Communication',
            'lessons' => [
                // 1
                $this->lesson('Telling a Story', '<h3>Telling a Story</h3><p><b>Once upon a time</b>, <b>Suddenly</b>, <b>Finally</b>, <b>The end</b>.</p>', [
                    $this->mc('How do you start a story?', ['Once upon a time', 'The end', 'Suddenly', 'Finally'], 'Once upon a time'),
                    $this->mc('What means "tiba-tiba"?', ['Suddenly', 'Finally', 'Once', 'The end'], 'Suddenly'),
                    $this->mc('What is the last part of a story?', ['The end', 'Once upon a time', 'Suddenly', 'Finally'], 'The end'),
                    $this->fib('___ upon a time, there was a prince.', 'Once'),
                    $this->fib('___! I found my keys.', 'Suddenly'),
                ]),
                // 2
                $this->lesson('Talking About Experiences', '<h3>Talking About Experiences</h3><p><b>I have been to...</b>, <b>I have visited...</b>, <b>I experienced...</b>.</p>', [
                    $this->mc('How do you say "I have been to Bali"?', ['I have been to Bali.', 'I went Bali.', 'I am Bali.', 'I was Bali.'], 'I have been to Bali.'),
                    $this->mc('What verb follows "have been"?', ['To + place', 'Verb-ing', 'Noun', 'Adjective'], 'To + place'),
                    $this->mc('Which word means "mengunjungi"?', ['Visited', 'Gone', 'Been', 'Arrived'], 'Visited'),
                    $this->fib('I have ___ to Japan.', 'been'),
                    $this->fib('I have ___ Paris.', 'visited'),
                ]),
                // 3
                $this->lesson('Talking About the Past', '<h3>Talking About the Past</h3><p><b>Yesterday</b>, <b>Last week</b>, <b>Went</b>, <b>Did</b>.</p>', [
                    $this->mc('What is the past of "go"?', ['Went', 'Gone', 'Going', 'Go'], 'Went'),
                    $this->mc('What is the past of "do"?', ['Did', 'Does', 'Done', 'Doing'], 'Did'),
                    $this->mc('What means "kemarin"?', ['Yesterday', 'Tomorrow', 'Today', 'Next week'], 'Yesterday'),
                    $this->fib('I ___ to the market yesterday.', 'went'),
                    $this->fib('What ___ you eat?', 'did'),
                ]),
                // 4
                $this->lesson('Talking About the Future', '<h3>Talking About the Future</h3><p><b>Will</b>, <b>Going to</b>, <b>Tomorrow</b>, <b>Next week</b>.</p>', [
                    $this->mc('What is used for future plans?', ['Going to', 'Did', 'Went', 'Was'], 'Going to'),
                    $this->mc('What is "besok" in English?', ['Tomorrow', 'Yesterday', 'Today', 'Next week'], 'Tomorrow'),
                    $this->mc('How do you say "I will go"?', ['I will go.', 'I going go.', 'I go.', 'I did go.'], 'I will go.'),
                    $this->fib('I ___ visit Bali next month.', 'will'),
                    $this->fib('I am ___ to travel.', 'going'),
                ]),
                // 5 - REVIEW (10 Q)
                $this->lesson('Review: Story & Past/Future', '<h3>Review 1</h3><p>Reviewing Stories, Past, and Future.</p>', [
                    $this->mc('How do you start a story?', ['Once upon a time', 'Suddenly', 'Finally', 'The end'], 'Once upon a time'),
                    $this->mc('What is the past of "go"?', ['Went', 'Gone', 'Going', 'Go'], 'Went'),
                    $this->mc('What is "besok"?', ['Tomorrow', 'Yesterday', 'Today', 'Next week'], 'Tomorrow'),
                    $this->mc('I have ___ to Japan.', ['been', 'went', 'gone', 'go'], 'been'),
                    $this->mc('What means "tiba-tiba"?', ['Suddenly', 'Finally', 'Once', 'The end'], 'Suddenly'),
                    $this->mc('What follows "have been"?', ['To + place', 'Verb-ing', 'Noun', 'Adjective'], 'To + place'),
                    $this->mc('I am ___ to travel.', ['going', 'will', 'did', 'was'], 'going'),
                    $this->fib('I ___ to the market yesterday.', 'went'),
                    $this->fib('I ___ visit Bali.', 'will'),
                    $this->fib('___! I found my keys.', 'Suddenly'),
                ], 30),
                // 6
                $this->lesson('Giving Opinions', '<h3>Giving Opinions</h3><p><b>I think...</b>, <b>In my opinion...</b>, <b>I believe...</b>.</p>', [
                    $this->mc('How do you start an opinion?', ['I think...', 'I am...', 'I have...', 'I go...'], 'I think...'),
                    $this->mc('What is a synonym for "opinion"?', ['View', 'Fact', 'Answer', 'Question'], 'View'),
                    $this->mc('How do you say "menurut saya"?', ['In my opinion', 'I am', 'I go', 'I have'], 'In my opinion'),
                    $this->fib('I ___ this is correct.', 'think'),
                    $this->fib('___ my opinion, it is good.', 'In'),
                ]),
                // 7
                $this->lesson('Explaining Ideas', '<h3>Explaining Ideas</h3><p><b>Because</b> (karena), <b>So</b> (jadi), <b>Therefore</b> (oleh karena itu).</p>', [
                    $this->mc('What word shows reason?', ['Because', 'So', 'Therefore', 'And'], 'Because'),
                    $this->mc('What means "jadi"?', ['So', 'Because', 'Therefore', 'But'], 'So'),
                    $this->mc('What is "oleh karena itu"?', ['Therefore', 'Because', 'So', 'But'], 'Therefore'),
                    $this->fib('I am tired, ___ I will rest.', 'so'),
                    $this->fib('___ it is raining.', 'Because'),
                ]),
                // 8
                $this->lesson('Agreeing & Disagreeing', '<h3>Agreeing & Disagreeing</h3><p><b>I agree</b>, <b>I disagree</b>, <b>You are right</b>, <b>I think differently</b>.</p>', [
                    $this->mc('How do you say "setuju"?', ['I agree.', 'I disagree.', 'No way.', 'Wrong.'], 'I agree.'),
                    $this->mc('How do you say "tidak setuju"?', ['I disagree.', 'I agree.', 'You are right.', 'Sure.'], 'I disagree.'),
                    $this->mc('What means "Kamu benar"?', ['You are right.', 'You are wrong.', 'I disagree.', 'No way.'], 'You are right.'),
                    $this->fib('I ___ with you.', 'agree'),
                    $this->fib('I do not ___.', 'agree'),
                ]),
                // 9
                $this->lesson('Giving Advice', '<h3>Giving Advice</h3><p><b>You should...</b>, <b>You could...</b>, <b>If I were you...</b>.</p>', [
                    $this->mc('How do you give advice?', ['You should...', 'I am...', 'I have...', 'I go...'], 'You should...'),
                    $this->mc('What means "Kamu seharusnya"?', ['You should...', 'You could...', 'I am...', 'I have...'], 'You should...'),
                    $this->mc('What is a softer way to advise?', ['You could...', 'You must...', 'You will...', 'You should...'], 'You could...'),
                    $this->fib('You ___ see a doctor.', 'should'),
                    $this->fib('You ___ try again.', 'could'),
                ]),
                // 10 - REVIEW (10 Q)
                $this->lesson('Review: Opinions & Advice', '<h3>Review 2</h3><p>Reviewing Opinions, Explaining, and Advice.</p>', [
                    $this->mc('How do you start an opinion?', ['I think...', 'I am...', 'I have...', 'I go...'], 'I think...'),
                    $this->mc('What shows reason?', ['Because', 'So', 'Therefore', 'But'], 'Because'),
                    $this->mc('How do you say "setuju"?', ['I agree.', 'I disagree.', 'You are right.', 'Sure.'], 'I agree.'),
                    $this->mc('How do you give advice?', ['You should...', 'You could...', 'I should...', 'I could...'], 'You should...'),
                    $this->mc('What means "jadi"?', ['So', 'Because', 'Therefore', 'But'], 'So'),
                    $this->mc('What is "oleh karena itu"?', ['Therefore', 'Because', 'So', 'But'], 'Therefore'),
                    $this->mc('What is a softer way to advise?', ['You could...', 'You must...', 'You should...', 'You will...'], 'You could...'),
                    $this->fib('I ___ this is good.', 'think'),
                    $this->fib('___ it is raining.', 'Because'),
                    $this->fib('I do not ___.', 'agree'),
                ], 30),
                // 11
                $this->lesson('Making Decisions', '<h3>Making Decisions</h3><p><b>Let\'s</b> (ayo), <b>I decide</b> (saya memutuskan), <b>We agree</b> (kami sepakat).</p>', [
                    $this->mc('How do you suggest something?', ['Let\'s...', 'I decide...', 'We agree...', 'I think...'], 'Let\'s...'),
                    $this->mc('What means "mengambil keputusan"?', ['Make a decision', 'Agree', 'Suggest', 'Think'], 'Make a decision'),
                    $this->mc('What means "sepakat"?', ['Agree', 'Decide', 'Suggest', 'Think'], 'Agree'),
                    $this->fib('___ we go now.', 'Let'),
                    $this->fib('I ___ to go home.', 'decide'),
                ]),
                // 12
                $this->lesson('Describing Problems', '<h3>Describing Problems</h3><p><b>Problem</b> (masalah), <b>Issue</b> (masalah), <b>Broken</b> (rusak), <b>Lost</b> (hilang).</p>', [
                    $this->mc('What is another word for "masalah"?', ['Issue', 'Solution', 'Answer', 'Help'], 'Issue'),
                    $this->mc('What means "rusak"?', ['Broken', 'Lost', 'Found', 'New'], 'Broken'),
                    $this->mc('What means "hilang"?', ['Lost', 'Broken', 'Found', 'New'], 'Lost'),
                    $this->fib('I have a ___.', 'problem'),
                    $this->fib('My phone is ___.', 'broken'),
                ]),
                // 13
                $this->lesson('Solving Problems', '<h3>Solving Problems</h3><p><b>Fix</b> (memperbaiki), <b>Solve</b> (menyelesaikan), <b>Solution</b> (solusi).</p>', [
                    $this->mc('What means "memperbaiki"?', ['Fix', 'Solve', 'Break', 'Lose'], 'Fix'),
                    $this->mc('What means "menyelesaikan"?', ['Solve', 'Fix', 'Break', 'Lose'], 'Solve'),
                    $this->mc('What is the answer to a problem?', ['Solution', 'Problem', 'Issue', 'Question'], 'Solution'),
                    $this->fib('Can you ___ this?', 'fix'),
                    $this->fib('The ___ is to restart.', 'solution'),
                ]),
                // 14
                $this->lesson('Expressing Feelings', '<h3>Expressing Feelings</h3><p><b>Happy</b>, <b>Sad</b>, <b>Angry</b>, <b>Worried</b>, <b>Excited</b>.</p>', [
                    $this->mc('How do you say "senang"?', ['Happy', 'Sad', 'Angry', 'Worried'], 'Happy'),
                    $this->mc('How do you say "sedih"?', ['Sad', 'Happy', 'Angry', 'Excited'], 'Sad'),
                    $this->mc('How do you say "marah"?', ['Angry', 'Happy', 'Sad', 'Worried'], 'Angry'),
                    $this->fib('I am ___. I got good news.', 'happy'),
                    $this->fib('I am ___. I lost my keys.', 'worried'),
                ]),
                // 15 - FINAL REVIEW (10 Q)
                $this->lesson('Final Review', '<h3>Unit 10 Final Review</h3><p>Reviewing all topics from Unit 10: Real-World Communication.</p>', [
                    $this->mc('How do you start a story?', ['Once upon a time', 'Suddenly', 'Finally', 'The end'], 'Once upon a time'),
                    $this->mc('What is the past of "go"?', ['Went', 'Gone', 'Going', 'Do'], 'Went'),
                    $this->mc('What is "besok"?', ['Tomorrow', 'Yesterday', 'Today', 'Next week'], 'Tomorrow'),
                    $this->mc('How do you give an opinion?', ['I think...', 'I am...', 'I have...', 'I go...'], 'I think...'),
                    $this->mc('What shows reason?', ['Because', 'So', 'Therefore', 'But'], 'Because'),
                    $this->mc('How do you say "setuju"?', ['I agree.', 'I disagree.', 'You are wrong.', 'No way.'], 'I agree.'),
                    $this->mc('How do you say "hilang"?', ['Lost', 'Broken', 'Found', 'New'], 'Lost'),
                    $this->fib('I have ___ to Japan.', 'been'),
                    $this->fib('I ___ with you.', 'agree'),
                    $this->fib('You ___ see a doctor.', 'should'),
                ], 30),
            ]
        ];
    }
}
