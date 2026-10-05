<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnglishUnit2Seeder extends Seeder
{
    use CanSeedEnglishContent;

    public function run(): void
    {
        DB::transaction(function () {
            $course = Course::where('title', 'Complete English Mastery')->first();

            if (!$course) {
                return;
            }

            $unitOrder = 2;
            $unitData = $this->getUnitData();

            $unit = Unit::query()->updateOrCreate(
                ['course_id' => $course->id, 'order' => $unitOrder],
                ['title' => $unitData['title']]
            );

            $defaultImage = 'images/mascots/2.png';

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
            'title' => 'Unit 2: Everyday Life',
            'lessons' => [
                // 1
                $this->lesson('Family Members', '<h3>Family Members</h3><p>Extended family: <b>Aunt</b> (tante), <b>Uncle</b> (paman), <b>Cousin</b> (sepupu), <b>Grandparents</b> (kakek-nenek).</p>', [
                    $this->mc('Your mother\'s sister is your...', ['Aunt', 'Uncle', 'Cousin', 'Brother'], 'Aunt'),
                    $this->mc('Your father\'s brother is your...', ['Uncle', 'Aunt', 'Son', 'Daughter'], 'Uncle'),
                    $this->mc('The child of your aunt is your...', ['Cousin', 'Brother', 'Sister', 'Father'], 'Cousin'),
                    $this->fib('My parents\' parents are my ___. (kakek-nenek)', 'grandparents'),
                    $this->fib('I have one ___ (tante) and two uncles.', 'aunt'),
                ]),
                // 2
                $this->lesson('My Home', '<h3>My Home</h3><p>Parts of a home: <b>Roof</b> (atap), <b>Wall</b> (dinding), <b>Floor</b> (lantai), <b>Window</b> (jendela), <b>Door</b> (pintu).</p>', [
                    $this->mc('You enter a room through the...', ['Door', 'Window', 'Roof', 'Floor'], 'Door'),
                    $this->mc('You look outside through the...', ['Window', 'Wall', 'Floor', 'Ceiling'], 'Window'),
                    $this->mc('The bottom of the room is the...', ['Floor', 'Roof', 'Wall', 'Door'], 'Floor'),
                    $this->fib('The ___ is on top of the house. (atap)', 'roof'),
                    $this->fib('Open the ___, please. It is hot here. (jendela)', 'window'),
                ]),
                // 3
                $this->lesson('Rooms & Furniture', '<h3>Rooms & Furniture</h3><p><b>Kitchen</b> (dapur): stove, fridge. <b>Bedroom</b> (kamar tidur): bed, wardrobe. <b>Living room</b> (ruang tamu): sofa, TV.</p>', [
                    $this->mc('Where is the fridge?', ['In the kitchen', 'In the bathroom', 'In the garden', 'In the garage'], 'In the kitchen'),
                    $this->mc('You put your clothes in the...', ['Wardrobe', 'Fridge', 'Stove', 'Sofa'], 'Wardrobe'),
                    $this->mc('Where do you watch TV?', ['Living room', 'Kitchen', 'Bathroom', 'Attic'], 'Living room'),
                    $this->fib('I cook food on the ___. (kompor)', 'stove'),
                    $this->fib('My clothes are in the ___. (lemari pakaian)', 'wardrobe'),
                ]),
                // 4
                $this->lesson('Daily Activities', '<h3>Daily Activities</h3><p>Common verbs: <b>Read</b> (membaca), <b>Write</b> (menulis), <b>Listen</b> (mendengar), <b>Speak</b> (berbicara).</p>', [
                    $this->mc('I ___ a book every night.', ['read', 'eat', 'sleep', 'jump'], 'read'),
                    $this->mc('She ___ a letter to her friend.', ['writes', 'eats', 'drinks', 'walks'], 'writes'),
                    $this->mc('We ___ to music.', ['listen', 'speak', 'watch', 'run'], 'listen'),
                    $this->fib('Please ___ English in class. (berbicara)', 'speak'),
                    $this->fib('I ___ my homework in the afternoon. (menulis)', 'write'),
                ]),
                // 5 - Morning Routine: 5 materi + 5 review (Lesson 1-4)
                $this->lesson('Morning Routine', '<h3>Morning Routine</h3><p><b>Wake up</b> (bangun), <b>Brush teeth</b> (sikat gigi), <b>Take a shower</b> (mandi), <b>Have breakfast</b> (sarapan).</p>', [
                    // Materi (5 Q)
                    $this->mc('What do you do first in the morning?', ['Wake up', 'Go to bed', 'Have dinner', 'Watch TV'], 'Wake up'),
                    $this->mc('You use a toothbrush to...', ['Brush teeth', 'Wash face', 'Eat breakfast', 'Comb hair'], 'Brush teeth'),
                    $this->mc('Eating in the morning is called...', ['Breakfast', 'Lunch', 'Dinner', 'Snack'], 'Breakfast'),
                    $this->fib('I ___ a shower at 6 AM. (mandi)', 'take'),
                    $this->fib('I ___ my teeth after breakfast. (menyikat)', 'brush'),
                    // Review Lesson 1-4 (5 Q)
                    $this->mc('Your uncle\'s son is your...', ['Cousin', 'Brother', 'Father', 'Grandfather'], 'Cousin'),                       // L1 Family Members
                    $this->mc('Which part of the house keeps the rain away?', ['Roof', 'Floor', 'Door', 'Window'], 'Roof'),                   // L2 My Home
                    $this->mc('Where do you cook food?', ['Kitchen', 'Bedroom', 'Living room', 'Bathroom'], 'Kitchen'),                       // L3 Rooms & Furniture
                    $this->fib('We sit on the ___ in the living room. (sofa)', 'sofa'),                                                       // L3 Rooms & Furniture
                    $this->fib('I ___ a book before bed. (membaca)', 'read'),                                                                 // L4 Daily Activities
                ], 30),
                // 6
                $this->lesson('Afternoon Routine', '<h3>Afternoon Routine</h3><p><b>Have lunch</b> (makan siang), <b>Go home</b> (pulang), <b>Finish work</b> (selesai bekerja).</p>', [
                    $this->mc('We have ___ at 1 PM.', ['lunch', 'breakfast', 'dinner', 'supper'], 'lunch'),
                    $this->mc('What do you do after school?', ['Go home', 'Wake up', 'Take a shower', 'Eat breakfast'], 'Go home'),
                    $this->mc('I ___ my work at 5 PM.', ['finish', 'start', 'wake up', 'breakfast'], 'finish'),
                    $this->fib('I have ___ with my friends at noon. (makan siang)', 'lunch'),
                    $this->fib('It is 4 PM, I am ___ home now. (pulang)', 'going'),
                ]),
                // 7
                $this->lesson('Evening Routine', '<h3>Evening Routine</h3><p><b>Have dinner</b> (makan malam), <b>Watch TV</b> (nonton TV), <b>Go to bed</b> (pergi tidur).</p>', [
                    $this->mc('We eat ___ at 7 PM.', ['dinner', 'lunch', 'breakfast', 'snack'], 'dinner'),
                    $this->mc('I ___ to bed at 10 PM.', ['go', 'run', 'eat', 'sit'], 'go'),
                    $this->mc('What do you do to relax in the evening?', ['Watch TV', 'Run', 'Cook', 'Work'], 'Watch TV'),
                    $this->fib('I ___ my pajamas before bed. (memakai)', 'wear'),
                    $this->fib('Good ___, time to sleep! (malam)', 'night'),
                ]),
                // 8
                $this->lesson('Likes & Dislikes', '<h3>Likes & Dislikes</h3><p><b>Like</b> (suka), <b>Love</b> (sangat suka), <b>Hate</b> (benci), <b>Don\'t like</b> (tidak suka).</p>', [
                    $this->mc('I ___ apples. They are delicious!', ['like', 'hate', 'don\'t like', 'am'], 'like'),
                    $this->mc('She ___ snakes. She is afraid of them.', ['hates', 'loves', 'likes', 'is'], 'hates'),
                    $this->mc('We ___ watching movies.', ['enjoy', 'eat', 'sleep', 'run'], 'enjoy'),
                    $this->fib('I ___ like spicy food. (tidak)', 'don\'t'),
                    $this->fib('I ___ my family very much. (sangat suka)', 'love'),
                ]),
                // 9
                $this->lesson('Hobbies', '<h3>Hobbies</h3><p>A hobby is something you enjoy doing in your free time: <b>Swimming</b> (berenang), <b>Cooking</b> (memasak), <b>Drawing</b> (menggambar), <b>Singing</b> (bernyanyi), <b>Dancing</b> (menari).</p>', [
                    $this->mc('Which hobby needs a pool?', ['Swimming', 'Drawing', 'Singing', 'Cooking'], 'Swimming'),
                    $this->mc('Which hobby uses a pencil and paper?', ['Drawing', 'Swimming', 'Dancing', 'Cooking'], 'Drawing'),
                    $this->mc('Which hobby is done in the kitchen?', ['Cooking', 'Swimming', 'Dancing', 'Singing'], 'Cooking'),
                    $this->fib('I like ___ in the pool. (berenang)', 'swimming'),
                    $this->fib('My hobby is ___ delicious food. (memasak)', 'cooking'),
                ]),
                // 10 - Free Time: 5 materi + 5 review (Lesson 6-9)
                $this->lesson('Free Time', '<h3>Free Time</h3><p><b>Go out</b> (pergi keluar), <b>Listen to music</b> (dengar musik), <b>Relax</b> (santai).</p>', [
                    // Materi (5 Q)
                    $this->mc('In my free time, I like to...', ['relax', 'work', 'study', 'stress'], 'relax'),
                    $this->mc('Let\'s ___ with friends this weekend.', ['go out', 'sleep', 'work', 'study'], 'go out'),
                    $this->mc('I listen to the ___ on my phone.', ['music', 'food', 'bed', 'chair'], 'music'),
                    $this->fib('I ___ to music to relax. (mendengarkan)', 'listen'),
                    $this->fib('I like to ___ with my friends. (pergi keluar)', 'go out'),
                    // Review Lesson 6-9 (5 Q)
                    $this->mc('Which meal do we usually eat at noon?', ['Lunch', 'Breakfast', 'Dinner', 'Snack'], 'Lunch'),                    // L6 Afternoon Routine
                    $this->mc('We usually eat dinner in the...', ['Evening', 'Morning', 'Afternoon', 'Noon'], 'Evening'),                     // L7 Evening Routine
                    $this->mc('She ___ spiders. She is scared of them.', ['hates', 'loves', 'likes', 'enjoys'], 'hates'),                    // L8 Likes & Dislikes
                    $this->fib('I ___ pizza. It is my favorite! (sangat suka)', 'love'),                                                     // L8 Likes & Dislikes
                    $this->fib('I like ___ pictures with a pencil. (menggambar)', 'drawing'),                                                // L9 Hobbies
                ], 30),
                // 11
                $this->lesson('Describing People', '<h3>Describing People</h3><p><b>Tall</b> (tinggi), <b>Short</b> (pendek), <b>Young</b> (muda), <b>Old</b> (tua), <b>Friendly</b> (ramah).</p>', [
                    $this->mc('A person who is not tall is...', ['short', 'tall', 'old', 'young'], 'short'),
                    $this->mc('My grandfather is...', ['old', 'young', 'baby', 'short'], 'old'),
                    $this->mc('She smiles a lot. She is very...', ['friendly', 'angry', 'sad', 'tall'], 'friendly'),
                    $this->fib('He is 2 meters tall. He is very ___. (tinggi)', 'tall'),
                    $this->fib('The child is very ___. (muda)', 'young'),
                ]),
                // 12
                $this->lesson('Describing Things', '<h3>Describing Things</h3><p><b>Big</b> (besar), <b>Small</b> (kecil), <b>Expensive</b> (mahal), <b>Cheap</b> (murah), <b>New</b> (baru).</p>', [
                    $this->mc('An elephant is...', ['big', 'small', 'cheap', 'expensive'], 'big'),
                    $this->mc('A mouse is...', ['small', 'big', 'expensive', 'new'], 'small'),
                    $this->mc('A Ferrari is...', ['expensive', 'cheap', 'small', 'old'], 'expensive'),
                    $this->fib('This phone is only $10. It is ___. (murah)', 'cheap'),
                    $this->fib('I bought a ___ car yesterday. (baru)', 'new'),
                ]),
                // 13
                $this->lesson('Present Simple', '<h3>Present Simple</h3><p>Use present simple for habits and facts: <b>I work</b>, <b>You work</b>, <b>They work</b>. For he / she / it, add <b>-s</b>: <b>She works</b>.</p>', [
                    $this->mc('I ___ at a bank.', ['work', 'works', 'working', 'worked'], 'work'),
                    $this->mc('She ___ in a hospital.', ['work', 'works', 'working', 'worked'], 'works'),
                    $this->mc('They ___ every day.', ['work', 'works', 'working', 'worked'], 'work'),
                    $this->fib('He ___ in a school. (bekerja)', 'works'),
                    $this->fib('I ___ English every day. (belajar)', 'study'),
                ]),
                // 14
                $this->lesson('My Daily Life', '<h3>My Daily Life</h3><p>My day: <b>Wake up</b> at 6 AM, <b>Go to work</b> at 8 AM, <b>Have lunch</b> at 12 PM, <b>Go home</b> at 5 PM, <b>Sleep</b> at 10 PM.</p>', [
                    $this->mc('What do you do at 6 AM?', ['Wake up', 'Go to bed', 'Eat lunch', 'Go home'], 'Wake up'),
                    $this->mc('I go to work at...', ['8 AM', '12 PM', '5 PM', '10 PM'], '8 AM'),
                    $this->mc('I have lunch at...', ['8 AM', '12 PM', '5 PM', '10 PM'], '12 PM'),
                    $this->fib('I ___ home at 5 PM. (pulang)', 'go'),
                    $this->fib('I sleep at ___.', '10 PM'),
                ]),
                // 15 - UNIT REVIEW: 10 soal (seluruh unit)
                $this->lesson('Unit Review', '<h3>Unit 2 Review</h3><p>Reviewing all topics from Unit 2: Everyday Life.</p>', [
                    $this->mc('My father\'s mother is my...', ['Grandmother', 'Grandfather', 'Aunt', 'Sister'], 'Grandmother'),                // L1
                    $this->mc('We sleep in the...', ['Bedroom', 'Kitchen', 'Living room', 'Garden'], 'Bedroom'),                              // L3
                    $this->mc('I ___ my teeth in the morning.', ['brush', 'wash', 'eat', 'read'], 'brush'),                                   // L5
                    $this->mc('I ___ to bed at 10 PM.', ['go', 'run', 'eat', 'sit'], 'go'),                                                   // L7
                    $this->mc('I ___ like spiders.', ['don\'t', 'am', 'is', 'are'], 'don\'t'),                                                // L8
                    $this->mc('What is your hobby?', ['I like reading.', 'I am 20.', 'I am from Bali.', 'My name is Budi.'], 'I like reading.'), // L9
                    $this->mc('She is very ___. She always helps people.', ['friendly', 'old', 'big', 'short'], 'friendly'),                  // L11
                    $this->mc('This house is very ___. It has 10 rooms.', ['big', 'small', 'cheap', 'new'], 'big'),                           // L12
                    $this->fib('My brother ___ football every Sunday. (bermain)', 'plays'),                                                   // L13
                    $this->fib('The car is $1,000,000. It is very ___.', 'expensive'),                                                        // L12
                ], 30),
            ]
        ];
    }
}