<?php

namespace Database\Seeders;

use App\Enums\QuestionTypeEnum;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionOption;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class EnglishContentSeeder extends Seeder
{
    public function run(): void
    {
        // Course::query()->delete();

        $course = Course::query()->firstOrCreate(
            ['title' => 'Complete English Mastery'],
            [
                'description' => 'Master English from absolute beginner (A1) to expert (C2). Structured curriculum covering grammar, vocabulary, conversation, and professional communication.',
                'language_target' => 'English',
                'level' => 'beginner',
                'is_active' => true,
            ]
        );

        $units = $this->getUnits();

        foreach ($units as $unitIndex => $unitData) {
            $unit = Unit::query()->firstOrCreate(
                ['course_id' => $course->id, 'order' => $unitIndex + 1],
                ['title' => $unitData['title']]
            );

            foreach ($unitData['lessons'] as $lessonIndex => $lessonData) {
                $lesson = Lesson::query()->updateOrCreate(
                    ['unit_id' => $unit->id, 'order' => $lessonIndex + 1],
                    [
                        'title' => $lessonData['title'],
                        'type' => $lessonData['type'] ?? 'reading',
                        'xp_reward' => $lessonData['xp'] ?? 20,
                        'explanation' => $lessonData['explanation'],
                    ]
                );

                foreach ($lessonData['questions'] as $questionIndex => $questionData) {
                    $question = Question::query()->updateOrCreate(
                        ['lesson_id' => $lesson->id, 'order' => $questionIndex + 1],
                        [
                            'type' => $questionData['type'],
                            'difficulty_level' => $questionData['difficulty'],
                            'question_text' => $questionData['text'],
                        ]
                    );

                    if ($questionData['type'] === QuestionTypeEnum::FillInTheBlank->value) {
                        QuestionAnswer::query()->updateOrCreate(
                            ['question_id' => $question->id],
                            ['correct_text' => $questionData['answer']]
                        );
                    } else {
                        QuestionOption::where('question_id', $question->id)->delete();
                        foreach ($questionData['options'] as $opt) {
                            QuestionOption::query()->create([
                                'question_id' => $question->id,
                                'option_text' => $opt['text'],
                                'is_correct' => $opt['correct'],
                            ]);
                        }
                    }
                }
            }
        }
    }

    private function getUnits(): array
    {
        return [
            [
                'title' => 'Unit 1: Foundations & Greetings',
                'lessons' => [
                    [
                        'title' => 'Saying Hello',
                        'type' => 'reading',
                        'xp' => 20,
                        'explanation' =>
<<<HTML
    <h3>Greetings in English</h3>

    <p>
        There are many ways to say hello in English depending on the time of day and formality:
    </p>

    <ul>
        <li><strong>Hello</strong> - universal, works anytime</li>
        <li><strong>Hi</strong> - informal and friendly</li>
        <li><strong>Good morning</strong> - before noon</li>
        <li><strong>Good afternoon</strong> - noon to evening</li>
        <li><strong>Good evening</strong> - after sunset</li>
    </ul>
    HTML,
                        'questions' => [
                            ['type' => 'multiple_choice', 'difficulty' => 'beginner', 'text' => 'Which greeting is the most formal?', 'options' => [['text' => 'Hey', 'correct' => false], ['text' => 'Yo', 'correct' => false], ['text' => 'Good morning', 'correct' => true], ['text' => 'Sup', 'correct' => false]]],
                            ['type' => 'multiple_choice', 'difficulty' => 'beginner', 'text' => 'When do you say "Good evening"?', 'options' => [['text' => 'Before noon', 'correct' => false], ['text' => 'After sunset', 'correct' => true], ['text' => 'At midnight', 'correct' => false], ['text' => 'In the morning', 'correct' => false]]],
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'beginner', 'text' => 'Complete: "Good _____, how are you today?" (used before noon)', 'answer' => 'morning'],
                        ],
                    ],
                    [
                        'title' => 'Introducing Yourself',
                        'type' => 'reading',
                        'xp' => 20,
                        'explanation' =>
<<<HTML
    <h3>Talking About Yourself</h3>

    <p>When meeting someone new:</p>

    <ul>
        <li><strong>My name is...</strong> / <strong>I'm...</strong></li>
        <li><strong>I'm from...</strong> (country/city)</li>
        <li><strong>I live in...</strong></li>
        <li><strong>I'm a student / worker</strong></li>
    </ul>
HTML,
                        'questions' => [
                            ['type' => 'multiple_choice', 'difficulty' => 'beginner', 'text' => 'Which phrase introduces your name?', 'options' => [['text' => 'I like', 'correct' => false], ['text' => 'My name is', 'correct' => true], ['text' => 'I go', 'correct' => false], ['text' => 'I have', 'correct' => false]]],
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'beginner', 'text' => 'Complete: "I _____ from Japan."', 'answer' => 'am'],
                            ['type' => 'multiple_choice', 'difficulty' => 'beginner', 'text' => '"I live in Jakarta" tells people...', 'options' => [['text' => 'Your job', 'correct' => false], ['text' => 'Your city', 'correct' => true], ['text' => 'Your age', 'correct' => false], ['text' => 'Your hobby', 'correct' => false]]],
                        ],
                    ],
                    [
                        'title' => 'Saying Goodbye',
                        'type' => 'reading',
                        'xp' => 20,
                        'explanation' =>
<<<'HTML'
    <h3>Ways to Say Goodbye</h3>

    <ul>
        <li><strong>Goodbye</strong> - formal</li>
        <li><strong>Bye</strong> - informal</li>
        <li><strong>See you later</strong> - casual</li>
        <li><strong>Have a nice day</strong> - polite</li>
    </ul>
HTML,
                        'questions' => [
                            ['type' => 'multiple_choice', 'difficulty' => 'beginner', 'text' => 'Which goodbye is the most formal?', 'options' => [['text' => 'Bye', 'correct' => false], ['text' => 'See ya', 'correct' => false], ['text' => 'Goodbye', 'correct' => true], ['text' => 'Later', 'correct' => false]]],
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'beginner', 'text' => 'Complete: "See you _____!" (meaning you will meet tomorrow)', 'answer' => 'tomorrow'],
                            ['type' => 'multiple_choice', 'difficulty' => 'beginner', 'text' => '"Take care" is used to...', 'options' => [['text' => 'Start a conversation', 'correct' => false], ['text' => 'Say goodbye warmly', 'correct' => true], ['text' => 'Ask for help', 'correct' => false], ['text' => 'Introduce yourself', 'correct' => false]]],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Unit 2: Daily Life & Routines',
                'lessons' => [
                    [
                        'title' => 'Morning Routine',
                        'type' => 'reading',
                        'xp' => 20,
                        'explanation' =>
<<<'HTML'
    <h3>Morning Activities</h3>
    <ul>
        <li><strong>wake up</strong> - stop sleeping</li>
        <li><strong>take a shower</strong> - wash body</li>
        <li><strong>brush teeth</strong> - clean teeth</li>
        <li><strong>have breakfast</strong> - eat morning meal</li>
    </ul>
HTML,
                        'questions' => [
                            ['type' => 'multiple_choice', 'difficulty' => 'beginner', 'text' => 'What do you do right after you wake up?', 'options' => [['text' => 'Get up from bed', 'correct' => true], ['text' => 'Have dinner', 'correct' => false], ['text' => 'Go to sleep', 'correct' => false], ['text' => 'Watch TV', 'correct' => false]]],
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'beginner', 'text' => 'Complete: "I _____ my teeth every morning."', 'answer' => 'brush'],
                            ['type' => 'multiple_choice', 'difficulty' => 'beginner', 'text' => '"Get dressed" means...', 'options' => [['text' => 'Take off clothes', 'correct' => false], ['text' => 'Put on clothes', 'correct' => true], ['text' => 'Buy clothes', 'correct' => false], ['text' => 'Wash clothes', 'correct' => false]]],
                        ],
                    ],
                    [
                        'title' => 'Work & School',
                        'type' => 'reading',
                        'xp' => 20,
                        'explanation' =>
<<<'HTML'
    <h3>Work and School</h3>
    <ul>
        <li><strong>go to work/school</strong></li>
        <li><strong>have a meeting</strong></li>
        <li><strong>finish work</strong></li>
        <li><strong>come home</strong></li>
    </ul>
HTML,
                        'questions' => [
                            ['type' => 'multiple_choice', 'difficulty' => 'beginner', 'text' => 'What does "finish work" mean?', 'options' => [['text' => 'Start working', 'correct' => false], ['text' => 'Stop working', 'correct' => true], ['text' => 'Go to work', 'correct' => false], ['text' => 'Take a break', 'correct' => false]]],
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'beginner', 'text' => 'Complete: "I _____ to school every day."', 'answer' => 'go'],
                            ['type' => 'multiple_choice', 'difficulty' => 'beginner', 'text' => 'When you "have a meeting", you...', 'options' => [['text' => 'Eat lunch', 'correct' => false], ['text' => 'Sleep', 'correct' => false], ['text' => 'Discuss things with colleagues', 'correct' => true], ['text' => 'Go shopping', 'correct' => false]]],
                        ],
                    ],
                    [
                        'title' => 'Evening & Bedtime',
                        'type' => 'reading',
                        'xp' => 20,
                        'explanation' =>
<<<'HTML'
    <h3>Evening Activities</h3>
    <ul>
        <li><strong>have dinner</strong></li>
        <li><strong>watch TV</strong></li>
        <li><strong>go to bed</strong></li>
        <li><strong>fall asleep</strong></li>
    </ul>
HTML,
                        'questions' => [
                            ['type' => 'multiple_choice', 'difficulty' => 'beginner', 'text' => 'What meal do you eat in the evening?', 'options' => [['text' => 'Breakfast', 'correct' => false], ['text' => 'Lunch', 'correct' => false], ['text' => 'Dinner', 'correct' => true], ['text' => 'Snack', 'correct' => false]]],
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'beginner', 'text' => 'Complete: "I _____ to bed at 10 PM."', 'answer' => 'go'],
                            ['type' => 'multiple_choice', 'difficulty' => 'beginner', 'text' => '"Fall asleep" means...', 'options' => [['text' => 'Wake up', 'correct' => false], ['text' => 'Start sleeping', 'correct' => true], ['text' => 'Get up from bed', 'correct' => false], ['text' => 'Have a dream', 'correct' => false]]],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Unit 3: Food & Shopping',
                'lessons' => [
                    [
                        'title' => 'Common Foods',
                        'type' => 'reading',
                        'xp' => 20,
                        'explanation' =>
<<<'HTML'
    <h3>Food Vocabulary</h3>
    <ul>
        <li><strong>Fruits:</strong> apple, banana, orange</li>
        <li><strong>Vegetables:</strong> tomato, potato, carrot</li>
        <li><strong>Grains:</strong> rice, bread, pasta</li>
    </ul>
HTML,
                        'questions' => [
                            ['type' => 'multiple_choice', 'difficulty' => 'beginner', 'text' => 'Which is a fruit?', 'options' => [['text' => 'Potato', 'correct' => false], ['text' => 'Carrot', 'correct' => false], ['text' => 'Banana', 'correct' => true], ['text' => 'Onion', 'correct' => false]]],
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'beginner', 'text' => 'Complete: "I would like some _____." (a grain, white and fluffy)', 'answer' => 'rice'],
                            ['type' => 'multiple_choice', 'difficulty' => 'beginner', 'text' => 'Which is a vegetable?', 'options' => [['text' => 'Apple', 'correct' => false], ['text' => 'Chicken', 'correct' => false], ['text' => 'Broccoli', 'correct' => true], ['text' => 'Bread', 'correct' => false]]],
                        ],
                    ],
                    [
                        'title' => 'Ordering Food',
                        'type' => 'reading',
                        'xp' => 20,
                        'explanation' => <<<'HTML'
    <h3>At a Restaurant</h3>
    <ul>
        <li><strong>I would like...</strong></li>
        <li><strong>Can I have...?</strong></li>
        <li><strong>Could I get the bill, please?</strong></li>
    </ul>
HTML,
                        'questions' => [
                            ['type' => 'multiple_choice', 'difficulty' => 'beginner', 'text' => 'How do you politely order food?', 'options' => [['text' => 'Give me food!', 'correct' => false], ['text' => 'I would like a chicken salad.', 'correct' => true], ['text' => 'Food now!', 'correct' => false], ['text' => 'Want chicken.', 'correct' => false]]],
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'beginner', 'text' => 'Complete: "Could I _____ the bill, please?"', 'answer' => 'get'],
                            ['type' => 'multiple_choice', 'difficulty' => 'beginner', 'text' => '"What do you recommend?" is used to...', 'options' => [['text' => 'Order food', 'correct' => false], ['text' => 'Ask for suggestions', 'correct' => true], ['text' => 'Pay the bill', 'correct' => false], ['text' => 'Leave the restaurant', 'correct' => false]]],
                        ],
                    ],
                    [
                        'title' => 'Prices & Money',
                        'type' => 'reading',
                        'xp' => 20,
                        'explanation' => <<<'HTML'
    <h3>Prices</h3>
    <ul>
        <li><strong>How much is this?</strong></li>
        <li><strong>It costs...</strong></li>
        <li><strong>Can I pay by card?</strong></li>
    </ul>
HTML,
                        'questions' => [
                            ['type' => 'multiple_choice', 'difficulty' => 'beginner', 'text' => 'How do you ask about price?', 'options' => [['text' => 'How many is this?', 'correct' => false], ['text' => 'How much is this?', 'correct' => true], ['text' => 'How old is this?', 'correct' => false], ['text' => 'How long is this?', 'correct' => false]]],
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'beginner', 'text' => 'Complete: "Can I pay _____ card?"', 'answer' => 'by'],
                            ['type' => 'multiple_choice', 'difficulty' => 'beginner', 'text' => '"I will take it" means...', 'options' => [['text' => 'I want to buy it', 'correct' => true], ['text' => 'I want to return it', 'correct' => false], ['text' => 'I don\'t want it', 'correct' => false], ['text' => 'It is too expensive', 'correct' => false]]],
                        ],
                    ],
                ],
            ],

            [
                'title' => 'Unit 4: Travel & Directions',
                'lessons' => [
                    [
                        'title' => 'At the Airport',
                        'type' => 'reading',
                        'xp' => 30,
                        'explanation' => <<<'HTML'
    <h3>Airport Vocabulary</h3>
    <ul>
        <li><strong>Boarding pass</strong> ticket to board</li>
        <li><strong>Gate</strong> where you board</li>
        <li><strong>Luggage</strong> bags</li>
    </ul>
HTML,
                        'questions' => [
                            ['type' => 'multiple_choice', 'difficulty' => 'intermediate', 'text' => 'Where do you board the plane?', 'options' => [['text' => 'Customs', 'correct' => false], ['text' => 'Check-in counter', 'correct' => false], ['text' => 'Gate', 'correct' => true], ['text' => 'Lounge', 'correct' => false]]],
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'intermediate', 'text' => 'Complete: "I need to check in my _____ before the flight."', 'answer' => 'luggage'],
                            ['type' => 'multiple_choice', 'difficulty' => 'intermediate', 'text' => 'A layover means...', 'options' => [['text' => 'Flight cancelled', 'correct' => false], ['text' => 'A stop between two flights', 'correct' => true], ['text' => 'Lost luggage', 'correct' => false], ['text' => 'Missed flight', 'correct' => false]]],
                        ],
                    ],
                    [
                        'title' => 'Asking Directions',
                        'type' => 'reading',
                        'xp' => 30,
                        'explanation' => <<<'HTML'
    <h3>Directions</h3>
    <ul>
        <li><strong>Turn left / right</strong></li>
        <li><strong>Go straight</strong></li>
        <li><strong>Across from / next to</strong></li>
    </ul>
HTML,
                        'questions' => [
                            ['type' => 'multiple_choice', 'difficulty' => 'intermediate', 'text' => '"Go straight" means...', 'options' => [['text' => 'Turn around', 'correct' => false], ['text' => 'Continue forward without turning', 'correct' => true], ['text' => 'Stop walking', 'correct' => false], ['text' => 'Walk backward', 'correct' => false]]],
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'intermediate', 'text' => 'Complete: "Turn _____ at the next intersection."', 'answer' => 'left'],
                            ['type' => 'multiple_choice', 'difficulty' => 'intermediate', 'text' => '"Across from the bank" means...', 'options' => [['text' => 'Inside the bank', 'correct' => false], ['text' => 'On the opposite side of the street', 'correct' => true], ['text' => 'Behind the bank', 'correct' => false], ['text' => 'Same side as the bank', 'correct' => false]]],
                        ],
                    ],
                    [
                        'title' => 'Hotel Booking',
                        'type' => 'reading',
                        'xp' => 30,
                        'explanation' => <<<'HTML'
    <h3>Hotel Stay</h3>
    <ul>
        <li><strong>Reservation</strong> booking</li>
        <li><strong>Checkout</strong> leaving time</li>
        <li><strong>Wake-up call</strong> morning phone call</li>
    </ul>
HTML,
                        'questions' => [
                            ['type' => 'multiple_choice', 'difficulty' => 'intermediate', 'text' => 'When you arrive at a hotel, you say...', 'options' => [['text' => 'I have a reservation under Smith.', 'correct' => true], ['text' => 'I want to leave now.', 'correct' => false], ['text' => 'Where is the airport?', 'correct' => false], ['text' => 'Give me food.', 'correct' => false]]],
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'intermediate', 'text' => 'Complete: "What time is _____?" (when you leave)', 'answer' => 'checkout'],
                            ['type' => 'multiple_choice', 'difficulty' => 'intermediate', 'text' => 'A wake-up call is...', 'options' => [['text' => 'Friend call', 'correct' => false], ['text' => 'Front desk call to wake you up', 'correct' => true], ['text' => 'Check out call', 'correct' => false], ['text' => 'Room service call', 'correct' => false]]],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Unit 5: Past & Future',
                'lessons' => [
                    [
                        'title' => 'Past Simple Regular & Irregular',
                        'type' => 'reading',
                        'xp' => 30,
                        'explanation' => <<<'HTML'
    <h3>Past Tense</h3>
    <p>Regular verbs add -ed. Irregular verbs change form (go -> went, eat -> ate).</p>
HTML,
                        'questions' => [
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'intermediate', 'text' => 'Complete: "I _____ to the park yesterday." (go -> past)', 'answer' => 'went'],
                            ['type' => 'multiple_choice', 'difficulty' => 'intermediate', 'text' => 'What is the past tense of "eat"?', 'options' => [['text' => 'Eated', 'correct' => false], ['text' => 'Eat', 'correct' => false], ['text' => 'Ate', 'correct' => true], ['text' => 'Eaten', 'correct' => false]]],
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'intermediate', 'text' => 'Complete: "We _____ a movie last night." (see -> past)', 'answer' => 'saw'],
                        ],
                    ],
                    [
                        'title' => 'Going to Future',
                        'type' => 'reading',
                        'xp' => 30,
                        'explanation' => <<<'HTML'
    <h3>Future Plans</h3>
    <p>Use "am/is/are + going to + verb" for planned future actions.</p>
HTML,
                        'questions' => [
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'intermediate', 'text' => 'Complete: "I _____ going to travel next summer."', 'answer' => 'am'],
                            ['type' => 'multiple_choice', 'difficulty' => 'intermediate', 'text' => '"We are going to move" means...', 'options' => [['text' => 'We moved already', 'correct' => false], ['text' => 'We plan to move in the future', 'correct' => true], ['text' => 'We don\'t want to move', 'correct' => false], ['text' => 'We forgot to move', 'correct' => false]]],
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'intermediate', 'text' => 'Complete: "She _____ going to start a new course."', 'answer' => 'is'],
                        ],
                    ],
                    [
                        'title' => 'Will for Predictions',
                        'type' => 'reading',
                        'xp' => 30,
                        'explanation' => <<<'HTML'
    <h3>Will vs Going to</h3>
    <p>Use "will" for quick decisions and predictions.</p>
HTML,
                        'questions' => [
                            ['type' => 'multiple_choice', 'difficulty' => 'intermediate', 'text' => 'Which is a prediction?', 'options' => [['text' => 'I am going to cook dinner.', 'correct' => false], ['text' => 'It will rain tomorrow.', 'correct' => true], ['text' => 'I will help you right now.', 'correct' => false], ['text' => 'We are going to the park.', 'correct' => false]]],
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'intermediate', 'text' => 'Complete: "I think it _____ be a sunny day."', 'answer' => 'will'],
                            ['type' => 'multiple_choice', 'difficulty' => 'intermediate', 'text' => '"I will help you" is a...', 'options' => [['text' => 'Planned action', 'correct' => false], ['text' => 'Spontaneous decision', 'correct' => true], ['text' => 'Past event', 'correct' => false], ['text' => 'Question', 'correct' => false]]],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Unit 6: Professional Emails',
                'lessons' => [
                    [
                        'title' => 'Email Structure',
                        'type' => 'reading',
                        'xp' => 40,
                        'explanation' => <<<'HTML'
    <h3>Professional Email</h3>
    <p>Subject line, clear greeting, purpose in first paragraph, professional sign-off.</p>
HTML,
                        'questions' => [
                            ['type' => 'multiple_choice', 'difficulty' => 'advanced', 'text' => 'What should a good email subject line be?', 'options' => [['text' => 'Very long and detailed', 'correct' => false], ['text' => 'Short, clear, and specific', 'correct' => true], ['text' => 'Left blank', 'correct' => false], ['text' => 'Vague like "Question"', 'correct' => false]]],
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'advanced', 'text' => 'Complete: "Best _____," (common email sign-off)', 'answer' => 'regards'],
                            ['type' => 'multiple_choice', 'difficulty' => 'advanced', 'text' => 'The first paragraph of a professional email should...', 'options' => [['text' => 'Tell a story', 'correct' => false], ['text' => 'State your purpose immediately', 'correct' => true], ['text' => 'Ask personal questions', 'correct' => false], ['text' => 'Complain about something', 'correct' => false]]],
                        ],
                    ],
                    [
                        'title' => 'Formal vs Informal Tone',
                        'type' => 'reading',
                        'xp' => 40,
                        'explanation' => <<<'HTML'
    <h3>Tone in Emails</h3>
    <p>Use formal language for new clients and managers. Use "I wanted to follow up" instead of "Just checking in".</p>
HTML,
                        'questions' => [
                            ['type' => 'multiple_choice', 'difficulty' => 'advanced', 'text' => 'Which is more formal?', 'options' => [['text' => 'Hey, what is up?', 'correct' => false], ['text' => 'I wanted to follow up on our discussion.', 'correct' => true], ['text' => 'Yo, did you see my email?', 'correct' => false], ['text' => 'LOL, that is funny!', 'correct' => false]]],
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'advanced', 'text' => 'Complete: "Please _____ me know if you have any questions."', 'answer' => 'let'],
                            ['type' => 'multiple_choice', 'difficulty' => 'advanced', 'text' => 'When should you use a formal tone?', 'options' => [['text' => 'Emailing a close friend', 'correct' => false], ['text' => 'Emailing a new client', 'correct' => true], ['text' => 'Sending a meme', 'correct' => false], ['text' => 'Texting your colleague', 'correct' => false]]],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Unit 7: Conditionals & Hypothesis',
                'lessons' => [
                    [
                        'title' => 'First Conditional',
                        'type' => 'reading',
                        'xp' => 40,
                        'explanation' => <<<'HTML'
    <h3>First Conditional</h3>
    <p>If + present simple, will + base verb (real future possibility).</p>
HTML,
                        'questions' => [
                            ['type' => 'multiple_choice', 'difficulty' => 'advanced', 'text' => 'Which is correct for first conditional?', 'options' => [['text' => 'If it will rain, I stay home.', 'correct' => false], ['text' => 'If it rains, I will stay home.', 'correct' => true], ['text' => 'If it rained, I will stay home.', 'correct' => false], ['text' => 'If it rains, I stay home.', 'correct' => false]]],
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'advanced', 'text' => 'Complete: "If you _____ hard, you will pass the exam."', 'answer' => 'study'],
                            ['type' => 'multiple_choice', 'difficulty' => 'advanced', 'text' => 'After "if", we use...', 'options' => [['text' => 'Will + verb', 'correct' => false], ['text' => 'Present simple', 'correct' => true], ['text' => 'Past tense', 'correct' => false], ['text' => 'Future tense', 'correct' => false]]],
                        ],
                    ],
                    [
                        'title' => 'Second Conditional',
                        'type' => 'reading',
                        'xp' => 40,
                        'explanation' => <<<'HTML'
    <h3>Second Conditional</h3>
    <p>If + past simple, would + base verb (unreal/imaginary present).</p>
HTML,
                        'questions' => [
                            ['type' => 'multiple_choice', 'difficulty' => 'advanced', 'text' => 'Second conditional uses "would" because...', 'options' => [['text' => 'It is a real situation', 'correct' => false], ['text' => 'It is an imaginary situation', 'correct' => true], ['text' => 'It happened in the past', 'correct' => false], ['text' => 'It is a question', 'correct' => false]]],
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'advanced', 'text' => 'Complete: "If I _____ rich, I would buy a house."', 'answer' => 'were'],
                            ['type' => 'multiple_choice', 'difficulty' => 'advanced', 'text' => 'Which is a second conditional sentence?', 'options' => [['text' => 'If it rains, I will stay home.', 'correct' => false], ['text' => 'If I were a bird, I would fly.', 'correct' => true], ['text' => 'If I studied, I passed.', 'correct' => false], ['text' => 'If I had studied, I would have passed.', 'correct' => false]]],
                        ],
                    ],
                ],
            ],

            [
                'title' => 'Unit 8: Academic & Legal English',
                'lessons' => [
                    [
                        'title' => 'Academic Essay Structure',
                        'type' => 'reading',
                        'xp' => 50,
                        'explanation' => <<<'HTML'
    <h3>Academic Writing</h3>
    <p>Introduction with thesis statement, body paragraphs with evidence, conclusion summarizing findings.</p>
HTML,
                        'questions' => [
                            ['type' => 'multiple_choice', 'difficulty' => 'advanced', 'text' => 'A "thesis statement" is...', 'options' => [['text' => 'The conclusion of an essay', 'correct' => false], ['text' => 'The main argument or claim of the essay', 'correct' => true], ['text' => 'A type of introduction', 'correct' => false], ['text' => 'A bibliography entry', 'correct' => false]]],
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'advanced', 'text' => 'Complete: "This essay _____ that technology has changed education."', 'answer' => 'argues'],
                            ['type' => 'multiple_choice', 'difficulty' => 'advanced', 'text' => 'The purpose of a conclusion is to...', 'options' => [['text' => 'Introduce new arguments', 'correct' => false], ['text' => 'Restate the thesis and summarize key points', 'correct' => true], ['text' => 'List sources', 'correct' => false], ['text' => 'Ask questions', 'correct' => false]]],
                        ],
                    ],
                    [
                        'title' => 'Contract Terminology',
                        'type' => 'reading',
                        'xp' => 50,
                        'explanation' => <<<'HTML'
    <h3>Legal English</h3>
    <p>Key terms: "hereby", "shall" (obligation), "breach of contract", "terms and conditions".</p>
HTML,
                        'questions' => [
                            ['type' => 'multiple_choice', 'difficulty' => 'advanced', 'text' => 'In legal English, "shall" means...', 'options' => [['text' => 'Maybe', 'correct' => false], ['text' => 'Must (obligation)', 'correct' => true], ['text' => 'Should', 'correct' => false], ['text' => 'Could', 'correct' => false]]],
                            ['type' => 'fill_in_the_blank', 'difficulty' => 'advanced', 'text' => 'Complete: "The parties _____ agree to the terms."', 'answer' => 'hereby'],
                            ['type' => 'multiple_choice', 'difficulty' => 'advanced', 'text' => '"This agreement is binding" means...', 'options' => [['text' => 'It is optional', 'correct' => false], ['text' => 'Both parties must follow it', 'correct' => true], ['text' => 'It can be ignored', 'correct' => false], ['text' => 'It is a suggestion', 'correct' => false]]],
                        ],
                    ],
                ],
            ],
        ];
    }
}
