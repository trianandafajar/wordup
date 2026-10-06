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

            if (! $course) {
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
            'title' => 'Unit 9: Travel & Real Situations',
            'lessons' => [
                // 1
                $this->lesson('Travel Basics', '<h3>Travel Basics</h3><p><b>Travel</b> (bepergian), <b>Vacation</b> (liburan), <b>Trip</b> (perjalanan), <b>Tourist</b> (wisatawan), <b>Destination</b> (tujuan).</p>', [
                    $this->mc('What do you call a holiday?', ['Vacation', 'Work', 'Job', 'School'], 'Vacation'),
                    $this->mc('Who visits a new city for fun?', ['Tourist', 'Local', 'Worker', 'Chef'], 'Tourist'),
                    $this->mc('The place you are going to is your...', ['Destination', 'Map', 'Ticket', 'Bill'], 'Destination'),
                    $this->fib('I am going on a business ___. (perjalanan)', 'trip'),
                    $this->fib('I love to ___ around the world. (bepergian)', 'travel'),
                ]),
                // 2
                $this->lesson('At the Airport', '<h3>At the Airport</h3><p><b>Airport</b> (bandara), <b>Flight</b> (penerbangan), <b>Departure</b> (keberangkatan), <b>Arrival</b> (kedatangan), <b>Gate</b> (pintu keberangkatan).</p>', [
                    $this->mc('Where do you catch a plane?', ['Airport', 'Train station', 'Bus stop', 'Office'], 'Airport'),
                    $this->mc('The time when the plane leaves is...', ['Departure', 'Arrival', 'Gate', 'Flight'], 'Departure'),
                    $this->mc('Where do you wait before you board a plane?', ['Gate', 'Ticket', 'Map', 'Cart'], 'Gate'),
                    $this->fib('My ___ is at 5 PM. (penerbangan)', 'flight'),
                    $this->fib('The ___ time is 8 PM. (kedatangan)', 'arrival'),
                ]),
                // 3
                $this->lesson('Passport & Documents', '<h3>Passport & Documents</h3><p><b>Passport</b> (paspor), <b>Visa</b> (visa), <b>Boarding pass</b> (tiket naik pesawat), <b>ID Card</b> (KTP).</p>', [
                    $this->mc('What do you need for international travel?', ['Passport', 'Menu', 'Table', 'Tip'], 'Passport'),
                    $this->mc('What shows your seat on the plane?', ['Boarding pass', 'Passport', 'Visa', 'Map'], 'Boarding pass'),
                    $this->mc('What do you need to enter some countries?', ['Visa', 'Map', 'Bill', 'Tip'], 'Visa'),
                    $this->fib('Please show your ___. (paspor)', 'passport'),
                    $this->fib('Your ___ is required for entry.', 'visa'),
                ]),
                // 4
                $this->lesson('Checking In', '<h3>Checking In</h3><p><b>Check-in</b> (lapor masuk), <b>Baggage</b> (bagasi), <b>Suitcase</b> (koper), <b>Weight</b> (berat).</p>', [
                    $this->mc('What do you show to the airline staff at check-in?', ['Passport', 'Menu', 'Key card', 'Bill'], 'Passport'),
                    $this->mc('What is a large bag for clothes?', ['Suitcase', 'Basket', 'Cart', 'Wallet'], 'Suitcase'),
                    $this->mc('The airline checks your bag\'s...', ['Weight', 'Color', 'Name', 'Type'], 'Weight'),
                    $this->fib('I need to ___ at the counter. (lapor masuk)', 'check-in'),
                    $this->fib('How much does your ___ weigh? (bagasi)', 'baggage'),
                ]),
                // 5 - Hotels: 5 materi + 5 review (Lesson 1-4)
                $this->lesson('Hotels', '<h3>Hotels</h3><p><b>Hotel</b> (hotel), <b>Reception</b> (resepsionis), <b>Key card</b> (kartu kunci), <b>Room</b> (kamar), <b>Reservation</b> (reservasi).</p>', [
                    // Materi (5 Q)
                    $this->mc('Where do you stay on vacation?', ['Hotel', 'Airport', 'Office', 'School'], 'Hotel'),
                    $this->mc('Who welcomes guests at a hotel?', ['Reception', 'Chef', 'Driver', 'Student'], 'Reception'),
                    $this->mc('What do you use to open the room door?', ['Key card', 'Password', 'Menu', 'Ticket'], 'Key card'),
                    $this->fib('I have a ___ for tonight. (reservasi)', 'reservation'),
                    $this->fib('My ___ number is 101. (kamar)', 'room'),
                    // Review Lesson 1-4 (5 Q)
                    $this->mc('A person who visits a city on vacation is a...', ['Tourist', 'Pilot', 'Waiter', 'Driver'], 'Tourist'),            // L1 Travel Basics
                    $this->mc('The time when a plane lands is the...', ['Arrival', 'Departure', 'Passport', 'Visa'], 'Arrival'),               // L2 At the Airport
                    $this->mc('Which document shows your seat on the plane?', ['Boarding pass', 'Menu', 'Receipt', 'Map'], 'Boarding pass'),  // L3 Passport & Documents
                    $this->fib('Please put your ___ on the scale. (bagasi)', 'baggage'),                                                      // L4 Checking In
                    $this->fib('The plane leaves at 7 PM. This is the ___ time. (keberangkatan)', 'departure'),                               // L2 At the Airport
                ], 30),
                // 6
                $this->lesson('Hotel Requests', '<h3>Hotel Requests</h3><p><b>Extra towels</b> (handuk tambahan), <b>Wake-up call</b> (panggilan bangun), <b>Room service</b> (layanan kamar), <b>Cleaning</b> (pembersihan).</p>', [
                    $this->mc('What do you ask for if you need more towels?', ['Extra towels', 'Wake-up call', 'Room service', 'Cleaning'], 'Extra towels'),
                    $this->mc('What means "Panggilan bangun"?', ['Wake-up call', 'Cleaning', 'Room service', 'Extra towels'], 'Wake-up call'),
                    $this->mc('What do you call food brought to your room?', ['Room service', 'Cleaning', 'Wake-up call', 'Extra towels'], 'Room service'),
                    $this->fib('I need a ___ call at 7 AM. (bangun)', 'wake-up'),
                    $this->fib('Can I have ___ service, please? (kamar)', 'room'),
                ]),
                // 7
                $this->lesson('Transportation', '<h3>Transportation</h3><p><b>Taxi</b> (taksi), <b>Subway</b> (kereta bawah tanah), <b>Bus</b> (bus), <b>Rent a car</b> (sewa mobil).</p>', [
                    $this->mc('What is a fast car service that you pay for?', ['Taxi', 'Bus', 'Subway', 'Walking'], 'Taxi'),
                    $this->mc('Which train runs underground?', ['Subway', 'Bus', 'Taxi', 'Walking'], 'Subway'),
                    $this->mc('How can you drive your own car on vacation?', ['Rent a car', 'Take a bus', 'Take a taxi', 'Walk'], 'Rent a car'),
                    $this->fib('Take the ___ to the city center. (kereta bawah tanah)', 'subway'),
                    $this->fib('I want to ___ a car for the day. (menyewa)', 'rent'),
                ]),
                // 8
                $this->lesson('Asking for Directions', '<h3>Asking for Directions</h3><p><b>Where is...?</b> (Di mana...?), <b>Is it far?</b> (Apakah jauh?), <b>Turn left/right</b> (Belok kiri/kanan), <b>Go straight</b> (Jalan lurus).</p>', [
                    $this->mc('How do you ask where a place is?', ['Where is...?', 'Who is...?', 'How is...?', 'What is...?'], 'Where is...?'),
                    $this->mc('To move forward, you...', ['Go straight', 'Turn left', 'Turn right', 'Stop'], 'Go straight'),
                    $this->mc('A question about distance is...', ['Is it far?', 'Is it red?', 'Is it big?', 'Is it nice?'], 'Is it far?'),
                    $this->fib('___ do I get to the hotel? (Bagaimana)', 'How'),
                    $this->fib('Turn ___ at the traffic light. (kanan)', 'right'),
                ]),
                // 9
                $this->lesson('Restaurants Abroad', '<h3>Restaurants Abroad</h3><p><b>Table for two</b> (meja untuk dua orang), <b>Order</b> (memesan), <b>Menu</b> (menu), <b>Bill</b> (tagihan).</p>', [
                    $this->mc('How do you ask for a table?', ['Table for two, please.', 'The bill, please.', 'The menu, please.', 'Food, please.'], 'Table for two, please.'),
                    $this->mc('What do you read to choose food?', ['Menu', 'Bill', 'Table', 'Tip'], 'Menu'),
                    $this->mc('What do you ask for when you want to pay?', ['The bill', 'The menu', 'A table', 'A waiter'], 'The bill'),
                    $this->fib('I would like to ___ food. (memesan)', 'order'),
                    $this->fib('Can I have the ___, please? (tagihan)', 'bill'),
                ]),
                // 10 - Tourist Attractions: 5 materi + 5 review (Lesson 6-9)
                $this->lesson('Tourist Attractions', '<h3>Tourist Attractions</h3><p><b>Museum</b> (museum), <b>Park</b> (taman), <b>Monument</b> (monumen), <b>Sightseeing</b> (jalan-jalan wisata).</p>', [
                    // Materi (5 Q)
                    $this->mc('What is a famous building or statue?', ['Monument', 'Museum', 'Park', 'Street'], 'Monument'),
                    $this->mc('What means "jalan-jalan wisata"?', ['Sightseeing', 'Working', 'Sleeping', 'Eating'], 'Sightseeing'),
                    $this->mc('Where can you see art and history?', ['Museum', 'Monument', 'Park', 'Street'], 'Museum'),
                    $this->fib('I love ___ in this city. (jalan-jalan wisata)', 'sightseeing'),
                    $this->fib('We visited the old ___ today. (monumen)', 'monument'),
                    // Review Lesson 6-9 (5 Q)
                    $this->mc('Your bathroom has no clean towels. You ask for...', ['extra towels', 'a taxi', 'a refund', 'a map'], 'extra towels'),            // L6 Hotel Requests
                    $this->mc('Which phrase means "Belok kiri"?', ['Turn left', 'Turn right', 'Go straight', 'Stop'], 'Turn left'),                              // L8 Asking for Directions
                    $this->mc('You finish dinner at a restaurant. You ask for...', ['the bill', 'the menu', 'a table', 'a key card'], 'the bill'),                // L9 Restaurants Abroad
                    $this->fib('A ___ is a car that you pay to take you somewhere. (taksi)', 'taxi'),                                                            // L7 Transportation
                    $this->fib('I need a wake-up ___ at 6 AM. (panggilan)', 'call'),                                                                             // L6 Hotel Requests
                ], 30),
                // 11
                $this->lesson('Emergencies', '<h3>Emergencies</h3><p><b>Help!</b> (Tolong!), <b>Police</b> (polisi), <b>Hospital</b> (rumah sakit), <b>Lost</b> (tersesat).</p>', [
                    $this->mc('What do you shout when you are in danger?', ['Help!', 'Hello!', 'Hi!', 'Bye!'], 'Help!'),
                    $this->mc('What do you say if you don\'t know where you are?', ['I am lost', 'I am here', 'I am happy', 'I am hungry'], 'I am lost'),
                    $this->mc('Where do you go if you are very sick?', ['Hospital', 'Police', 'Park', 'Street'], 'Hospital'),
                    $this->fib('Call the ___. (polisi)', 'police'),
                    $this->fib('I am ___! Help me. (tersesat)', 'lost'),
                ]),
                // 12
                $this->lesson('Problems & Complaints', '<h3>Problems & Complaints</h3><p><b>Broken</b> (rusak), <b>Refund</b> (pengembalian uang), <b>Wait</b> (tunggu), <b>Manager</b> (manajer).</p>', [
                    $this->mc('What means "rusak"?', ['Broken', 'New', 'Fresh', 'Big'], 'Broken'),
                    $this->mc('What do you call getting your money back?', ['Refund', 'Bill', 'Tip', 'Cost'], 'Refund'),
                    $this->mc('Who is the boss of the hotel or store?', ['Manager', 'Driver', 'Chef', 'Guest'], 'Manager'),
                    $this->fib('This air conditioner is ___. (rusak)', 'broken'),
                    $this->fib('I would like a ___, please. (pengembalian uang)', 'refund'),
                ]),
                // 13
                $this->lesson('Asking for Help', '<h3>Asking for Help</h3><p><b>Excuse me</b> (Permisi), <b>Can you help me?</b> (Bisakah Anda membantu saya?), <b>Could you repeat that?</b> (Bisakah Anda mengulanginya?), <b>I don\'t understand</b> (Saya tidak mengerti).</p>', [
                    $this->mc('How do you get someone\'s attention politely?', ['Excuse me', 'Hey you!', 'Listen!', 'Come here!'], 'Excuse me'),
                    $this->mc('You did not hear the answer. You say...', ['Could you repeat that?', 'I am hungry.', 'Goodbye.', 'It is big.'], 'Could you repeat that?'),
                    $this->mc('What means "Saya tidak mengerti"?', ['I don\'t understand', 'I don\'t like it', 'I don\'t know you', 'I don\'t have it'], 'I don\'t understand'),
                    $this->fib('Can you ___ me, please? (membantu)', 'help'),
                    $this->fib('I don\'t ___. Please speak slowly. (mengerti)', 'understand'),
                ]),
                // 14
                $this->lesson('Travel Conversation', '<h3>Travel Conversation</h3><p><b>Where are you from?</b> (Anda berasal dari mana?), <b>How long are you staying?</b> (Berapa lama Anda menginap?), <b>I am here on vacation.</b> (Saya di sini untuk liburan.), <b>Nice to meet you!</b> (Senang bertemu Anda!).</p>', [
                    $this->mc('Someone asks about your country. They say...', ['Where are you from?', 'What time is it?', 'How much is it?', 'Who is she?'], 'Where are you from?'),
                    $this->mc('"How long are you staying?" Your answer is...', ['Five days.', 'In a hotel.', 'By taxi.', 'Yes, please.'], 'Five days.'),
                    $this->mc('The officer asks why you are visiting. You say...', ['I am here on vacation.', 'I like blue.', 'He is a doctor.', 'It is cheap.'], 'I am here on vacation.'),
                    $this->fib('Nice to ___ you! (bertemu)', 'meet'),
                    $this->fib('Where are you ___? I am from Indonesia. (berasal)', 'from'),
                ]),
                // 15 - UNIT REVIEW: 10 soal (seluruh unit)
                $this->lesson('Unit Review', '<h3>Unit 9 Review</h3><p>Reviewing all topics from Unit 9: Travel & Real Situations.</p>', [
                    $this->mc('Where do you wait before you board a plane?', ['Gate', 'Menu', 'Table', 'Room'], 'Gate'),                                         // L2
                    $this->mc('Which document do you need to enter some countries?', ['Visa', 'Menu', 'Receipt', 'Ticket'], 'Visa'),                            // L3
                    $this->mc('What do you ask for to get food in your hotel room?', ['Room service', 'Cleaning', 'Key card', 'Reservation'], 'Room service'),   // L6
                    $this->mc('Which train runs underground?', ['Subway', 'Bus', 'Taxi', 'Bicycle'], 'Subway'),                                                 // L7
                    $this->mc('Which phrase means "Belok kanan"?', ['Turn right', 'Turn left', 'Go straight', 'Stop'], 'Turn right'),                           // L8
                    $this->mc('You arrive at a restaurant with a friend. You say...', ['A table for two, please.', 'I need a taxi.', 'Where is the gate?', 'I need a visa.'], 'A table for two, please.'), // L9
                    $this->mc('Where do you go when you are very sick?', ['Hospital', 'Museum', 'Park', 'Hotel'], 'Hospital'),                                  // L11
                    $this->mc('Your TV is broken. You want your money back. You ask for...', ['a refund', 'a tip', 'a menu', 'a map'], 'a refund'),              // L12
                    $this->fib('___ me, where is the station? (Permisi)', 'Excuse'),                                                                            // L13
                    $this->fib('How long are you ___ in this city? (menginap)', 'staying'),                                                                     // L14
                ], 30),
            ],
        ];
    }
}
