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

            if (!$course) {
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
                // 5 - REVIEW (10 Q)
                $this->lesson('Review: Basics & Small Talk', '<h3>Review 1</h3><p>Reviewing Starting Conversations, Questions, Answers, and Small Talk.</p>', [
                    $this->mc('How do you greet someone?', ['Hello', 'Goodbye', 'See you', 'Good night'], 'Hello'),
                    $this->mc('Which word asks about a time?', ['When', 'Who', 'Where', 'What'], 'When'),
                    $this->mc('How do you reply to "How are you?"', ['I am fine, thanks.', 'Yes, I do.', 'It is red.', 'Bye.'], 'I am fine, thanks.'),
                    $this->mc('Which is a good small talk topic?', ['Weather', 'Salary', 'Password', 'Age'], 'Weather'),
                    $this->mc('Nice to ___ you.', ['meet', 'see', 'look', 'watch'], 'meet'),
                    $this->mc('Do you like pizza? -> Yes, I ___.', ['do', 'am', 'is', 'have'], 'do'),
                    $this->mc('How was your ___?', ['weekend', 'day', 'night', 'morning'], 'weekend'),
                    $this->fib('___ is your name?', 'What'),
                    $this->fib('Excuse ___, where is the station?', 'me'),
                    $this->fib('It is a beautiful ___ today.', 'day'),
                ], 30),
                // 6
                $this->lesson('Introducing Someone', '<h3>Introducing Someone</h3><p>Phrases: <b>This is my friend, John</b>, <b>Meet my sister, Anna</b>.</p>', [
                    $this->mc('How do you introduce a friend?', ['This is my friend, John.', 'I am John.', 'You are John.', 'He is John.'], 'This is my friend, John.'),
                    $this->mc('What is the reply to an introduction?', ['Nice to meet you too.', 'Goodbye.', 'Good night.', 'Sorry.'], 'Nice to meet you too.'),
                    $this->mc('How do you introduce a family member?', ['Meet my sister, Anna.', 'This is a book.', 'I am Anna.', 'She is a girl.'], 'Meet my sister, Anna.'),
                    $this->fib('This ___ my brother, Budi.', 'is'),
                    $this->fib('Nice to ___ you, Anna.', 'meet'),
                ]),
                // 7
                $this->lesson('Talking About Yourself', '<h3>Talking About Yourself</h3><p><b>My name is...</b>, <b>I live in...</b>, <b>I work as...</b>, <b>I like...</b>.</p>', [
                    $this->mc('How do you tell your job?', ['I work as a teacher.', 'I am 20 years old.', 'I live in Jakarta.', 'I like pizza.'], 'I work as a teacher.'),
                    $this->mc('How do you tell where you live?', ['I live in Bali.', 'I like apples.', 'My name is Rina.', 'I am fine.'], 'I live in Bali.'),
                    $this->mc('How do you tell your hobby?', ['I like reading.', 'I am a doctor.', 'I am from Japan.', 'Good morning.'], 'I like reading.'),
                    $this->fib('My ___ is Siska.', 'name'),
                    $this->fib('I ___ in Bandung.', 'live'),
                ]),
                // 8
                $this->lesson('Talking About Others', '<h3>Talking About Others</h3><p><b>His name is...</b>, <b>She lives in...</b>, <b>He works as...</b>.</p>', [
                    $this->mc('How do you tell a man\'s name?', ['His name is Tom.', 'Her name is Tom.', 'My name is Tom.', 'Your name is Tom.'], 'His name is Tom.'),
                    $this->mc('How do you tell a woman\'s job?', ['She is a nurse.', 'He is a nurse.', 'I am a nurse.', 'You are a nurse.'], 'She is a nurse.'),
                    $this->mc('Where does she live? -> She ___ in Paris.', ['lives', 'live', 'living', 'lived'], 'lives'),
                    $this->fib('___ name is David.', 'His'),
                    $this->fib('___ is a teacher. (perempuan)', 'She'),
                ]),
                // 9
                $this->lesson('Asking for Help', '<h3>Asking for Help</h3><p><b>Can you help me?</b>, <b>Could you please...?</b>, <b>I need assistance</b>.</p>', [
                    $this->mc('How do you ask for help politely?', ['Could you please help me?', 'Help me now!', 'Do it!', 'You must help.'], 'Could you please help me?'),
                    $this->mc('What means "Bisa bantu saya?"?', ['Can you help me?', 'Where is it?', 'What is this?', 'Who are you?'], 'Can you help me?'),
                    $this->mc('A polite request starts with...', ['Could you...', 'Do...', 'Give...', 'Make...'], 'Could you...'),
                    $this->fib('Can you ___ me, please?', 'help'),
                    $this->fib('___ you please open the door?', 'Could'),
                ]),
                // 10 - REVIEW (10 Q)
                $this->lesson('Review: Introductions & Help', '<h3>Review 2</h3><p>Reviewing Introductions, Talking about yourself/others, and Asking for Help.</p>', [
                    $this->mc('How do you introduce a friend?', ['This is my friend, Tom.', 'I am Tom.', 'You are Tom.', 'He is Tom.'], 'This is my friend, Tom.'),
                    $this->mc('How do you tell where you live?', ['I live in Jakarta.', 'I like apples.', 'My name is Budi.', 'I am fine.'], 'I live in Jakarta.'),
                    $this->mc('How do you tell a woman\'s job?', ['She is a doctor.', 'He is a doctor.', 'I am a doctor.', 'You are a doctor.'], 'She is a doctor.'),
                    $this->mc('How do you ask for help?', ['Can you help me?', 'Go away!', 'Where is it?', 'Who are you?'], 'Can you help me?'),
                    $this->mc('His name is John. ___ is a student.', ['He', 'She', 'It', 'They'], 'He'),
                    $this->mc('Nice to meet you. -> ___ to meet you too.', ['Nice', 'Good', 'Fine', 'Great'], 'Nice'),
                    $this->mc('I ___ in Bandung.', ['live', 'lives', 'living', 'lived'], 'live'),
                    $this->fib('This ___ my sister, Rina.', 'is'),
                    $this->fib('Could you ___ me?', 'help'),
                    $this->fib('She ___ in London. (tinggal)', 'lives'),
                ], 30),
                // 11
                $this->lesson('Making Requests', '<h3>Making Requests</h3><p><b>Can I have...?</b>, <b>Would you mind...?</b>, <b>Please...</b>.</p>', [
                    $this->mc('How do you ask for water politely?', ['Can I have some water?', 'Give me water!', 'Water now!', 'I want water!'], 'Can I have some water?'),
                    $this->mc('What follows "Would you mind..."?', ['-ing verb', 'to verb', 'noun', 'adjective'], '-ing verb'),
                    $this->mc('How do you ask someone to wait?', ['Please wait a moment.', 'Wait!', 'Stop!', 'Go!'], 'Please wait a moment.'),
                    $this->fib('Can I ___ the menu, please?', 'have'),
                    $this->fib('Please ___ a moment.', 'wait'),
                ]),
                // 12
                $this->lesson('Accepting & Refusing', '<h3>Accepting & Refusing</h3><p><b>Yes, I\'d love to</b> (Menerima), <b>I\'m sorry, I can\'t</b> (Menolak), <b>Sure!</b>, <b>Maybe next time</b>.</p>', [
                    $this->mc('How do you accept an invitation?', ['Yes, I\'d love to!', 'No!', 'I hate it.', 'Go away.'], 'Yes, I\'d love to!'),
                    $this->mc('How do you refuse politely?', ['I\'m sorry, I can\'t.', 'No, I won\'t.', 'I refuse.', 'Bad idea.'], 'I\'m sorry, I can\'t.'),
                    $this->mc('What means "Tentu saja!"?', ['Sure!', 'No!', 'Maybe.', 'Never.'], 'Sure!'),
                    $this->fib('Would you like to come? Yes, I ___ to!', 'd'),
                    $this->fib('I\'m ___, I can\'t come today.', 'sorry'),
                ]),
                // 13
                $this->lesson('Agreeing & Disagreeing', '<h3>Agreeing & Disagreeing</h3><p><b>I agree</b> (setuju), <b>You\'re right</b> (kamu benar), <b>I don\'t agree</b> (tidak setuju), <b>I think differently</b>.</p>', [
                    $this->mc('How do you say you agree?', ['I agree.', 'I disagree.', 'No way.', 'Wrong.'], 'I agree.'),
                    $this->mc('How do you say you disagree politely?', ['I don\'t agree.', 'You are wrong!', 'Shut up!', 'No!'], 'I don\'t agree.'),
                    $this->mc('What means "Kamu benar"?', ['You\'re right.', 'You\'re wrong.', 'You\'re bad.', 'You\'re old.'], 'You\'re right.'),
                    $this->fib('I ___ with you.', 'agree'),
                    $this->fib('You are ___. (benar)', 'right'),
                ]),
                // 14
                $this->lesson('Making Suggestions', '<h3>Making Suggestions</h3><p><b>Let\'s...</b> (Ayo...), <b>How about...?</b> (Bagaimana kalau...?), <b>Why don\'t we...?</b> (Mengapa kita tidak...?).</p>', [
                    $this->mc('How do you suggest going to the park?', ['Let\'s go to the park!', 'Go to park!', 'Park is good.', 'I go to park.'], 'Let\'s go to the park!'),
                    $this->mc('What means "Bagaimana kalau...?"?', ['How about...?', 'What is...?', 'Where is...?', 'Who is...?'], 'How about...?'),
                    $this->mc('Why ___ we eat pizza?', ['don\'t', 'not', 'do', 'are'], 'don\'t'),
                    $this->fib('___\'s eat lunch!', 'Let'),
                    $this->fib('How ___ watching a movie?', 'about'),
                ]),
                // 15 - FINAL REVIEW (10 Q)
                $this->lesson('Unit Review', '<h3>Unit 7 Final Review</h3><p>Reviewing all topics from Unit 7: Conversations.</p>', [
                    $this->mc('How do you greet someone?', ['Hello', 'Goodbye', 'See you', 'Good night'], 'Hello'),
                    $this->mc('Which question word asks about a place?', ['Where', 'Who', 'When', 'Why'], 'Where'),
                    $this->mc('How do you introduce a friend?', ['This is my friend, Anna.', 'I am Anna.', 'You are Anna.', 'He is Anna.'], 'This is my friend, Anna.'),
                    $this->mc('How do you ask for help?', ['Can you help me?', 'Go away!', 'Where is it?', 'Who are you?'], 'Can you help me?'),
                    $this->mc('How do you accept an invitation?', ['Yes, I\'d love to!', 'No!', 'I hate it.', 'Go away.'], 'Yes, I\'d love to!'),
                    $this->mc('How do you suggest something?', ['Let\'s go!', 'Go!', 'Stop!', 'No!'], 'Let\'s go!'),
                    $this->mc('What means "Kamu benar"?', ['You\'re right.', 'You\'re wrong.', 'You\'re bad.', 'You\'re old.'], 'You\'re right.'),
                    $this->fib('Nice to ___ you.', 'meet'),
                    $this->fib('I ___ with you. (setuju)', 'agree'),
                    $this->fib('Can I ___ the menu, please?', 'have'),
                ], 30),
            ]
        ];
    }
}
