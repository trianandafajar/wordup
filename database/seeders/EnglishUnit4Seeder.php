<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnglishUnit4Seeder extends Seeder
{
    use CanSeedEnglishContent;

    public function run(): void
    {
        DB::transaction(function () {
            $course = Course::where('title', 'Complete English Mastery')->first();

            if (!$course) {
                return;
            }

            $unitOrder = 4;
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
            'title' => 'Unit 4: Shopping',
            'lessons' => [
                // 1
                $this->lesson('Shopping Basics', '<h3>Shopping Basics</h3><p>Key shopping terms: <b>Buy</b> (membeli), <b>Sell</b> (menjual), <b>Store</b> (toko), <b>Customer</b> (pelanggan), <b>Shopkeeper</b> (penjaga toko).</p>', [
                    $this->mc('What do you do at a store?', ['Buy things', 'Sleep', 'Shower', 'Cook'], 'Buy things'),
                    $this->mc('A person who buys things is a...', ['Customer', 'Shopkeeper', 'Manager', 'Chef'], 'Customer'),
                    $this->mc('What is "toko" in English?', ['Store', 'Home', 'School', 'Park'], 'Store'),
                    $this->fib('I want to ___ a new shirt. (membeli)', 'buy'),
                    $this->fib('The ___ is helping me find shoes. (penjaga toko)', 'shopkeeper'),
                ]),
                // 2
                $this->lesson('Clothes', '<h3>Clothes</h3><p><b>Shirt</b> (kemeja), <b>T-shirt</b> (kaos), <b>Pants</b> (celana), <b>Skirt</b> (rok), <b>Dress</b> (gaun).</p>', [
                    $this->mc('Men usually wear a ___ to the office.', ['Shirt', 'Skirt', 'Dress', 'Bikini'], 'Shirt'),
                    $this->mc('Women wear a ___ or a skirt.', ['Dress', 'Tie', 'Belt', 'Hat'], 'Dress'),
                    $this->mc('What is "kaos" in English?', ['T-shirt', 'Pants', 'Skirt', 'Dress'], 'T-shirt'),
                    $this->fib('I am wearing blue ___. (celana)', 'pants'),
                    $this->fib('She has a beautiful pink ___. (gaun)', 'dress'),
                ]),
                // 3
                $this->lesson('Colors', '<h3>Colors</h3><p>Review and new colors: <b>Grey</b> (abu-abu), <b>Gold</b> (emas), <b>Silver</b> (perak), <b>Light blue</b> (biru muda), <b>Dark green</b> (hijau tua).</p>', [
                    $this->mc('What color is a cloud before rain?', ['Grey', 'Gold', 'Silver', 'Pink'], 'Grey'),
                    $this->mc('Which one is a metal color?', ['Gold', 'Green', 'Red', 'Blue'], 'Gold'),
                    $this->mc('The sky on a clear day is...', ['Light blue', 'Dark green', 'Grey', 'Gold'], 'Light blue'),
                    $this->fib('My watch is ___. (perak)', 'silver'),
                    $this->fib('The leaves are ___ in summer. (hijau tua)', 'dark green'),
                ]),
                // 4
                $this->lesson('Sizes', '<h3>Sizes</h3><p><b>Small (S)</b>, <b>Medium (M)</b>, <b>Large (L)</b>, <b>Extra Large (XL)</b>, <b>Tight</b> (ketat), <b>Loose</b> (longgar).</p>', [
                    $this->mc('Which size is for big people?', ['Large', 'Small', 'Extra Small', 'Tiny'], 'Large'),
                    $this->mc('If clothes are too small, they are...', ['Tight', 'Loose', 'Big', 'Long'], 'Tight'),
                    $this->mc('If clothes are too big, they are...', ['Loose', 'Tight', 'Small', 'Short'], 'Loose'),
                    $this->fib('I need a ___ size. (sedang)', 'medium'),
                    $this->fib('These pants are too ___. I need a bigger size.', 'tight'),
                ]),
                // 5 - Numbers & Prices: 5 materi + 5 review (Lesson 1-4)
                $this->lesson('Numbers & Prices', '<h3>Numbers & Prices</h3><p>Numbers 20-100: <b>Thirty</b> (30), <b>Forty</b> (40), <b>Fifty</b> (50), <b>One hundred</b> (100). <b>Price</b> (harga), <b>Cost</b> (biaya).</p>', [
                    // Materi (5 Q)
                    $this->mc('What is 50 in English?', ['Fifty', 'Fifteen', 'Five', 'Forty'], 'Fifty'),
                    $this->mc('What is 100 in English?', ['One hundred', 'Ten', 'Thousand', 'Fifty'], 'One hundred'),
                    $this->mc('The amount of money you pay is the...', ['Price', 'Size', 'Color', 'Name'], 'Price'),
                    $this->fib('This shirt ___ ten dollars. (seharga)', 'costs'),
                    $this->fib('Twenty plus twenty is ___.', 'forty'),
                    // Review Lesson 1-4 (5 Q)
                    $this->mc('A customer is a person who...', ['Buys', 'Sells', 'Cleans', 'Cooks'], 'Buys'),                          // L1 Shopping Basics
                    $this->mc('Which one is a "kaos"?', ['T-shirt', 'Shirt', 'Dress', 'Skirt'], 'T-shirt'),                           // L2 Clothes
                    $this->mc('If a shirt is not tight, it is...', ['Loose', 'Small', 'Short', 'Tight'], 'Loose'),                    // L4 Sizes
                    $this->fib('The color of my ring is ___. (emas)', 'gold'),                                                        // L3 Colors
                    $this->fib('I need a ___ size. (sedang)', 'medium'),                                                              // L4 Sizes
                ], 30),
                // 6
                $this->lesson('Asking the Price', '<h3>Asking the Price</h3><p>Phrases: <b>How much is this?</b> (Berapa harganya ini?), <b>How much does it cost?</b> (Berapa harganya?), <b>Is there a discount?</b> (Apakah ada diskon?).</p>', [
                    $this->mc('How do you ask for the price?', ['How much is this?', 'Where is it?', 'What is your name?', 'How are you?'], 'How much is this?'),
                    $this->mc('To ask for a lower price, you ask for a...', ['Discount', 'Bill', 'Menu', 'Table'], 'Discount'),
                    $this->mc('"Berapa harganya?" in English is...', ['How much does it cost?', 'What is the size?', 'What color is it?', 'How do you do?'], 'How much does it cost?'),
                    $this->fib('___ much is this bag?', 'How'),
                    $this->fib('Is there a ___ for this shirt? (diskon)', 'discount'),
                ]),
                // 7
                $this->lesson('Buying Clothes', '<h3>Buying Clothes</h3><p><b>Try on</b> (mencoba), <b>Fitting room</b> (kamar ganti), <b>Mirror</b> (cermin), <b>It fits!</b> (Ukurannya pas!).</p>', [
                    $this->mc('Where do you try on clothes?', ['Fitting room', 'Kitchen', 'Bathroom', 'Garden'], 'Fitting room'),
                    $this->mc('You look at yourself in a...', ['Mirror', 'Window', 'Door', 'Floor'], 'Mirror'),
                    $this->mc('When the size is right, you say...', ['It fits!', 'It is big!', 'It is small!', 'It is red!'], 'It fits!'),
                    $this->fib('Can I ___ this on? (mencoba)', 'try'),
                    $this->fib('Where is the ___ room? (kamar ganti)', 'fitting'),
                ]),
                // 8
                $this->lesson('Buying Groceries', '<h3>Buying Groceries</h3><p><b>Supermarket</b>, <b>Basket</b> (keranjang), <b>Cart/Trolley</b> (troli), <b>Fresh</b> (segar), <b>Plastic bag</b> (kantong plastik).</p>', [
                    $this->mc('You put many things in a...', ['Cart', 'Pocket', 'Hat', 'Shoe'], 'Cart'),
                    $this->mc('You buy food at a...', ['Supermarket', 'Cinema', 'Library', 'Park'], 'Supermarket'),
                    $this->mc('Fruit should be...', ['Fresh', 'Old', 'Broken', 'Blue'], 'Fresh'),
                    $this->fib('Do you need a ___ bag? (plastik)', 'plastic'),
                    $this->fib('Put the milk in the ___. (keranjang)', 'basket'),
                ]),
                // 9
                $this->lesson('At the Store', '<h3>At the Store</h3><p><b>Aisle</b> (lorong), <b>Shelf</b> (rak), <b>Cashier</b> (kasir), <b>Counter</b> (meja kasir).</p>', [
                    $this->mc('You pay at the...', ['Cashier', 'Aisle', 'Shelf', 'Garden'], 'Cashier'),
                    $this->mc('Things are on the...', ['Shelf', 'Roof', 'Floor', 'Sky'], 'Shelf'),
                    $this->mc('A walking path in a store is an...', ['Aisle', 'Road', 'Street', 'Bridge'], 'Aisle'),
                    $this->fib('The milk is in ___ 5. (lorong)', 'aisle'),
                    $this->fib('Please go to the ___ to pay. (kasir)', 'cashier'),
                ]),
                // 10 - Payment & Money: 5 materi + 5 review (Lesson 6-9)
                $this->lesson('Payment & Money', '<h3>Payment & Money</h3><p><b>Cash</b> (tunai), <b>Credit card</b> (kartu kredit), <b>Receipt</b> (struk), <b>Change</b> (kembalian).</p>', [
                    // Materi (5 Q)
                    $this->mc('Paying with paper money is...', ['Cash', 'Card', 'Phone', 'Apple'], 'Cash'),
                    $this->mc('The paper after you pay is a...', ['Receipt', 'Menu', 'Bill', 'Book'], 'Receipt'),
                    $this->mc('The money you get back is...', ['Change', 'Tip', 'Cost', 'Price'], 'Change'),
                    $this->fib('Can I pay by ___ card?', 'credit'),
                    $this->fib('Here is your ___. (struk)', 'receipt'),
                    // Review Lesson 6-9 (5 Q)
                    $this->mc('How do you ask for the price?', ['How much is this?', 'Where is it?', 'What is your name?', 'How are you?'], 'How much is this?'), // L6 Asking the Price
                    $this->mc('Where do you try on clothes?', ['Fitting room', 'Kitchen', 'Bathroom', 'Garden'], 'Fitting room'),    // L7 Buying Clothes
                    $this->mc('Things in a store are on the...', ['Shelf', 'Roof', 'Floor', 'Sky'], 'Shelf'),                         // L9 At the Store
                    $this->fib('Is there a ___ for this shirt? (diskon)', 'discount'),                                                // L6 Asking the Price
                    $this->fib('The fruit is very ___. (segar)', 'fresh'),                                                            // L8 Buying Groceries
                ], 30),
                // 11
                $this->lesson('Comparing Products', '<h3>Comparing Products</h3><p><b>Cheaper</b> (lebih murah), <b>More expensive</b> (lebih mahal), <b>Better</b> (lebih baik), <b>Worse</b> (lebih buruk).</p>', [
                    $this->mc('This bag is $10. That bag is $5. This bag is...', ['More expensive', 'Cheaper', 'Better', 'Worse'], 'More expensive'),
                    $this->mc('This bag is $5. That bag is $10. This bag is...', ['Cheaper', 'More expensive', 'Bigger', 'Smaller'], 'Cheaper'),
                    $this->mc('Gold is ___ than plastic.', ['more expensive', 'cheaper', 'worse', 'smaller'], 'more expensive'),
                    $this->fib('Is this shirt ___ than that one? (lebih murah)', 'cheaper'),
                    $this->fib('The red one is ___ than the blue one. (lebih baik)', 'better'),
                ]),
                // 12
                $this->lesson('Describing Products', '<h3>Describing Products</h3><p><b>Soft</b> (lembut), <b>Hard</b> (keras), <b>Heavy</b> (berat), <b>Light</b> (ringan), <b>Comfortable</b> (nyaman).</p>', [
                    $this->mc('A pillow is...', ['soft', 'hard', 'heavy', 'sharp'], 'soft'),
                    $this->mc('A big stone is...', ['heavy', 'light', 'soft', 'comfortable'], 'heavy'),
                    $this->mc('A feather is...', ['light', 'heavy', 'hard', 'expensive'], 'light'),
                    $this->fib('This sofa is very ___. I can sleep on it. (nyaman)', 'comfortable'),
                    $this->fib('The table is made of wood. It is ___. (keras)', 'hard'),
                ]),
                // 13
                $this->lesson('Asking for Help', '<h3>Asking for Help</h3><p>Phrases: <b>Can you help me?</b> (Bisa bantu saya?), <b>Where can I find...?</b> (Di mana saya bisa menemukan...?), <b>Do you have...?</b> (Apakah Anda punya...?).</p>', [
                    $this->mc('To ask for help, you say...', ['Can you help me?', 'Go away!', 'Where is my mom?', 'I am hungry.'], 'Can you help me?'),
                    $this->mc('To find something, you say...', ['Where can I find...?', 'What is this?', 'Who are you?', 'Is it red?'], 'Where can I find...?'),
                    $this->mc('To check stock, you say...', ['Do you have...?', 'Is it expensive?', 'I want it!', 'Can I pay?'], 'Do you have...?'),
                    $this->fib('___ you help me, please?', 'Can'),
                    $this->fib('___ can I find the milk?', 'Where'),
                ]),
                // 14
                $this->lesson('Shopping Conversation', '<h3>Shopping Conversation</h3><p>Shop talk: <b>Can I help you?</b> (Ada yang bisa dibantu?), <b>I\'m just looking.</b> (Saya hanya melihat-lihat.), <b>I\'ll take it.</b> (Saya ambil yang ini.), <b>Here you are.</b> (Ini dia / Silakan.).</p>', [
                    $this->mc('The shopkeeper says "Can I help you?" You say...', ['I\'m just looking, thanks.', 'Go away.', 'I am a table.', 'Good night.'], 'I\'m just looking, thanks.'),
                    $this->mc('You decide to buy the shirt. You say...', ['I\'ll take it.', 'I hate it.', 'It is a shirt.', 'Where is it?'], 'I\'ll take it.'),
                    $this->mc('You give money to the cashier. You say...', ['Here you are.', 'Good morning.', 'I am hungry.', 'It is red.'], 'Here you are.'),
                    $this->fib('Welcome! Can I ___ you? (membantu)', 'help'),
                    $this->fib('It fits well. I will ___ it. (ambil)', 'take'),
                ]),
                // 15 - UNIT REVIEW: 10 soal (seluruh unit)
                $this->lesson('Unit Review', '<h3>Unit 4 Review</h3><p>Reviewing all topics from Unit 4: Shopping.</p>', [
                    $this->mc('Which size is "M"?', ['Medium', 'Small', 'Large', 'Extra Small'], 'Medium'),                           // L4
                    $this->mc('Where do you pay?', ['At the cashier', 'In the fitting room', 'On the shelf', 'In the aisle'], 'At the cashier'), // L9
                    $this->mc('Paying with paper money is...', ['Cash', 'Credit card', 'Apple', 'A bag'], 'Cash'),                    // L10
                    $this->mc('This is $5. That is $10. This is...', ['Cheaper', 'More expensive', 'Better', 'Bigger'], 'Cheaper'),   // L11
                    $this->mc('A feather is...', ['light', 'heavy', 'hard', 'expensive'], 'light'),                                   // L12
                    $this->mc('To ask for help, you say...', ['Can you help me?', 'Go away!', 'Where is my mom?', 'I am hungry.'], 'Can you help me?'), // L13
                    $this->mc('You decide to buy the shirt. You say...', ['I\'ll take it.', 'I hate it.', 'It is a shirt.', 'Where is it?'], 'I\'ll take it.'), // L14
                    $this->fib('___ much does it cost?', 'How'),                                                                      // L6
                    $this->fib('Can I have a ___? (struk)', 'receipt'),                                                               // L10
                    $this->fib('I need a ___ room. (kamar ganti)', 'fitting'),                                                        // L7
                ], 30),
            ]
        ];
    }
}