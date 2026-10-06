<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnglishUnit7Seeder extends Seeder
{
    use CanSeedEnglishContent;

    public function run(): void
    {
        DB::transaction(function () {
            $course = Course::where('title', 'Complete English Mastery')->first();

            if (! $course) {
                return;
            }

            $unitOrder = 7;
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
            'title' => 'Unit 7: Conversations',
            'lessons' => [
                // 1
                $this->lesson('Starting a Conversation', '<h3>Starting a Conversation</h3><p><b>Hello / Hi</b>, <b>How are you?</b>, <b>Nice to meet you</b>, <b>Excuse me</b>.</p>', [
                    $this->mc('How do you greet someone casually?', ['Hello', 'Goodbye', 'See you', 'Good night'], 'Hello'),
                    $this->mc('What do you say when meeting someone new?', ['Nice to meet you', 'Goodbye', 'See you later', 'Good night'], 'Nice to meet you'),
                    $this->mc('How do you get someone\'s attention?', ['Excuse me', 'Sorry', 'Hey you', 'Please'], 'Excuse me'),
                    $this->fib('Hi, how ___ you today?', 'are'),
                    $this->fib('Nice to ___ you, Budi.', 'meet'),
                ]),
                // 2
                $this->lesson('Asking Questions', '<h3>Asking Questions</h3><p>Question words: <b>Who</b> (siapa), <b>What</b> (apa), <b>Where</b> (di mana), <b>When</b> (kapan), <b>Why</b> (mengapa), <b>How</b> (bagaimana).</p>', [
                    $this->mc('Which question word asks about a person?', ['Who', 'What', 'Where', 'When'], 'Who'),
                    $this->mc('Which question word asks about a place?', ['Where', 'Who', 'When', 'Why'], 'Where'),
                    $this->mc('Which question word asks about a reason?', ['Why', 'What', 'Where', 'Who'], 'Why'),
                    $this->fib('___ are you going? (tempat)', 'Where'),
                    $this->fib('___ is your name?', 'What'),
                ]),
                // 3
                $this->lesson('Answering Questions', '<h3>Answering Questions</h3><p>Responding: <b>Yes, I do / No, I don\'t</b>, <b>I am fine, thanks</b>, <b>It is over there</b>.</p>', [
                    $this->mc('How do you reply to "How are you?"', ['I am fine, thanks.', 'Yes, I do.', 'It is red.', 'Goodbye.'], 'I am fine, thanks.'),
                    $this->mc('How do you answer "Do you like coffee?"', ['Yes, I do.', 'I am 20.', 'It is 5 PM.', 'In the kitchen.'], 'Yes, I do.'),
                    $this->mc('How do you answer "Where is the bank?"', ['It is over there.', 'Yes, I do.', 'I am fine.', 'Good morning.'], 'It is over there.'),
                    $this->fib('Do you speak English? Yes, I ___.', 'do'),
                    $this->fib('How are you? I ___ fine.', 'am'),
                ]),
                // 4
                $this->lesson('Small Talk', '<h3>Small Talk</h3><p>Topics: <b>Weather</b> (cuaca), <b>Weekend</b> (akhir pekan), <b>Hobby</b> (hobi).</p>', [
                    $this->mc('What is a good small talk topic?', ['Weather', 'Private salary', 'Password', 'Secret'], 'Weather'),
                    $this->mc('What do you say about the weather today?', ['It is a nice day!', 'I am 20 years old.', 'My name is Rina.', 'I like coffee.'], 'It is a nice day!'),
                    $this->mc('How do you ask about the weekend?', ['How was your weekend?', 'What is your name?', 'Where are you?', 'Who are you?'], 'How was your weekend?'),
                    $this->fib('Nice ___ today, isn\'t it?', 'day'),
                    $this->fib('Did you have a good ___?', 'weekend'),
                ]),
                // 5 - Introducing Someone: 5 materi + 5 review (Lesson 1-4)
                $this->lesson('Introducing Someone', '<h3>Introducing Someone</h3><p>Phrases: <b>This is my friend, John</b>, <b>Meet my sister, Anna</b>.</p>', [
                    // Materi (5 Q)
                    $this->mc('How do you introduce a friend?', ['This is my friend, John.', 'I am John.', 'You are John.', 'He is John.'], 'This is my friend, John.'),
                    $this->mc('What is the reply to an introduction?', ['Nice to meet you too.', 'Goodbye.', 'Good night.', 'Sorry.'], 'Nice to meet you too.'),
                    $this->mc('How do you introduce a family member?', ['Meet my sister, Anna.', 'This is a book.', 'I am Anna.', 'She is a girl.'], 'Meet my sister, Anna.'),
                    $this->fib('This ___ my brother, Budi.', 'is'),
                    $this->fib('Nice to ___ you, Anna.', 'meet'),
                    // Review Lesson 1-4 (5 Q)
                    $this->mc('How do you greet someone?', ['Hello', 'Goodbye', 'See you', 'Good night'], 'Hello'),                                  // L1 Starting a Conversation
                    $this->mc('Which word asks about a time?', ['When', 'Who', 'Where', 'What'], 'When'),                                           // L2 Asking Questions
                    $this->mc('How do you reply to "How are you?"', ['I am fine, thanks.', 'Yes, I do.', 'It is red.', 'Bye.'], 'I am fine, thanks.'), // L3 Answering Questions
                    $this->fib('Excuse ___, where is the station? (permisi)', 'me'),                                                                // L1 Starting a Conversation
                    $this->fib('Did you have a good ___? (akhir pekan)', 'weekend'),                                                                // L4 Small Talk
                ], 30),
                // 6
                $this->lesson('Talking About Yourself', '<h3>Talking About Yourself</h3><p><b>My name is...</b>, <b>I live in...</b>, <b>I work as...</b>, <b>I like...</b>.</p>', [
                    $this->mc('How do you tell your job?', ['I work as a teacher.', 'I am 20 years old.', 'I live in Jakarta.', 'I like pizza.'], 'I work as a teacher.'),
                    $this->mc('How do you tell where you live?', ['I live in Bali.', 'I like apples.', 'My name is Rina.', 'I am fine.'], 'I live in Bali.'),
                    $this->mc('How do you tell your hobby?', ['I like reading.', 'I am a doctor.', 'I am from Japan.', 'Good morning.'], 'I like reading.'),
                    $this->fib('My ___ is Siska.', 'name'),
                    $this->fib('I ___ in Bandung.', 'live'),
                ]),
                // 7
                $this->lesson('Talking About Others', '<h3>Talking About Others</h3><p><b>His name is...</b>, <b>She lives in...</b>, <b>He works as...</b>.</p>', [
                    $this->mc('How do you tell a man\'s name?', ['His name is Tom.', 'Her name is Tom.', 'My name is Tom.', 'Your name is Tom.'], 'His name is Tom.'),
                    $this->mc('How do you tell a woman\'s job?', ['She is a nurse.', 'He is a nurse.', 'I am a nurse.', 'You are a nurse.'], 'She is a nurse.'),
                    $this->mc('Where does she live? She ___ in Paris.', ['lives', 'live', 'living', 'lived'], 'lives'),
                    $this->fib('___ name is David. (laki-laki)', 'His'),
                    $this->fib('___ is a teacher. (perempuan)', 'She'),
                ]),
                // 8
                $this->lesson('Asking for Help', '<h3>Asking for Help</h3><p><b>Can you help me?</b>, <b>Could you please...?</b>, <b>I need assistance</b>.</p>', [
                    $this->mc('How do you ask for help politely?', ['Could you please help me?', 'Help me now!', 'Do it!', 'You must help.'], 'Could you please help me?'),
                    $this->mc('What does "Bisa bantu saya?" mean in English?', ['Can you help me?', 'Where is it?', 'What is this?', 'Who are you?'], 'Can you help me?'),
                    $this->mc('A polite request starts with...', ['Could you...', 'Do...', 'Give...', 'Make...'], 'Could you...'),
                    $this->fib('Can you ___ me, please?', 'help'),
                    $this->fib('___ you please open the door?', 'Could'),
                ]),
                // 9
                $this->lesson('Making Requests', '<h3>Making Requests</h3><p><b>Can I have...?</b>, <b>Would you mind...?</b>, <b>Please...</b>.</p>', [
                    $this->mc('How do you ask for water politely?', ['Can I have some water?', 'Give me water!', 'Water now!', 'I want water!'], 'Can I have some water?'),
                    $this->mc('What follows "Would you mind..."?', ['-ing verb', 'to verb', 'noun', 'adjective'], '-ing verb'),
                    $this->mc('How do you ask someone to wait?', ['Please wait a moment.', 'Wait!', 'Stop!', 'Go!'], 'Please wait a moment.'),
                    $this->fib('Can I ___ the menu, please?', 'have'),
                    $this->fib('Please ___ a moment.', 'wait'),
                ]),
                // 10 - Accepting & Refusing: 5 materi + 5 review (Lesson 6-9)
                $this->lesson('Accepting & Refusing', '<h3>Accepting & Refusing</h3><p><b>Yes, I\'d love to</b> (Menerima), <b>I\'m sorry, I can\'t</b> (Menolak), <b>Sure!</b>, <b>Maybe next time</b>.</p>', [
                    // Materi (5 Q)
                    $this->mc('How do you accept an invitation?', ['Yes, I\'d love to!', 'No!', 'I hate it.', 'Go away.'], 'Yes, I\'d love to!'),
                    $this->mc('How do you refuse politely?', ['I\'m sorry, I can\'t.', 'No, I won\'t.', 'I refuse.', 'Bad idea.'], 'I\'m sorry, I can\'t.'),
                    $this->mc('What does "Tentu saja!" mean in English?', ['Sure!', 'No!', 'Maybe.', 'Never.'], 'Sure!'),
                    $this->fib('Would you like to come? ___, I would love to! (Ya)', 'Yes'),
                    $this->fib('I\'m ___, I can\'t come today. (maaf)', 'sorry'),
                    // Review Lesson 6-9 (5 Q)
                    $this->mc('How do you tell where you live?', ['I live in Jakarta.', 'I like apples.', 'My name is Budi.', 'I am fine.'], 'I live in Jakarta.'),               // L6 Talking About Yourself
                    $this->mc('How do you tell a woman\'s job?', ['She is a doctor.', 'He is a doctor.', 'I am a doctor.', 'You are a doctor.'], 'She is a doctor.'), // L7 Talking About Others
                    $this->mc('How do you ask for help politely?', ['Could you please help me?', 'Go away!', 'Where is it?', 'Who are you?'], 'Could you please help me?'),     // L8 Asking for Help
                    $this->mc('How do you ask someone to wait?', ['Please wait a moment.', 'Wait!', 'Stop!', 'Go!'], 'Please wait a moment.'),                        // L9 Making Requests
                    $this->fib('She ___ in London. (tinggal)', 'lives'),                                                                                              // L7 Talking About Others
                ], 30),
                // 11
                $this->lesson('Agreeing & Disagreeing', '<h3>Agreeing & Disagreeing</h3><p><b>I agree</b> (setuju), <b>You\'re right</b> (kamu benar), <b>I don\'t agree</b> (tidak setuju), <b>I think differently</b>.</p>', [
                    $this->mc('How do you say you agree?', ['I agree.', 'I disagree.', 'No way.', 'Wrong.'], 'I agree.'),
                    $this->mc('How do you say you disagree politely?', ['I don\'t agree.', 'You are wrong!', 'Shut up!', 'No!'], 'I don\'t agree.'),
                    $this->mc('What does "Kamu benar" mean in English?', ['You\'re right.', 'You\'re wrong.', 'You\'re bad.', 'You\'re old.'], 'You\'re right.'),
                    $this->fib('I ___ with you. (setuju)', 'agree'),
                    $this->fib('You are ___. (benar)', 'right'),
                ]),
                // 12
                $this->lesson('Making Suggestions', '<h3>Making Suggestions</h3><p><b>Let\'s...</b> (Ayo...), <b>How about...?</b> (Bagaimana kalau...?), <b>Why don\'t we...?</b> (Mengapa kita tidak...?).</p>', [
                    $this->mc('How do you suggest going to the park?', ['Let\'s go to the park!', 'Go to park!', 'Park is good.', 'I go to park.'], 'Let\'s go to the park!'),
                    $this->mc('What does "Bagaimana kalau...?" mean in English?', ['How about...?', 'What is...?', 'Where is...?', 'Who is...?'], 'How about...?'),
                    $this->mc('Why ___ we eat pizza?', ['don\'t', 'not', 'do', 'are'], 'don\'t'),
                    $this->fib('___\'s eat lunch!', 'Let'),
                    $this->fib('How ___ watching a movie?', 'about'),
                ]),
                // 13
                $this->lesson('Giving Opinions', '<h3>Giving Opinions</h3><p><b>I think...</b> (Saya pikir...), <b>In my opinion...</b> (Menurut saya...), <b>I believe...</b> (Saya yakin...), <b>because</b> (karena).</p>', [
                    $this->mc('How do you give your opinion?', ['I think it is good.', 'I am 20.', 'It is red.', 'Yes, I do.'], 'I think it is good.'),
                    $this->mc('What does "Menurut saya" mean in English?', ['In my opinion', 'Excuse me', 'Nice to meet you', 'Let\'s go'], 'In my opinion'),
                    $this->mc('I like it ___ it is fun.', ['because', 'but', 'or', 'so'], 'because'),
                    $this->fib('I ___ this movie is great. (pikir)', 'think'),
                    $this->fib('In my ___, the food is delicious. (pendapat)', 'opinion'),
                ]),
                // 14
                $this->lesson('Real Conversation', '<h3>Real Conversation</h3><p><i>A: Hi, how are you?</i><br><i>B: I am fine, thanks. And you?</i><br><i>A: Great! Let\'s have lunch together.</i><br><i>B: Good idea!</i></p>', [
                    $this->mc('A: "How are you?" B: ...', ['I am fine, thanks. And you?', 'It is red.', 'Yes, I do.', 'At 5 PM.'], 'I am fine, thanks. And you?'),
                    $this->mc('A: "Would you like some tea?" B: ...', ['Yes, please!', 'I am 20.', 'In the park.', 'He is a doctor.'], 'Yes, please!'),
                    $this->mc('A: "Let\'s go to the park!" You agree. You say...', ['Good idea!', 'I am fine.', 'My name is Rina.', 'Goodbye.'], 'Good idea!'),
                    $this->fib('A: Can you help me? B: ___, of course!', 'Sure'),
                    $this->fib('A: Thank you! B: You are ___.', 'welcome'),
                ]),
                // 15 - UNIT REVIEW: 10 soal (seluruh unit)
                $this->lesson('Unit Review', '<h3>Unit 7 Review</h3><p>Reviewing all topics from Unit 7: Conversations.</p>', [
                    $this->mc('What do you say when meeting someone new?', ['Nice to meet you', 'Goodbye', 'See you later', 'Good night'], 'Nice to meet you'),     // L1
                    $this->mc('Which question word asks about a reason?', ['Why', 'What', 'Where', 'Who'], 'Why'),                                                       // L2
                    $this->mc('How do you answer "Where is the bank?"', ['It is over there.', 'Yes, I do.', 'I am fine.', 'Good morning.'], 'It is over there.'),         // L3
                    $this->mc('How do you introduce a friend?', ['This is my friend, Anna.', 'I am Anna.', 'You are Anna.', 'He is Anna.'], 'This is my friend, Anna.'), // L5
                    $this->mc('How do you refuse politely?', ['I\'m sorry, I can\'t.', 'No, I won\'t.', 'I refuse.', 'Bad idea.'], 'I\'m sorry, I can\'t.'),             // L10
                    $this->mc('What does "Kamu benar" mean in English?', ['You\'re right.', 'You\'re wrong.', 'You\'re bad.', 'You\'re old.'], 'You\'re right.'),        // L11
                    $this->mc('How do you suggest something?', ['Let\'s go!', 'Go!', 'Stop!', 'No!'], 'Let\'s go!'),                                                    // L12
                    $this->fib('Can you ___ me, please?', 'help'),                                                                                                      // L8
                    $this->fib('I ___ this is a good idea. (pikir)', 'think'),                                                                                          // L13
                    $this->fib('A: Thank you! B: You are ___.', 'welcome'),                                                                                             // L14
                ], 30),
            ],
        ];
    }
}
