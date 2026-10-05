<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnglishUnit9Seeder extends Seeder
{
    use CanSeedEnglishContent;

    public function run(): void
    {
        DB::transaction(function () {
            $course = Course::where('title', 'Complete English Mastery')->first();

            if (!$course) {
                return;
            }

            $unitOrder = 9;
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
            'title' => 'Unit 9: Travel & Real Situations',
            'lessons' => [
                // 1
                $this->lesson('Travel Basics', '<h3>Travel Basics</h3><p><b>Travel</b> (bepergian), <b>Vacation</b> (liburan), <b>Trip</b> (perjalanan), <b>Tourist</b> (wisatawan), <b>Destination</b> (tujuan).</p>', [
                    $this->mc('What do you call a holiday?', ['Vacation', 'Work', 'Job', 'School'], 'Vacation'),
                    $this->mc('Who visits a new city?', ['Tourist', 'Local', 'Worker', 'Chef'], 'Tourist'),
                    $this->mc('Where are you going?', ['Destination', 'Map', 'Ticket', 'Bill'], 'Destination'),
                    $this->fib('I am going on a business ___.', 'trip'),
                    $this->fib('I love to ___ around the world.', 'travel'),
                ]),
                // 2
                $this->lesson('At the Airport', '<h3>At the Airport</h3><p><b>Airport</b> (bandara), <b>Flight</b> (penerbangan), <b>Departure</b> (keberangkatan), <b>Arrival</b> (kedatangan), <b>Gate</b> (pintu masuk).</p>', [
                    $this->mc('Where do you catch a plane?', ['Airport', 'Train station', 'Bus stop', 'Office'], 'Airport'),
                    $this->mc('The time when the plane leaves is...', ['Departure', 'Arrival', 'Gate', 'Flight'], 'Departure'),
                    $this->mc('What is the place to wait for a plane?', ['Gate', 'Ticket', 'Map', 'Cart'], 'Gate'),
                    $this->fib('My ___ is at 5 PM.', 'flight'),
                    $this->fib('The ___ time is 8 PM.', 'arrival'),
                ]),
                // 3
                $this->lesson('Passport & Documents', '<h3>Passport & Documents</h3><p><b>Passport</b> (paspor), <b>Visa</b>, <b>Boarding pass</b> (tiket pesawat), <b>ID Card</b> (KTP).</p>', [
                    $this->mc('What do you need for international travel?', ['Passport', 'Menu', 'Table', 'Tip'], 'Passport'),
                    $this->mc('What shows your seat on the plane?', ['Boarding pass', 'Passport', 'Visa', 'Map'], 'Boarding pass'),
                    $this->mc('What do you need to enter a country?', ['Visa', 'Map', 'Bill', 'Tip'], 'Visa'),
                    $this->fib('Please show your ___.', 'passport'),
                    $this->fib('Your ___ is required for entry.', 'visa'),
                ]),
                // 4
                $this->lesson('Checking In', '<h3>Checking In</h3><p><b>Check-in</b> (lapor masuk), <b>Baggage</b> (bagasi), <b>Suitcase</b> (koper), <b>Weight</b> (berat).</p>', [
                    $this->mc('What do you give the airline?', ['Baggage', 'Ticket', 'Passport', 'All correct'], 'All correct'),
                    $this->mc('What is a large bag for clothes?', ['Suitcase', 'Basket', 'Cart', 'Wallet'], 'Suitcase'),
                    $this->mc('The airline checks your bag...', ['Weight', 'Color', 'Name', 'Type'], 'Weight'),
                    $this->fib('I need to ___ at the counter.', 'check-in'),
                    $this->fib('How much does your ___ weigh?', 'baggage'),
                ]),
                // 5 - REVIEW (10 Q)
                $this->lesson('Review: Airport & Documents', '<h3>Review 1</h3><p>Reviewing Travel Basics, Airport, Passport, and Checking In.</p>', [
                    $this->mc('A holiday is a...', ['Vacation', 'Trip', 'Job', 'Meeting'], 'Vacation'),
                    $this->mc('Where do you catch a plane?', ['Airport', 'Bus stop', 'Train station', 'Office'], 'Airport'),
                    $this->mc('What do you need to travel abroad?', ['Passport', 'Menu', 'Table', 'Tip'], 'Passport'),
                    $this->mc('What shows your seat?', ['Boarding pass', 'Visa', 'Map', 'ID card'], 'Boarding pass'),
                    $this->mc('A large bag for clothes is a...', ['Suitcase', 'Basket', 'Wallet', 'Cart'], 'Suitcase'),
                    $this->mc('The plane arrives at...', ['Arrival', 'Departure', 'Gate', 'Flight'], 'Arrival'),
                    $this->mc('A visa is required for...', ['Entering a country', 'Flying', 'Shopping', 'Working'], 'Entering a country'),
                    $this->fib('My ___ is at 6 AM.', 'flight'),
                    $this->fib('___ in at the counter.', 'Check'),
                    $this->fib('How much does the ___ weigh?', 'baggage'),
                ], 30),
                // 6
                $this->lesson('Hotels', '<h3>Hotels</h3><p><b>Hotel</b> (hotel), <b>Reception</b> (resepsionis), <b>Key card</b> (kartu kunci), <b>Room</b> (kamar), <b>Reservation</b> (reservasi).</p>', [
                    $this->mc('Where do you stay on vacation?', ['Hotel', 'Airport', 'Office', 'School'], 'Hotel'),
                    $this->mc('Who welcomes guests?', ['Reception', 'Chef', 'Driver', 'Student'], 'Reception'),
                    $this->mc('What do you use to open the room door?', ['Key card', 'Password', 'Menu', 'Ticket'], 'Key card'),
                    $this->fib('I have a ___ for tonight.', 'reservation'),
                    $this->fib('My ___ number is 101.', 'room'),
                ]),
                // 7
                $this->lesson('Hotel Requests', '<h3>Hotel Requests</h3><p><b>Extra towels</b> (handuk tambahan), <b>Wake-up call</b> (panggilan bangun), <b>Room service</b> (layanan kamar), <b>Cleaning</b> (pembersihan).</p>', [
                    $this->mc('How do you ask for more towels?', ['Extra towels', 'Wake-up call', 'Room service', 'Cleaning'], 'Extra towels'),
                    $this->mc('What means "Panggilan bangun"?', ['Wake-up call', 'Cleaning', 'Room service', 'Extra towels'], 'Wake-up call'),
                    $this->mc('What means food brought to your room?', ['Room service', 'Cleaning', 'Wake-up call', 'Extra towels'], 'Room service'),
                    $this->fib('I need a ___ call at 7 AM.', 'wake-up'),
                    $this->fib('Can I have ___ service?', 'room'),
                ]),
                // 8
                $this->lesson('Transportation', '<h3>Transportation</h3><p><b>Taxi</b> (taksi), <b>Subway</b> (kereta bawah tanah), <b>Bus</b> (bus), <b>Rent a car</b> (sewa mobil).</p>', [
                    $this->mc('What is a fast car service?', ['Taxi', 'Bus', 'Subway', 'Walking'], 'Taxi'),
                    $this->mc('Trains that are underground are...', ['Subway', 'Bus', 'Taxi', 'Walking'], 'Subway'),
                    $this->mc('How do you drive your own car?', ['Rent a car', 'Bus', 'Taxi', 'Walking'], 'Rent a car'),
                    $this->fib('Take the ___ to the city center.', 'subway'),
                    $this->fib('I want to ___ a car for the day.', 'rent'),
                ]),
                // 9
                $this->lesson('Asking for Directions', '<h3>Asking for Directions</h3><p><b>Where is...?</b>, <b>Is it far?</b>, <b>Turn left/right</b>, <b>Go straight</b>.</p>', [
                    $this->mc('How do you ask where a place is?', ['Where is...?', 'Who is...?', 'How is...?', 'What is...?'], 'Where is...?'),
                    $this->mc('To move forward, you...', ['Go straight', 'Turn left', 'Turn right', 'Stop'], 'Go straight'),
                    $this->mc('A question about distance is...', ['Is it far?', 'Is it red?', 'Is it big?', 'Is it nice?'], 'Is it far?'),
                    $this->fib('___ do I get to the hotel?', 'How'),
                    $this->fib('Turn ___ at the traffic light.', 'right'),
                ]),
                // 10 - REVIEW (10 Q)
                $this->lesson('Review: Hotels & Directions', '<h3>Review 2</h3><p>Reviewing Hotels, Requests, Transportation, and Directions.</p>', [
                    $this->mc('Where do you check in at a hotel?', ['Reception', 'Airport', 'Office', 'School'], 'Reception'),
                    $this->mc('What do you ask for food in your room?', ['Room service', 'Wake-up call', 'Cleaning', 'Towels'], 'Room service'),
                    $this->mc('What is underground transport?', ['Subway', 'Bus', 'Taxi', 'Train'], 'Subway'),
                    $this->mc('How do you ask for the way?', ['Where is...?', 'What is...?', 'Who is...?', 'How is...?'], 'Where is...?'),
                    $this->mc('What do you use to open the room?', ['Key card', 'Password', 'Menu', 'Ticket'], 'Key card'),
                    $this->mc('How to ask for a lower price?', ['Is there a discount?', 'Is it far?', 'Where is it?', 'How is it?'], 'Is there a discount?'),
                    $this->mc('To move forward, you...', ['Go straight', 'Turn left', 'Turn right', 'Stop'], 'Go straight'),
                    $this->fib('I have a ___ for tonight.', 'reservation'),
                    $this->fib('___ do I get to the station?', 'How'),
                    $this->fib('I want to ___ a car.', 'rent'),
                ], 30),
                // 11
                $this->lesson('Restaurants Abroad', '<h3>Restaurants Abroad</h3><p><b>Table for two</b>, <b>Order</b> (pesan), <b>Menu</b>, <b>Check/Bill</b> (tagihan).</p>', [
                    $this->mc('How do you ask for a table?', ['Table for two', 'Check', 'Menu', 'Food'], 'Table for two'),
                    $this->mc('What do you read to choose food?', ['Menu', 'Bill', 'Table', 'Tip'], 'Menu'),
                    $this->mc('What do you ask for to pay?', ['Bill', 'Menu', 'Table', 'Waiter'], 'Bill'),
                    $this->fib('I would like to ___ food.', 'order'),
                    $this->fib('Can I have the ___, please?', 'bill'),
                ]),
                // 12
                $this->lesson('Tourist Attractions', '<h3>Tourist Attractions</h3><p><b>Museum</b> (museum), <b>Park</b> (taman), <b>Monument</b> (monumen), <b>Sightseeing</b> (jalan-jalan).</p>', [
                    $this->mc('What is a famous building or statue?', ['Monument', 'Museum', 'Park', 'Street'], 'Monument'),
                    $this->mc('What is "jalan-jalan"?', ['Sightseeing', 'Working', 'Sleeping', 'Eating'], 'Sightseeing'),
                    $this->mc('What holds art/history?', ['Museum', 'Monument', 'Park', 'Street'], 'Museum'),
                    $this->fib('I love ___ in this city.', 'sightseeing'),
                    $this->fib('We visited the old ___ today.', 'monument'),
                ]),
                // 13
                $this->lesson('Emergencies', '<h3>Emergencies</h3><p><b>Help!</b> (Tolong!), <b>Police</b> (polisi), <b>Hospital</b> (rumah sakit), <b>Lost</b> (tersesat).</p>', [
                    $this->mc('What do you shout when in danger?', ['Help!', 'Hello!', 'Hi!', 'Bye!'], 'Help!'),
                    $this->mc('What do you say if you don\'t know where you are?', ['I am lost', 'I am here', 'I am happy', 'I am hungry'], 'I am lost'),
                    $this->mc('Where do you go if very sick?', ['Hospital', 'Police', 'Park', 'Street'], 'Hospital'),
                    $this->fib('Call the ___.', 'police'),
                    $this->fib('I am ___! Help me.', 'lost'),
                ]),
                // 14
                $this->lesson('Problems & Complaints', '<h3>Problems & Complaints</h3><p><b>Broken</b> (rusak), <b>Refund</b> (pengembalian uang), <b>Wait</b> (tunggu), <b>Manager</b> (manajer).</p>', [
                    $this->mc('What means "rusak"?', ['Broken', 'New', 'Fresh', 'Big'], 'Broken'),
                    $this->mc('What is getting your money back?', ['Refund', 'Bill', 'Tip', 'Cost'], 'Refund'),
                    $this->mc('Who is the boss of the hotel/store?', ['Manager', 'Driver', 'Chef', 'Wait'], 'Manager'),
                    $this->fib('This air conditioner is ___.', 'broken'),
                    $this->fib('I would like a ___, please.', 'refund'),
                ]),
                // 15 - FINAL REVIEW (10 Q)
                $this->lesson('Unit Review', '<h3>Unit 9 Final Review</h3><p>Reviewing all topics from Unit 9: Travel.</p>', [
                    $this->mc('A holiday is a...', ['Vacation', 'Work', 'Job', 'School'], 'Vacation'),
                    $this->mc('Where do you stay on vacation?', ['Hotel', 'Airport', 'Office', 'School'], 'Hotel'),
                    $this->mc('What is the fast car service?', ['Taxi', 'Bus', 'Subway', 'Walking'], 'Taxi'),
                    $this->mc('Where is the library?', ['Where is...?', 'Who is...?', 'How is...?', 'What is...?'], 'Where is...?'),
                    $this->mc('What do you pay at the end?', ['Bill', 'Menu', 'Table', 'Tip'], 'Bill'),
                    $this->mc('What holds art/history?', ['Museum', 'Monument', 'Park', 'Street'], 'Museum'),
                    $this->mc('What means "Tolong!"?', ['Help!', 'Hello!', 'Hi!', 'Bye!'], 'Help!'),
                    $this->fib('I am ___! Help me.', 'lost'),
                    $this->fib('I need a ___ for this bag.', 'refund'),
                    $this->fib('My ___ number is 101.', 'room'),
                ], 30),
            ]
        ];
    }
}
