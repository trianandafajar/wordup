<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnglishUnit6Seeder extends Seeder
{
    use CanSeedEnglishContent;

    public function run(): void
    {
        DB::transaction(function () {
            $course = Course::where('title', 'Complete English Mastery')->first();

            if (! $course) {
                return;
            }

            $unitOrder = 6;
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
            'title' => 'Unit 6: Places & Directions',
            'lessons' => [
                // 1
                $this->lesson('Places Around Town', '<h3>Places Around Town</h3><p><b>Park</b> (taman), <b>Library</b> (perpustakaan), <b>Hospital</b> (rumah sakit), <b>Police station</b> (kantor polisi), <b>Museum</b> (museum).</p>', [
                    $this->mc('Where do you read books?', ['Park', 'Library', 'Hospital', 'Museum'], 'Library'),
                    $this->mc('Where do you go when sick?', ['Park', 'Library', 'Hospital', 'Museum'], 'Hospital'),
                    $this->mc('Where do you call the police?', ['Library', 'Museum', 'Police station', 'Park'], 'Police station'),
                    $this->fib('I like to relax in the ___.', 'park'),
                    $this->fib('The ___ has old paintings.', 'museum'),
                ]),
                // 2
                $this->lesson('Buildings', '<h3>Buildings</h3><p><b>School</b> (sekolah), <b>Office</b> (kantor), <b>Restaurant</b> (restoran), <b>Bank</b> (bank), <b>Supermarket</b> (supermarket).</p>', [
                    $this->mc('Where do you study?', ['Office', 'School', 'Bank', 'Restaurant'], 'School'),
                    $this->mc('Where do you work?', ['School', 'Office', 'Restaurant', 'Park'], 'Office'),
                    $this->mc('Where do you get money?', ['Restaurant', 'Bank', 'School', 'Office'], 'Bank'),
                    $this->fib('I eat dinner at a ___.', 'restaurant'),
                    $this->fib('I buy food at the ___.', 'supermarket'),
                ]),
                // 3
                $this->lesson('Inside the City', '<h3>Inside the City</h3><p><b>Street</b> (jalan), <b>Road</b> (jalan raya), <b>Avenue</b> (avenue), <b>Bridge</b> (jembatan), <b>Square</b> (alun-alun).</p>', [
                    $this->mc('What is a path in the city?', ['Street', 'Park', 'Building', 'Tree'], 'Street'),
                    $this->mc('What crosses over a river?', ['Road', 'Street', 'Bridge', 'Square'], 'Bridge'),
                    $this->mc('A large open public area is a...', ['Park', 'Square', 'Street', 'Road'], 'Square'),
                    $this->fib('I live on Main ___.', 'Street'),
                    $this->fib('The ___ over the river is new.', 'bridge'),
                ]),
                // 4
                $this->lesson('Location Words', '<h3>Location Words</h3><p><b>Near</b> (dekat), <b>Far</b> (jauh), <b>Between</b> (di antara), <b>Opposite</b> (seberang), <b>Behind</b> (di belakang).</p>', [
                    $this->mc('The opposite of far is...', ['Near', 'Big', 'Old', 'Tall'], 'Near'),
                    $this->mc('The post office is ___ the bank and the library.', ['between', 'near', 'opposite', 'behind'], 'between'),
                    $this->mc('What is across from something?', ['Near', 'Opposite', 'Behind', 'Far'], 'Opposite'),
                    $this->fib('The park is ___ my house. (dekat)', 'near'),
                    $this->fib('The car is ___ the building. (di belakang)', 'behind'),
                ]),
                // 5 - Left & Right: 5 materi + 5 review (Lesson 1-4)
                $this->lesson('Left & Right', '<h3>Left & Right</h3><p><b>Left</b> (kiri), <b>Right</b> (kanan), <b>Straight</b> (lurus), <b>Turn</b> (belok), <b>Go ahead</b> (terus).</p>', [
                    // Materi (5 Q)
                    $this->mc('The opposite of left is...', ['Right', 'Front', 'Back', 'Straight'], 'Right'),
                    $this->mc('What does "lurus" mean?', ['Left', 'Right', 'Straight', 'Turn'], 'Straight'),
                    $this->mc('What does "belok" mean?', ['Turn', 'Stop', 'Go ahead', 'Straight'], 'Turn'),
                    $this->fib('Turn ___ at the corner. (kanan)', 'right'),
                    $this->fib('Go ___ on this road. (lurus)', 'straight'),
                    // Review Lesson 1-4 (5 Q)
                    $this->mc('Where do you borrow books?', ['Park', 'Library', 'Hospital', 'Museum'], 'Library'),                    // L1 Places Around Town
                    $this->mc('Where do you study?', ['Office', 'School', 'Bank', 'Restaurant'], 'School'),                          // L2 Buildings
                    $this->mc('What crosses over a river?', ['Road', 'Street', 'Bridge', 'Square'], 'Bridge'),                       // L3 Inside the City
                    $this->fib('The hospital is ___ the school. (dekat)', 'near'),                                                   // L4 Location Words
                    $this->fib('The garden is ___ the house. (di belakang)', 'behind'),                                              // L4 Location Words
                ], 30),
                // 6
                $this->lesson('Giving Directions', '<h3>Giving Directions</h3><p><b>Go straight</b> (terus), <b>Turn left/right</b> (belok kiri/kanan), <b>Next to</b> (di sebelah), <b>In front of</b> (di depan).</p>', [
                    $this->mc('How do you tell someone to continue forward?', ['Go straight', 'Turn around', 'Stop here', 'Goodbye'], 'Go straight'),
                    $this->mc('What does "belok kanan" mean?', ['Turn right', 'Turn left', 'Go straight', 'Stop'], 'Turn right'),
                    $this->mc('What does "next to" mean?', ['Di sebelah', 'Di depan', 'Di belakang', 'Di atas'], 'Di sebelah'),
                    $this->fib('Go ___ and then turn right. (lurus)', 'straight'),
                    $this->fib('The post office is in ___ of the bank. (di depan)', 'front'),
                ]),
                // 7
                $this->lesson('Asking for Directions', '<h3>Asking for Directions</h3><p>Phrases: <b>Where is...?</b>, <b>How do I get to...?</b>, <b>Can you help me?</b>, <b>Is it far from here?</b>.</p>', [
                    $this->mc('How do you ask for directions?', ['Where is...?', 'What time is it?', 'What is your name?', 'How old are you?'], 'Where is...?'),
                    $this->mc('To ask about distance, you say...', ['Is it far?', 'Is it big?', 'Is it old?', 'Is it red?'], 'Is it far?'),
                    $this->mc('A polite way to ask is...', ['Can you help me?', 'Help me!', 'Where?', 'Tell me!'], 'Can you help me?'),
                    $this->fib('___ is the library?', 'Where'),
                    $this->fib('___ do I get to the station?', 'How'),
                ]),
                // 8
                $this->lesson('Transportation', '<h3>Transportation</h3><p><b>Bus</b> (bus), <b>Train</b> (kereta), <b>Car</b> (mobil), <b>Taxi</b> (taksi), <b>Bike</b> (sepeda).</p>', [
                    $this->mc('Which one has two wheels?', ['Bus', 'Bike', 'Car', 'Train'], 'Bike'),
                    $this->mc('What runs on tracks?', ['Bus', 'Train', 'Car', 'Taxi'], 'Train'),
                    $this->mc('What is "taksi" in English?', ['Bus', 'Train', 'Taxi', 'Car'], 'Taxi'),
                    $this->fib('I take the ___ to work. (bus)', 'bus'),
                    $this->fib('I ride my ___ to school. (sepeda)', 'bike'),
                ]),
                // 9
                $this->lesson('Taking a Bus', '<h3>Taking a Bus</h3><p><b>Bus stop</b> (halte bus), <b>Ticket</b> (tiket), <b>Driver</b> (sopir), <b>Station</b> (stasiun), <b>Schedule</b> (jadwal).</p>', [
                    $this->mc('Where do you wait for a bus?', ['Train station', 'Bus stop', 'Airport', 'Office'], 'Bus stop'),
                    $this->mc('What do you buy to ride?', ['Money', 'Ticket', 'Card', 'Passport'], 'Ticket'),
                    $this->mc('Who drives the bus?', ['Passenger', 'Guard', 'Driver', 'Manager'], 'Driver'),
                    $this->fib('I wait at the bus ___.', 'stop'),
                    $this->fib('I need a ___ to ride the bus.', 'ticket'),
                ]),
                // 10 - Taking a Train: 5 materi + 5 review (Lesson 6-9)
                $this->lesson('Taking a Train', '<h3>Taking a Train</h3><p><b>Train station</b>, <b>Platform</b> (peron), <b>Seat</b> (kursi), <b>Track</b> (rel), <b>Conductor</b> (kondektur).</p>', [
                    // Materi (5 Q)
                    $this->mc('Where do you catch a train?', ['Bus stop', 'Train station', 'Airport', 'Parking'], 'Train station'),
                    $this->mc('What is "peron" in English?', ['Track', 'Platform', 'Station', 'Seat'], 'Platform'),
                    $this->mc('Trains run on...', ['Roads', 'Tracks', 'Streets', 'Highways'], 'Tracks'),
                    $this->fib('I sit on a ___ in the train. (kursi)', 'seat'),
                    $this->fib('The train departs from ___ 2.', 'platform'),
                    // Review Lesson 6-9 (5 Q)
                    $this->mc('What does "belok kiri" mean?', ['Turn left', 'Turn right', 'Go straight', 'Stop'], 'Turn left'),                                    // L6 Giving Directions
                    $this->mc('What does "in front of" mean?', ['Di depan', 'Di belakang', 'Di sebelah', 'Di antara'], 'Di depan'),                              // L6 Giving Directions
                    $this->mc('How do you ask for the way to the library?', ['Where is the library?', 'Who is the library?', 'When is the library?', 'Why is the library?'], 'Where is the library?'), // L7 Asking for Directions
                    $this->mc('What is "taksi" in English?', ['Bus', 'Train', 'Taxi', 'Car'], 'Taxi'),                                                          // L8 Transportation
                    $this->fib('I need a ___ to ride the bus. (tiket)', 'ticket'),                                                                              // L9 Taking a Bus
                ], 30),
                // 11
                $this->lesson('Driving', '<h3>Driving</h3><p><b>Drive</b> (mengemudi), <b>Steering wheel</b> (kemudi), <b>Parking</b> (parkir), <b>Traffic light</b> (lampu lalu lintas), <b>Speed limit</b> (batas kecepatan).</p>', [
                    $this->mc('What do you hold to steer a car?', ['Steering wheel', 'Pedal', 'Mirror', 'Seat belt'], 'Steering wheel'),
                    $this->mc('What color means stop?', ['Red', 'Yellow', 'Green', 'Blue'], 'Red'),
                    $this->mc('Where do you leave your car?', ['Street', 'Parking', 'Road', 'Highway'], 'Parking'),
                    $this->fib('I ___ a car to work. (mengemudi)', 'drive'),
                    $this->fib('Stop at the red ___.', 'light'),
                ]),
                // 12
                $this->lesson('Maps & Routes', '<h3>Maps & Routes</h3><p><b>Map</b> (peta), <b>Route</b> (rute), <b>Distance</b> (jarak), <b>GPS</b>, <b>Navigation</b> (navigasi).</p>', [
                    $this->mc('What shows places and roads?', ['Schedule', 'Map', 'Ticket', 'Menu'], 'Map'),
                    $this->mc('What is the way from A to B?', ['Schedule', 'Map', 'Route', 'Ticket'], 'Route'),
                    $this->mc('GPS helps you with...', ['Navigation', 'Cooking', 'Sleeping', 'Reading'], 'Navigation'),
                    $this->fib('I use a ___ to find the way. (peta)', 'map'),
                    $this->fib('The shortest ___ is through Main Street. (rute)', 'route'),
                ]),
                // 13
                $this->lesson('Getting Lost', '<h3>Getting Lost</h3><p><b>I am lost</b> (Saya tersesat), <b>Excuse me</b> (Permisi), <b>Wrong way</b> (salah arah), <b>Turn around</b> (putar balik), <b>Landmark</b> (patokan).</p>', [
                    $this->mc('What does "I am lost" mean?', ['Saya tersesat', 'Saya lapar', 'Saya senang', 'Saya lelah'], 'Saya tersesat'),
                    $this->mc('How do you get someone\'s attention politely?', ['Excuse me', 'Hey you', 'Go away', 'Be quiet'], 'Excuse me'),
                    $this->mc('You are going the wrong way. You should...', ['turn around', 'sit down', 'sleep', 'run'], 'turn around'),
                    $this->fib('___ me, where is the station? (Permisi)', 'Excuse'),
                    $this->fib('I am ___. Can you help me? (tersesat)', 'lost'),
                ]),
                // 14
                $this->lesson('Directions Conversation', '<h3>Directions Conversation</h3><p><i>A: Excuse me, how do I get to the museum?</i><br><i>B: Go straight, then turn left at the bank.</i><br><i>A: Thank you very much!</i><br><i>B: You are welcome.</i></p>', [
                    $this->mc('Someone asks, "How do I get to the museum?" A good answer is...', ['Go straight, then turn left.', 'I like museums.', 'It is red.', 'Yes, I do.'], 'Go straight, then turn left.'),
                    $this->mc('After someone helps you, you say...', ['Thank you very much!', 'Go away!', 'Be quiet!', 'Goodbye, bad man!'], 'Thank you very much!'),
                    $this->mc('The reply to "Thank you" is...', ['You are welcome.', 'Good night.', 'See you.', 'I am sorry.'], 'You are welcome.'),
                    $this->fib('Go straight, then turn ___ at the bank. (kiri)', 'left'),
                    $this->fib('The museum is ___ to the park. (di sebelah)', 'next'),
                ]),
                // 15 - UNIT REVIEW: 10 soal (seluruh unit)
                $this->lesson('Unit Review', '<h3>Unit 6 Review</h3><p>Reviewing all topics from Unit 6: Places & Directions.</p>', [
                    $this->mc('Where do you go when you are sick?', ['Park', 'Library', 'Hospital', 'Museum'], 'Hospital'),                                   // L1
                    $this->mc('A large open public area is a...', ['Park', 'Square', 'Street', 'Road'], 'Square'),                                          // L3
                    $this->mc('The post office is ___ the bank and the library.', ['between', 'near', 'opposite', 'behind'], 'between'),                    // L4
                    $this->mc('What does "belok kanan" mean?', ['Turn right', 'Turn left', 'Go straight', 'Stop'], 'Turn right'),                           // L5
                    $this->mc('Which sentence gives directions?', ['Turn left at the bank.', 'I like banks.', 'The bank is big.', 'Banks are old.'], 'Turn left at the bank.'), // L6
                    $this->mc('To ask about distance, you say...', ['Is it far?', 'Is it big?', 'Is it old?', 'Is it red?'], 'Is it far?'),                 // L7
                    $this->mc('What is "taksi" in English?', ['Bus', 'Train', 'Taxi', 'Car'], 'Taxi'),                                                      // L8
                    $this->fib('I wait at the bus ___.', 'stop'),                                                                                           // L9
                    $this->fib('I use a ___ to find the way. (peta)', 'map'),                                                                               // L12
                    $this->fib('___ me, where is the station? (Permisi)', 'Excuse'),                                                                        // L13
                ], 30),
            ],
        ];
    }
}
