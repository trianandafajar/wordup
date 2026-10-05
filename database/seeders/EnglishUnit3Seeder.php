<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnglishUnit3Seeder extends Seeder
{
    use CanSeedEnglishContent;

    public function run(): void
    {
        DB::transaction(function () {
            $course = Course::where('title', 'Complete English Mastery')->first();
            
            if (!$course) {
                return;
            }

            $unitOrder = 3;
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
            'title' => 'Unit 3: Food & Drinks',
            'lessons' => [
                // 1
                $this->lesson('Food Basics', '<h3>Food Basics</h3><p>Basic food vocabulary: <b>Bread</b> (roti), <b>Rice</b> (nasi), <b>Noodles</b> (mie), <b>Eggs</b> (telur), <b>Cheese</b> (keju).</p>', [
                    $this->mc('What is "roti" in English?', ['Rice', 'Bread', 'Noodles', 'Eggs'], 'Bread'),
                    $this->mc('Which one do we eat in Indonesia?', ['Bread', 'Rice', 'Pasta', 'Potatoes'], 'Rice'),
                    $this->mc('Eggs come from...', ['Chickens', 'Cows', 'Fish', 'Trees'], 'Chickens'),
                    $this->fib('I eat ___ for breakfast. (roti)', 'bread'),
                    $this->fib('Indonesians eat ___ every day. (nasi)', 'rice'),
                ]),
                // 2
                $this->lesson('Fruits & Vegetables', '<h3>Fruits & Vegetables</h3><p><b>Apple</b> (apel), <b>Banana</b> (pisang), <b>Orange</b> (jeruk), <b>Carrot</b> (wortel), <b>Tomato</b> (tomat).</p>', [
                    $this->mc('Which one is a fruit?', ['Carrot', 'Potato', 'Apple', 'Onion'], 'Apple'),
                    $this->mc('Which one is orange?', ['Apple', 'Banana', 'Carrot', 'Grape'], 'Carrot'),
                    $this->mc('Monkeys like...', ['Apples', 'Bananas', 'Carrots', 'Tomatoes'], 'Bananas'),
                    $this->fib('An ___ is red and sweet. (apel)', 'apple'),
                    $this->fib('A ___ is used in salad. (tomat)', 'tomato'),
                ]),
                // 3
                $this->lesson('Meat & Seafood', '<h3>Meat & Seafood</h3><p><b>Chicken</b> (ayam), <b>Beef</b> (daging sapi), <b>Fish</b> (ikan), <b>Shrimp</b> (udang), <b>Pork</b> (daging babi).</p>', [
                    $this->mc('What do we get from cows?', ['Chicken', 'Beef', 'Fish', 'Shrimp'], 'Beef'),
                    $this->mc('Which one lives in the ocean?', ['Chicken', 'Beef', 'Fish', 'Pork'], 'Fish'),
                    $this->mc('Which one is seafood?', ['Beef', 'Chicken', 'Shrimp', 'Pork'], 'Shrimp'),
                    $this->fib('I like fried ___. (ayam)', 'chicken'),
                    $this->fib('___ live in the water. (ikan)', 'Fish'),
                ]),
                // 4
                $this->lesson('Drinks', '<h3>Drinks</h3><p><b>Water</b> (air), <b>Milk</b> (susu), <b>Juice</b> (jus), <b>Coffee</b> (kopi), <b>Tea</b> (teh).</p>', [
                    $this->mc('What do we drink when we are thirsty?', ['Water', 'Bread', 'Rice', 'Chicken'], 'Water'),
                    $this->mc('Which drink comes from cows?', ['Water', 'Milk', 'Juice', 'Coffee'], 'Milk'),
                    $this->mc('Which drink is hot?', ['Juice', 'Water', 'Coffee', 'Milk'], 'Coffee'),
                    $this->fib('I drink ___ every morning. (air)', 'water'),
                    $this->fib('Orange ___ is sweet. (jus)', 'juice'),
                ]),
                // 5 - REVIEW (10 Q)
                $this->lesson('Review: Food & Drinks Basics', '<h3>Review 1</h3><p>Reviewing Food Basics, Fruits, Vegetables, Meat, and Drinks.</p>', [
                    $this->mc('Which one is a vegetable?', ['Apple', 'Banana', 'Carrot', 'Orange'], 'Carrot'),
                    $this->mc('What is "nasi" in English?', ['Bread', 'Rice', 'Noodles', 'Pasta'], 'Rice'),
                    $this->mc('Which one is seafood?', ['Chicken', 'Beef', 'Fish', 'Pork'], 'Fish'),
                    $this->mc('We drink ___ when thirsty.', ['Water', 'Bread', 'Chicken', 'Rice'], 'Water'),
                    $this->mc('Which fruit is yellow?', ['Apple', 'Banana', 'Orange', 'Grape'], 'Banana'),
                    $this->mc('Beef comes from...', ['Chickens', 'Cows', 'Fish', 'Pigs'], 'Cows'),
                    $this->mc('Which drink is made from beans?', ['Water', 'Milk', 'Juice', 'Coffee'], 'Coffee'),
                    $this->fib('An ___ a day keeps the doctor away.', 'apple'),
                    $this->fib('I eat ___ and eggs for breakfast. (roti)', 'bread'),
                    $this->fib('___ is white and comes from cows. (susu)', 'Milk'),
                ], 30),
                // 6
                $this->lesson('Breakfast', '<h3>Breakfast</h3><p>Common breakfast foods: <b>Cereal</b> (sereal), <b>Toast</b> (roti panggang), <b>Pancakes</b> (panekuk), <b>Bacon</b> (daging asap).</p>', [
                    $this->mc('What do people eat for breakfast?', ['Cereal', 'Steak', 'Soup', 'Salad'], 'Cereal'),
                    $this->mc('Toast is made from...', ['Bread', 'Rice', 'Noodles', 'Potatoes'], 'Bread'),
                    $this->mc('Which one is sweet?', ['Bacon', 'Eggs', 'Pancakes', 'Toast'], 'Pancakes'),
                    $this->fib('I have ___ and milk for breakfast. (sereal)', 'cereal'),
                    $this->fib('___ is crispy and salty. (daging asap)', 'Bacon'),
                ]),
                // 7
                $this->lesson('Lunch & Dinner', '<h3>Lunch & Dinner</h3><p><b>Soup</b> (sup), <b>Salad</b> (salad), <b>Steak</b> (steak), <b>Pasta</b> (pasta), <b>Pizza</b> (pizza).</p>', [
                    $this->mc('Which one is Italian?', ['Steak', 'Soup', 'Pizza', 'Salad'], 'Pizza'),
                    $this->mc('A ___ is healthy and green.', ['Pizza', 'Steak', 'Salad', 'Soup'], 'Salad'),
                    $this->mc('Which one is hot and liquid?', ['Salad', 'Steak', 'Pizza', 'Soup'], 'Soup'),
                    $this->fib('I like spaghetti, a type of ___.', 'pasta'),
                    $this->fib('___ is meat cooked on a grill.', 'Steak'),
                ]),
                // 8
                $this->lesson('At a Restaurant', '<h3>At a Restaurant</h3><p>Restaurant vocabulary: <b>Waiter</b> (pelayan), <b>Menu</b> (menu), <b>Table</b> (meja), <b>Bill</b> (tagihan), <b>Tip</b> (tip).</p>', [
                    $this->mc('Who serves food at a restaurant?', ['Waiter', 'Chef', 'Customer', 'Manager'], 'Waiter'),
                    $this->mc('What do you read to choose food?', ['Bill', 'Menu', 'Table', 'Tip'], 'Menu'),
                    $this->mc('What do you pay at the end?', ['Menu', 'Table', 'Bill', 'Waiter'], 'Bill'),
                    $this->fib('Can I see the ___, please?', 'menu'),
                    $this->fib('We sit at a ___ to eat.', 'table'),
                ]),
                // 9
                $this->lesson('Ordering Food', '<h3>Ordering Food</h3><p>Useful phrases: <b>I would like...</b> (Saya ingin...), <b>Can I have...?</b> (Bolehkah saya...), <b>What do you recommend?</b> (Apa yang Anda rekomendasikan?).</p>', [
                    $this->mc('How do you order politely?', ['Give me food!', 'I want this!', 'Can I have...?', 'Food now!'], 'Can I have...?'),
                    $this->mc('What means "Saya ingin..."?', ['I want', 'I would like', 'Give me', 'I need'], 'I would like'),
                    $this->mc('To ask for suggestions, you say...', ['What do you recommend?', 'Give me food.', 'I am hungry.', 'Where is food?'], 'What do you recommend?'),
                    $this->fib('___ I have the menu? (polite)', 'Can'),
                    $this->fib('I ___ like a pizza, please.', 'would'),
                ]),
                // 10 - REVIEW (10 Q)
                $this->lesson('Review: Meals & Restaurants', '<h3>Review 2</h3><p>Reviewing Breakfast, Lunch, Dinner, and Restaurant vocabulary.</p>', [
                    $this->mc('What do you eat in the morning?', ['Breakfast', 'Lunch', 'Dinner', 'Snack'], 'Breakfast'),
                    $this->mc('Who serves you at a restaurant?', ['Chef', 'Waiter', 'Customer', 'Manager'], 'Waiter'),
                    $this->mc('Which one is Italian food?', ['Steak', 'Pizza', 'Soup', 'Salad'], 'Pizza'),
                    $this->mc('To order politely, you say...', ['Give me!', 'Can I have...?', 'I want now!', 'Food!'], 'Can I have...?'),
                    $this->mc('What do you read at a restaurant?', ['Bill', 'Book', 'Menu', 'Newspaper'], 'Menu'),
                    $this->mc('Toast is made from...', ['Rice', 'Bread', 'Noodles', 'Potatoes'], 'Bread'),
                    $this->mc('A salad is usually...', ['Hot', 'Cold', 'Spicy', 'Sweet'], 'Cold'),
                    $this->fib('I have ___ and milk for breakfast. (sereal)', 'cereal'),
                    $this->fib('Can I see the ___, please?', 'menu'),
                    $this->fib('I ___ like a coffee, please.', 'would'),
                ], 30),
                // 11
                $this->lesson('Quantities & Portions', '<h3>Quantities & Portions</h3><p><b>A piece of</b> (sepotong), <b>A cup of</b> (secangkir), <b>A glass of</b> (segelas), <b>A bowl of</b> (semangkuk), <b>A plate of</b> (sepiring).</p>', [
                    $this->mc('I want ___ water.', ['a piece of', 'a glass of', 'a loaf of', 'a slice of'], 'a glass of'),
                    $this->mc('Can I have ___ coffee?', ['a bowl of', 'a cup of', 'a glass of', 'a piece of'], 'a cup of'),
                    $this->mc('I eat ___ rice.', ['a cup of', 'a glass of', 'a bowl of', 'a piece of'], 'a bowl of'),
                    $this->fib('I want a ___ of cake. (sepotong)', 'piece'),
                    $this->fib('Can I have a ___ of fried rice? (sepiring)', 'plate'),
                ]),
                // 12
                $this->lesson('Cooking & Ingredients', '<h3>Cooking & Ingredients</h3><p>Cooking verbs: <b>Boil</b> (merebus), <b>Fry</b> (menggoreng), <b>Bake</b> (memanggang), <b>Grill</b> (membakar), <b>Mix</b> (mencampur).</p>', [
                    $this->mc('You ___ eggs in a pan.', ['boil', 'fry', 'bake', 'grill'], 'fry'),
                    $this->mc('You ___ a cake in an oven.', ['boil', 'fry', 'bake', 'grill'], 'bake'),
                    $this->mc('You ___ water for tea.', ['fry', 'boil', 'bake', 'mix'], 'boil'),
                    $this->fib('I ___ vegetables on the grill. (membakar)', 'grill'),
                    $this->fib('___ the ingredients together. (mencampur)', 'Mix'),
                ]),
                // 13
                $this->lesson('Food Preferences', '<h3>Food Preferences</h3><p>Talking about preferences: <b>Favorite</b> (favorit), <b>Delicious</b> (lezat), <b>Tasty</b> (enak), <b>Bland</b> (hambar), <b>Spicy</b> (pedas).</p>', [
                    $this->mc('My ___ food is pizza.', ['favorite', 'bland', 'spicy', 'sour'], 'favorite'),
                    $this->mc('This food has no taste. It is...', ['delicious', 'tasty', 'bland', 'spicy'], 'bland'),
                    $this->mc('Chili makes food...', ['sweet', 'bland', 'spicy', 'sour'], 'spicy'),
                    $this->fib('This cake is ___! (lezat)', 'delicious'),
                    $this->fib('Indonesian food is usually ___.', 'spicy'),
                ]),
                // 14
                $this->lesson('Talking About Meals', '<h3>Talking About Meals</h3><p>Meal expressions: <b>I had...</b> (Saya sudah makan...), <b>I am having...</b> (Saya sedang makan...), <b>I will have...</b> (Saya akan makan...).</p>', [
                    $this->mc('What did you have for breakfast? I ___ cereal.', ['have', 'had', 'will have', 'am having'], 'had'),
                    $this->mc('What are you eating now? I ___ pizza.', ['had', 'have', 'am having', 'will have'], 'am having'),
                    $this->mc('What will you eat tonight? I ___ pasta.', ['had', 'have', 'am having', 'will have'], 'will have'),
                    $this->fib('I ___ lunch at noon yesterday.', 'had'),
                    $this->fib('I ___ dinner right now. (sedang)', 'am having'),
                ]),
                // 15 - FINAL REVIEW (10 Q)
                $this->lesson('Unit 3 Review', '<h3>Unit 3 Final Review</h3><p>Reviewing all topics from Unit 3: Food & Drinks.</p>', [
                    $this->mc('What is "nasi" in English?', ['Bread', 'Rice', 'Noodles', 'Pasta'], 'Rice'),
                    $this->mc('Which one is a fruit?', ['Carrot', 'Potato', 'Apple', 'Onion'], 'Apple'),
                    $this->mc('Fish is a type of...', ['Meat', 'Seafood', 'Vegetable', 'Fruit'], 'Seafood'),
                    $this->mc('Who serves food at a restaurant?', ['Chef', 'Waiter', 'Manager', 'Customer'], 'Waiter'),
                    $this->mc('I want ___ water.', ['a piece of', 'a glass of', 'a bowl of', 'a cup of'], 'a glass of'),
                    $this->mc('You ___ eggs in a pan.', ['boil', 'fry', 'bake', 'grill'], 'fry'),
                    $this->mc('This food is very ___! I love it.', ['bland', 'bad', 'delicious', 'boring'], 'delicious'),
                    $this->fib('Can I have a ___ of coffee?', 'cup'),
                    $this->fib('I ___ pizza for dinner last night.', 'had'),
                    $this->fib('My ___ food is fried chicken.', 'favorite'),
                ], 30),
            ]
        ];
    }
}
