<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnglishUnit1Seeder extends Seeder
{
    use CanSeedEnglishContent;

    public function run(): void
    {
        DB::transaction(function () {
            $course = Course::where('title', 'Complete English Mastery')->first();
            
            if (!$course) {
                return;
            }

            $unitOrder = 1;
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
            'title' => 'Unit 1: Introduction to English',
            'lessons' => [
                $this->lesson('Greetings', '<h3>Greetings</h3><p><b>Hello / Hi</b> adalah sapaan umum.</p><ul><li>Good morning (pagi)</li><li>Good afternoon (siang/sore)</li><li>Good evening (malam)</li><li>Good night (selamat tidur)</li><li>Goodbye / See you (perpisahan)</li></ul>', [
                    $this->mc('Which greeting is used in the morning?', ['Good night', 'Good morning', 'Good evening', 'Goodbye'], 'Good morning'),
                    $this->mc('What do you say when leaving?', ['Hello', 'Good morning', 'Goodbye', 'Nice to meet you'], 'Goodbye'),
                    $this->mc('Which greeting is used before going to bed?', ['Good afternoon', 'Good night', 'Good morning', 'Hello'], 'Good night'),
                    $this->fib('Good ___, Ms. Rina! (sapaan sore hari)', 'afternoon'),
                    $this->fib('Good ___, everyone! (sapaan malam hari saat bertemu)', 'evening'),
                ]),

                $this->lesson('The Alphabet', '<h3>The Alphabet</h3><p>Ada 26 huruf: A B C D E F G H I J K L M N O P Q R S T U V W X Y Z.</p><p>Vokal: <b>A, E, I, O, U</b>. Sisanya adalah konsonan.</p>', [
                    $this->mc('How many letters are in the English alphabet?', ['24', '25', '26', '28'], '26'),
                    $this->mc('Which one is a vowel?', ['B', 'K', 'E', 'T'], 'E'),
                    $this->mc('Which one is a consonant?', ['A', 'O', 'U', 'M'], 'M'),
                    $this->fib('The letter after C is ___.', 'D'),
                    $this->fib('The first letter of the alphabet is ___.', 'A'),
                ]),

                $this->lesson('Numbers 1-20', '<h3>Numbers 1-20</h3><p>one, two, three, four, five, six, seven, eight, nine, ten, eleven, twelve, thirteen, fourteen, fifteen, sixteen, seventeen, eighteen, nineteen, twenty.</p>', [
                    $this->mc('What is "7" in English?', ['Six', 'Seven', 'Eight', 'Seventeen'], 'Seven'),
                    $this->mc('Which number is "fifteen"?', ['5', '50', '15', '13'], '15'),
                    $this->mc('What is "twelve" in numbers?', ['2', '20', '12', '21'], '12'),
                    $this->fib('Ten plus ten is ___.', 'twenty'),
                    $this->fib('Five plus four is ___.', 'nine'),
                ]),

                $this->lesson('Personal Pronouns', '<h3>Personal Pronouns</h3><ul><li>I (saya)</li><li>You (kamu/kalian)</li><li>He (dia laki-laki)</li><li>She (dia perempuan)</li><li>It (benda/hewan)</li><li>We (kami/kita)</li><li>They (mereka)</li></ul>', [
                    $this->mc('Which pronoun is used for a woman?', ['He', 'She', 'It', 'They'], 'She'),
                    $this->mc('"Kami" in English is...', ['They', 'You', 'We', 'I'], 'We'),
                    $this->mc('Which pronoun is used for a cat?', ['He', 'She', 'It', 'We'], 'It'),
                    $this->fib('Budi is my brother. ___ is a student. (He/She)', 'He'),
                    $this->fib('Rina and Dita are my friends. ___ are kind. (They/We)', 'They'),
                ]),

                $this->lesson('Verb "To Be": am, is, are', '<h3>To Be</h3><ul><li>I <b>am</b></li><li>He / She / It <b>is</b></li><li>You / We / They <b>are</b></li></ul><p>Contoh: I am a student. She is a teacher. They are friends.</p>', [
                    $this->mc('I ___ a student.', ['is', 'are', 'am', 'be'], 'am'),
                    $this->mc('They ___ from Indonesia.', ['is', 'are', 'am', 'was'], 'are'),
                    $this->mc('He ___ my father.', ['am', 'are', 'is', 'be'], 'is'),
                    $this->fib('She ___ a doctor.', 'is'),
                    $this->fib('We ___ happy today.', 'are'),
                ]),

                $this->lesson('Introducing Yourself', '<h3>Introducing Yourself</h3><ul><li>My name is Rina.</li><li>I am 20 years old.</li><li>I am from Jakarta.</li><li>Nice to meet you.</li></ul>', [
                    $this->mc('Which sentence introduces your name?', ['I am fine.', 'My name is Rina.', 'See you later.', 'Thank you.'], 'My name is Rina.'),
                    $this->mc('The reply to "Nice to meet you" is...', ['Nice to meet you too.', 'Good night.', 'I am 20.', 'Sorry.'], 'Nice to meet you too.'),
                    $this->mc('Which sentence tells your age?', ['I am from Bali.', 'I am 20 years old.', 'I am a teacher.', 'I am happy.'], 'I am 20 years old.'),
                    $this->fib('I ___ from Jakarta.', 'am'),
                    $this->fib('My ___ is Budi. (nama)', 'name'),
                ]),

                $this->lesson('Countries and Nationalities', '<h3>Countries and Nationalities</h3><ul><li>Indonesia: Indonesian</li><li>Japan: Japanese</li><li>America: American</li><li>England: English</li><li>China: Chinese</li></ul>', [
                    $this->mc('A person from Japan is...', ['Japan', 'Japanese', 'Japanian', 'Japaner'], 'Japanese'),
                    $this->mc('"Indonesian" is a...', ['Country', 'Nationality', 'City', 'Color'], 'Nationality'),
                    $this->mc('A person from China is...', ['Chinaese', 'Chinese', 'Chinan', 'Chinian'], 'Chinese'),
                    $this->fib('She is from America. She is ___.', 'American'),
                    $this->fib('He is from Indonesia. He is ___.', 'Indonesian'),
                ]),

                $this->lesson('Family Members', '<h3>Family</h3><ul><li>father, mother</li><li>brother, sister</li><li>grandfather, grandmother</li><li>uncle, aunt</li><li>parents (ayah dan ibu)</li></ul>', [
                    $this->mc('Your father\'s brother is your...', ['Uncle', 'Cousin', 'Aunt', 'Grandfather'], 'Uncle'),
                    $this->mc('"Nenek" in English is...', ['Grandfather', 'Grandmother', 'Aunt', 'Sister'], 'Grandmother'),
                    $this->mc('Your mother\'s sister is your...', ['Aunt', 'Uncle', 'Sister', 'Mother'], 'Aunt'),
                    $this->fib('My mother and father are my ___.', 'parents'),
                    $this->fib('My father\'s father is my ___.', 'grandfather'),
                ]),

                $this->lesson('Colors', '<h3>Colors</h3><p>red, blue, green, yellow, black, white, orange, purple, pink, brown.</p>', [
                    $this->mc('The sky is usually...', ['Green', 'Blue', 'Black', 'Pink'], 'Blue'),
                    $this->mc('Which color is "hitam"?', ['White', 'Brown', 'Black', 'Purple'], 'Black'),
                    $this->mc('Grass is usually...', ['Red', 'Green', 'Yellow', 'White'], 'Green'),
                    $this->fib('A banana is ___. (kuning)', 'yellow'),
                    $this->fib('Snow is ___. (putih)', 'white'),
                ]),

                $this->lesson('Days of the Week', '<h3>Days of the Week</h3><p>Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday.</p><p>Weekend adalah Saturday dan Sunday.</p>', [
                    $this->mc('Which day comes after Monday?', ['Sunday', 'Tuesday', 'Wednesday', 'Friday'], 'Tuesday'),
                    $this->mc('Which days are the weekend?', ['Monday and Tuesday', 'Friday and Saturday', 'Saturday and Sunday', 'Sunday and Monday'], 'Saturday and Sunday'),
                    $this->mc('How many days are in a week?', ['5', '6', '7', '8'], '7'),
                    $this->fib('The day before Friday is ___.', 'Thursday'),
                    $this->fib('The day after Saturday is ___.', 'Sunday'),
                ]),

                $this->lesson('Months of the Year', '<h3>Months of the Year</h3><p>January, February, March, April, May, June, July, August, September, October, November, December.</p>', [
                    $this->mc('How many months are in a year?', ['10', '11', '12', '13'], '12'),
                    $this->mc('Which month comes after March?', ['February', 'April', 'May', 'June'], 'April'),
                    $this->mc('Which month comes before December?', ['October', 'November', 'January', 'September'], 'November'),
                    $this->fib('The first month of the year is ___.', 'January'),
                    $this->fib('The month after July is ___.', 'August'),
                ]),

                $this->lesson('Articles: a, an, the', '<h3>Articles</h3><ul><li><b>a</b> dipakai sebelum bunyi konsonan: a book</li><li><b>an</b> dipakai sebelum bunyi vokal: an apple</li><li><b>the</b> dipakai untuk sesuatu yang spesifik: the sun</li></ul>', [
                    $this->mc('I have ___ apple.', ['a', 'an', 'the', 'two'], 'an'),
                    $this->mc('She has ___ cat.', ['a', 'an', 'is', 'are'], 'a'),
                    $this->mc('___ sun is very hot today.', ['A', 'An', 'The', 'Some'], 'The'),
                    $this->fib('He is ___ engineer.', 'an'),
                    $this->fib('This is ___ pen.', 'a'),
                ]),

                $this->lesson('Singular and Plural Nouns', '<h3>Plural Nouns</h3><ul><li>Umumnya tambah s: book jadi books</li><li>Akhiran s, x, ch, sh tambah es: box jadi boxes</li><li>Tidak beraturan: child jadi children, man jadi men</li></ul>', [
                    $this->mc('The plural of "book" is...', ['Bookes', 'Books', 'Bookies', 'Book'], 'Books'),
                    $this->mc('The plural of "child" is...', ['Childs', 'Childes', 'Children', 'Childrens'], 'Children'),
                    $this->mc('The plural of "man" is...', ['Mans', 'Men', 'Manes', 'Mens'], 'Men'),
                    $this->fib('One box, two ___.', 'boxes'),
                    $this->fib('One cat, three ___.', 'cats'),
                ]),

                $this->lesson('This, That, These, Those', '<h3>Demonstratives</h3><ul><li><b>This</b>: satu benda, dekat</li><li><b>That</b>: satu benda, jauh</li><li><b>These</b>: banyak benda, dekat</li><li><b>Those</b>: banyak benda, jauh</li></ul>', [
                    $this->mc('___ is my pen. (dekat, satu)', ['That', 'These', 'This', 'Those'], 'This'),
                    $this->mc('___ are my shoes. (dekat, banyak)', ['This', 'These', 'That', 'Those'], 'These'),
                    $this->mc('___ are birds in the sky. (jauh, banyak)', ['This', 'That', 'These', 'Those'], 'Those'),
                    $this->fib('___ is a mountain over there. (jauh, satu)', 'That'),
                    $this->fib('___ are my books here on the table. (dekat, banyak)', 'These'),
                ]),

                $this->lesson('Unit 1 Review', '<h3>Review Unit 1</h3><p>Ulasan Unit 1: greetings, alphabet, numbers, pronouns, to be, introductions, nationalities, family, colors, days, months, articles, plurals, dan demonstratives.</p>', [
                    $this->mc('Choose the correct sentence.', ['She am a teacher.', 'She is a teacher.', 'She are a teacher.', 'She be a teacher.'], 'She is a teacher.', 'intermediate'),
                    $this->mc('Choose the correct sentence.', ['I have a orange.', 'I have an orange.', 'I have the oranges.', 'I have an oranges.'], 'I have an orange.', 'intermediate'),
                    $this->mc('Which day is part of the weekend?', ['Monday', 'Wednesday', 'Sunday', 'Thursday'], 'Sunday', 'intermediate'),
                    $this->fib('We ___ students. (to be)', 'are'),
                    $this->fib('Two ___ are playing in the yard. (child, plural)', 'children', 'intermediate'),
                ], 30),
            ]
        ];
    }
}
