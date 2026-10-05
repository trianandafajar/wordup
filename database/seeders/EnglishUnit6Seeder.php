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

            if (!$course) {
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
                    $this->mc('The opposite of far is...', ['Near', 'Close', 'Next', 'Adjacent'], 'Near'),
                    $this->mc('The post office is ___ the bank and the library.', ['between', 'near', 'opposite', 'behind'], 'between'),
                    $this->mc('What is across from something?', ['Near', 'Opposite', 'Behind', 'Far'], 'Opposite'),
                    $this->fib('The park is ___ my house.', 'near'),
                    $this->fib('The car is ___ the building.', 'behind'),
                ]),
                // 5 - REVIEW (10 Q)
                $this->lesson('Review: Places & Location', '<h3>Review 1</h3><p>Reviewing Places, Buildings, City Areas, and Location Words.</p>', [
                    $this->mc('Where do you borrow books?', ['Park', 'Library', 'Hospital', 'Museum'], 'Library'),
                    $this->mc('Where do you work?', ['School', 'Office', 'Park', 'Library'], 'Office'),
                    $this->mc('What crosses a river?', ['Road', 'Street', 'Bridge', 'Building'], 'Bridge'),
                    $this->mc('The opposite of far is...', ['Near', 'Far', 'Opposite', 'Behind'], 'Near'),
                    $this->mc('Where do you get money?', ['Bank', 'Library', 'Hospital', 'School'], 'Bank'),
                    $this->mc('A large open public area is a...', ['Park', 'Street', 'Square', 'Road'], 'Square'),
                    $this->mc('The post office is ___ the bank and the store.', ['between', 'opposite', 'near', 'behind'], 'between'),
                    $this->fib('I like to walk in the ___.', 'park'),
                    $this->fib('The hospital is ___ the school.', 'near'),
                    $this->fib('The bank is on Main ___.', 'Street'),
                ], 30),
                // 6
                $this->lesson('Left & Right', '<h3>Left & Right</h3><p><b>Left</b> (kiri), <b>Right</b> (kanan), <b>Straight</b> (lurus), <b>Turn</b> (belok), <b>Go ahead</b> (terus).</p>', [
                    $this->mc('Your left hand is on this side.', ['This side', 'Other side', 'Front', 'Back'], 'This side'),
                    $this->mc('The opposite of left is...', ['Right', 'Front', 'Back', 'Straight'], 'Right'),
                    $this->mc('What means "lurus"?', ['Left', 'Right', 'Straight', 'Turn'], 'Straight'),
                    $this->fib('Turn ___ at the corner.', 'right'),
                    $this->fib('Go ___ on this road. (lurus)', 'straight'),
                ]),
                // 7
                $this->lesson('Giving Directions', '<h3>Giving Directions</h3><p><b>Go straight</b> (terus), <b>Turn left/right</b> (belok kiri/kanan), <b>Next to</b> (di sebelah), <b>In front of</b> (di depan).</p>', [
                    $this->mc('How do you tell someone the way?', ['Go straight', 'I don\'t know', 'That\'s it', 'Goodbye'], 'Go straight'),
                    $this->mc('What does "belok kanan" mean?', ['Turn right', 'Turn left', 'Go straight', 'Stop'], 'Turn right'),
                    $this->mc('What is next to the bank?', ['The library', 'The park', 'The school', 'Need more info'], 'Need more info'),
                    $this->fib('Go ___ and then turn right.', 'straight'),
                    $this->fib('The post office is in ___ of the bank.', 'front'),
                ]),
                // 8
                $this->lesson('Asking for Directions', '<h3>Asking for Directions</h3><p>Phrases: <b>Where is...?</b>, <b>How do I get to...?</b>, <b>Can you help me?</b>, <b>Is it far from here?</b>.</p>', [
                    $this->mc('How do you ask for directions?', ['Where is...?', 'What time is it?', 'What is your name?', 'How old are you?'], 'Where is...?'),
                    $this->mc('To ask about distance, you say...', ['Is it far?', 'Is it big?', 'Is it old?', 'Is it red?'], 'Is it far?'),
                    $this->mc('A polite way to ask is...', ['Can you help me?', 'Help me!', 'Where?', 'Tell me!'], 'Can you help me?'),
                    $this->fib('___ is the library?', 'Where'),
                    $this->fib('___ do I get to the station?', 'How'),
                ]),
                // 9
                $this->lesson('Transportation', '<h3>Transportation</h3><p><b>Bus</b> (bus), <b>Train</b> (kereta), <b>Car</b> (mobil), <b>Taxi</b> (taksi), <b>Bike</b> (sepeda).</p>', [
                    $this->mc('What do many people use to go to work?', ['Bus', 'Bike', 'Car', 'All correct'], 'All correct'),
                    $this->mc('What runs on tracks?', ['Bus', 'Train', 'Car', 'Taxi'], 'Train'),
                    $this->mc('What is "taksi" in English?', ['Bus', 'Train', 'Taxi', 'Car'], 'Taxi'),
                    $this->fib('I take the ___ to work.', 'bus'),
                    $this->fib('I ride my ___ to school.', 'bike'),
                ]),
                // 10 - REVIEW (10 Q)
                $this->lesson('Review: Directions & Transportation', '<h3>Review 2</h3><p>Reviewing Directions, Transportation, and Navigation.</p>', [
                    $this->mc('The opposite of left is...', ['Right', 'Left', 'Front', 'Back'], 'Right'),
                    $this->mc('How do you ask "di mana perpustakaan?"?', ['Where is the library?', 'What is the library?', 'When is the library?', 'Who is at the library?'], 'Where is the library?'),
                    $this->mc('What runs on tracks?', ['Bus', 'Train', 'Car', 'Taxi'], 'Train'),
                    $this->mc('Go ___ and turn left.', ['straight', 'left', 'right', 'back'], 'straight'),
                    $this->mc('Is the bank far?', ['Yes', 'No', 'Maybe', 'Need more info'], 'Need more info'),
                    $this->mc('The post office is in ___ of the school.', ['front', 'behind', 'left', 'right'], 'front'),
                    $this->mc('What means "belok kiri"?', ['Turn left', 'Turn right', 'Go straight', 'Stop'], 'Turn left'),
                    $this->fib('I ride a ___ every day. (sepeda)', 'bike'),
                    $this->fib('The library is ___ the bank.', 'near'),
                    $this->fib('Turn ___ at the traffic light.', 'right'),
                ], 30),
                // 11
                $this->lesson('Taking a Bus', '<h3>Taking a Bus</h3><p><b>Bus stop</b> (halte bus), <b>Ticket</b> (tiket), <b>Driver</b> (sopir), <b>Station</b> (stasiun), <b>Schedule</b> (jadwal).</p>', [
                    $this->mc('Where do you wait for a bus?', ['Train station', 'Bus stop', 'Airport', 'Office'], 'Bus stop'),
                    $this->mc('What do you buy to ride?', ['Money', 'Ticket', 'Card', 'Passport'], 'Ticket'),
                    $this->mc('Who drives the bus?', ['Passenger', 'Guard', 'Driver', 'Manager'], 'Driver'),
                    $this->fib('I wait at the bus ___.', 'stop'),
                    $this->fib('I need a ___ to ride the bus.', 'ticket'),
                ]),
                // 12
                $this->lesson('Taking a Train', '<h3>Taking a Train</h3><p><b>Train station</b>, <b>Platform</b> (peron), <b>Seat</b> (kursi), <b>Track</b> (rel), <b>Conductor</b> (kondektur).</p>', [
                    $this->mc('Where do you catch a train?', ['Bus stop', 'Train station', 'Airport', 'Parking'], 'Train station'),
                    $this->mc('What is "peron" in English?', ['Track', 'Platform', 'Station', 'Seat'], 'Platform'),
                    $this->mc('Trains run on...', ['Roads', 'Tracks', 'Streets', 'Highways'], 'Tracks'),
                    $this->fib('I sit on a ___ in the train.', 'seat'),
                    $this->fib('The train departs from ___ 2.', 'platform'),
                ]),
                // 13
                $this->lesson('Driving', '<h3>Driving</h3><p><b>Drive</b> (mengemudi), <b>Steering wheel</b> (kemudi), <b>Parking</b> (parkir), <b>Traffic light</b> (lampu lalu lintas), <b>Speed limit</b> (batas kecepatan).</p>', [
                    $this->mc('What do you hold to steer a car?', ['Wheel', 'Steering wheel', 'Handle', 'Control'], 'Steering wheel'),
                    $this->mc('What color means stop?', ['Red', 'Yellow', 'Green', 'Blue'], 'Red'),
                    $this->mc('Where do you leave your car?', ['Street', 'Parking', 'Road', 'Highway'], 'Parking'),
                    $this->fib('I ___ a car to work.', 'drive'),
                    $this->fib('Stop at the red ___.', 'light'),
                ]),
                // 14
                $this->lesson('Maps & Routes', '<h3>Maps & Routes</h3><p><b>Map</b> (peta), <b>Route</b> (rute), <b>Distance</b> (jarak), <b>GPS</b>, <b>Navigation</b> (navigasi).</p>', [
                    $this->mc('What shows places and roads?', ['Schedule', 'Map', 'Ticket', 'Menu'], 'Map'),
                    $this->mc('What is the way from A to B?', ['Schedule', 'Map', 'Route', 'Ticket'], 'Route'),
                    $this->mc('GPS helps you with...', ['Navigation', 'Parking', 'Driving', 'All correct'], 'Navigation'),
                    $this->fib('I use a ___ to find the way.', 'map'),
                    $this->fib('The shortest ___ is through Main Street.', 'route'),
                ]),
                // 15 - FINAL REVIEW (10 Q)
                $this->lesson('Unit Review', '<h3>Unit 6 Final Review</h3><p>Reviewing all topics from Unit 6: Places & Directions.</p>', [
                    $this->mc('Where do you borrow books?', ['Library', 'Hospital', 'Park', 'Museum'], 'Library'),
                    $this->mc('What is the opposite of left?', ['Right', 'Front', 'Back', 'Left'], 'Right'),
                    $this->mc('How do you ask for directions?', ['Where is...?', 'What time?', 'Who?', 'Why?'], 'Where is...?'),
                    $this->mc('What runs on tracks?', ['Bus', 'Train', 'Car', 'Taxi'], 'Train'),
                    $this->mc('The bank is ___ the library.', ['near', 'far', 'opposite', 'behind'], 'near'),
                    $this->mc('Where do you wait for a bus?', ['Train station', 'Bus stop', 'Airport', 'Parking'], 'Bus stop'),
                    $this->mc('What helps you navigate?', ['Map', 'Ticket', 'Schedule', 'Menu'], 'Map'),
                    $this->fib('Go ___ and turn right.', 'straight'),
                    $this->fib('I need a ___ to ride the bus.', 'ticket'),
                    $this->fib('Stop at the red ___.', 'light'),
                ], 30),
            ]
        ];
    }
}
