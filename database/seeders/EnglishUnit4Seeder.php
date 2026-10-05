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
                // 5 - REVIEW (10 Q)
                $this->lesson('Review: Basics & Clothes', '<h3>Review 1</h3><p>Reviewing Shopping Basics, Clothes, Colors, and Sizes.</p>', [
                    $this->mc('A customer is a person who...', ['Buys', 'Sells', 'Cleans', 'Cooks'], 'Buys'),
                    $this->mc('Which size is "L"?', ['Large', 'Small', 'Medium', 'Long'], 'Large'),
                    $this->mc('What color is gold?', ['Yellow/Metal', 'Blue', 'Green', 'Grey'], 'Yellow/Metal'),
                    $this->mc('She wears a ___ to the party.', ['Dress', 'Pants', 'Tie', 'Shirt'], 'Dress'),
                    $this->mc('The store is ___. (tutup)', ['closed', 'open', 'busy', 'free'], 'closed'),
                    $this->mc('Which one is a "kaos"?', ['T-shirt', 'Shirt', 'Dress', 'Skirt'], 'T-shirt'),
                    $this->mc('If a shirt is not tight, it is...', ['Loose', 'Small', 'Short', 'Tight'], 'Loose'),
                    $this->fib('I want to ___ this bag. (membeli)', 'buy'),
                    $this->fib('She likes ___ green. (hijau tua)', 'dark'),
                    $this->fib('My size is ___. (kecil)', 'small'),
                ], 30),
                // 6
                $this->lesson('Numbers & Prices', '<h3>Numbers & Prices</h3><p>Numbers 20-100: <b>Thirty</b> (30), <b>Forty</b> (40), <b>Fifty</b> (50), <b>One hundred</b> (100). <b>Price</b> (harga), <b>Cost</b> (biaya).</p>', [
                    $this->mc('What is 50 in English?', ['Fifty', 'Fifteen', 'Five', 'Forty'], 'Fifty'),
                    $this->mc('What is 100 in English?', ['One hundred', 'Ten', 'Thousand', 'Fifty'], 'One hundred'),
                    $this->mc('The amount of money you pay is the...', ['Price', 'Size', 'Color', 'Name'], 'Price'),
                    $this->fib('This shirt ___ ten dollars. (seharga)', 'costs'),
                    $this->fib('Twenty plus twenty is ___.', 'forty'),
                ]),
                // 7
                $this->lesson('Asking the Price', '<h3>Asking the Price</h3><p>Phrases: <b>How much is this?</b> (Berapa harganya ini?), <b>How much does it cost?</b> (Berapa harganya?), <b>Is there a discount?</b> (Apakah ada diskon?).</p>', [
                    $this->mc('How do you ask for the price?', ['How much is this?', 'Where is it?', 'What is your name?', 'How are you?'], 'How much is this?'),
                    $this->mc('To ask for a lower price, you ask for a...', ['Discount', 'Bill', 'Menu', 'Table'], 'Discount'),
                    $this->mc('"Berapa harganya?" in English is...', ['How much does it cost?', 'What is the size?', 'What color is it?', 'How do you do?'], 'How much does it cost?'),
                    $this->fib('___ much is this bag?', 'How'),
                    $this->fib('Is there a ___ for this shirt? (diskon)', 'discount'),
                ]),
                // 8
                $this->lesson('Buying Clothes', '<h3>Buying Clothes</h3><p><b>Try on</b> (mencoba), <b>Fitting room</b> (kamar ganti), <b>Mirror</b> (cermin), <b>It fits!</b> (Ukurannya pas!).</p>', [
                    $this->mc('Where do you try on clothes?', ['Fitting room', 'Kitchen', 'Bathroom', 'Garden'], 'Fitting room'),
                    $this->mc('You look at yourself in a...', ['Mirror', 'Window', 'Door', 'Floor'], 'Mirror'),
                    $this->mc('When the size is right, you say...', ['It fits!', 'It is big!', 'It is small!', 'It is red!'], 'It fits!'),
                    $this->fib('Can I ___ this on? (mencoba)', 'try'),
                    $this->fib('Where is the ___ room? (kamar ganti)', 'fitting'),
                ]),
                // 9
                $this->lesson('Buying Groceries', '<h3>Buying Groceries</h3><p><b>Supermarket</b>, <b>Basket</b> (keranjang), <b>Cart/Trolley</b> (troli), <b>Fresh</b> (segar), <b>Plastic bag</b> (kantong plastik).</p>', [
                    $this->mc('You put many things in a...', ['Cart', 'Pocket', 'Hat', 'Shoe'], 'Cart'),
                    $this->mc('You buy food at a...', ['Supermarket', 'Cinema', 'Library', 'Park'], 'Supermarket'),
                    $this->mc('Fruit should be...', ['Fresh', 'Old', 'Broken', 'Blue'], 'Fresh'),
                    $this->fib('Do you need a ___ bag? (plastik)', 'plastic'),
                    $this->fib('Put the milk in the ___. (keranjang)', 'basket'),
                ]),
                // 10 - REVIEW (10 Q)
                $this->lesson('Review: Prices & Buying', '<h3>Review 2</h3><p>Reviewing Prices, Asking the Price, and Buying things.</p>', [
                    $this->mc('How much is ___? (ini)', ['this', 'that', 'these', 'those'], 'this'),
                    $this->mc('One hundred is...', ['100', '10', '1000', '50'], '100'),
                    $this->mc('Where is the fitting room?', ['Over there', 'In the kitchen', 'In the car', 'At home'], 'Over there'),
                    $this->mc('Is there a ___? (diskon)', ['discount', 'bill', 'receipt', 'price'], 'discount'),
                    $this->mc('The shirt ___ $20.', ['costs', 'buys', 'sells', 'helps'], 'costs'),
                    $this->mc('You use a ___ for many groceries.', ['Cart', 'Basket', 'Pocket', 'Hand'], 'Cart'),
                    $this->mc('I want to ___ this on.', ['try', 'buy', 'sell', 'give'], 'try'),
                    $this->fib('___ much does it cost?', 'How'),
                    $this->fib('Fifty plus thirty is ___. (80)', 'eighty'),
                    $this->fib('The fruit is very ___. (segar)', 'fresh'),
                ], 30),
                // 11
                $this->lesson('At the Store', '<h3>At the Store</h3><p><b>Aisle</b> (lorong), <b>Shelf</b> (rak), <b>Cashier</b> (kasir), <b>Counter</b> (meja kasir).</p>', [
                    $this->mc('You pay at the...', ['Cashier', 'Aisle', 'Shelf', 'Garden'], 'Cashier'),
                    $this->mc('Things are on the...', ['Shelf', 'Roof', 'Floor', 'Sky'], 'Shelf'),
                    $this->mc('A walking path in a store is an...', ['Aisle', 'Road', 'Street', 'Bridge'], 'Aisle'),
                    $this->fib('The milk is in ___ 5. (lorong)', 'aisle'),
                    $this->fib('Please go to the ___ to pay. (kasir)', 'cashier'),
                ]),
                // 12
                $this->lesson('Payment & Money', '<h3>Payment & Money</h3><p><b>Cash</b> (tunai), <b>Credit card</b> (kartu kredit), <b>Receipt</b> (struk), <b>Change</b> (kembalian).</p>', [
                    $this->mc('Paying with paper money is...', ['Cash', 'Card', 'Phone', 'Apple'], 'Cash'),
                    $this->mc('The paper after you pay is a...', ['Receipt', 'Menu', 'Bill', 'Book'], 'Receipt'),
                    $this->mc('The money you get back is...', ['Change', 'Tip', 'Cost', 'Price'], 'Change'),
                    $this->fib('Can I pay by ___ card?', 'credit'),
                    $this->fib('Here is your ___. (struk)', 'receipt'),
                ]),
                // 13
                $this->lesson('Comparing Products', '<h3>Comparing Products</h3><p><b>Cheaper</b> (lebih murah), <b>More expensive</b> (lebih mahal), <b>Better</b> (lebih baik), <b>Worse</b> (lebih buruk).</p>', [
                    $this->mc('This bag is $10. That bag is $5. This bag is...', ['More expensive', 'Cheaper', 'Better', 'Worse'], 'More expensive'),
                    $this->mc('This bag is $5. That bag is $10. This bag is...', ['Cheaper', 'More expensive', 'Bigger', 'Smaller'], 'Cheaper'),
                    $this->mc('The quality of this is...', ['Better', 'Best', 'Good', 'Bad'], 'Better'),
                    $this->fib('Is this shirt ___ than that one? (lebih murah)', 'cheaper'),
                    $this->fib('The red one is ___ than the blue one. (lebih baik)', 'better'),
                ]),
                // 14
                $this->lesson('Asking for Help', '<h3>Asking for Help</h3><p>Phrases: <b>Can you help me?</b> (Bisa bantu saya?), <b>Where can I find...?</b> (Di mana saya bisa menemukan...?), <b>Do you have...?</b> (Apakah Anda punya...?).</p>', [
                    $this->mc('To ask for help, you say...', ['Can you help me?', 'Go away!', 'Where is my mom?', 'I am hungry.'], 'Can you help me?'),
                    $this->mc('To find something, you say...', ['Where can I find...?', 'What is this?', 'Who are you?', 'Is it red?'], 'Where can I find...?'),
                    $this->mc('To check stock, you say...', ['Do you have...?', 'Is it expensive?', 'I want it!', 'Can I pay?'], 'Do you have...?'),
                    $this->fib('___ you help me, please?', 'Can'),
                    $this->fib('___ can I find the milk?', 'Where'),
                ]),
                // 15 - FINAL REVIEW (10 Q)
                $this->lesson('Unit Review', '<h3>Unit 4 Final Review</h3><p>Reviewing all topics from Unit 4: Shopping.</p>', [
                    $this->mc('How much is this?', ['It is $20', 'It is red', 'It is small', 'It is a bag'], 'It is $20'),
                    $this->mc('Where do you pay?', ['At the cashier', 'In the fitting room', 'On the shelf', 'In the aisle'], 'At the cashier'),
                    $this->mc('Which size is "M"?', ['Medium', 'Small', 'Large', 'Extra Small'], 'Medium'),
                    $this->mc('Cash is...', ['Paper money', 'Credit card', 'Apple', 'A bag'], 'Paper money'),
                    $this->mc('Can I ___ this on?', ['try', 'buy', 'sell', 'give'], 'try'),
                    $this->mc('This is $5. That is $10. This is...', ['Cheaper', 'More expensive', 'Better', 'Bigger'], 'Cheaper'),
                    $this->mc('A person who buys is a...', ['Customer', 'Shopkeeper', 'Manager', 'Chef'], 'Customer'),
                    $this->fib('___ much does it cost?', 'How'),
                    $this->fib('Can I have a ___? (struk)', 'receipt'),
                    $this->fib('I need a ___ room. (kamar ganti)', 'fitting'),
                ], 30),
            ]
        ];
    }
}
