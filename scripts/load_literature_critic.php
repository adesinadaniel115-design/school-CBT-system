<?php

$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';

$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Subject;
use App\Models\Question;

$subject = Subject::firstOrCreate(['name' => 'LITERATURE IN ENGLISH']);

$rows = [
    ['question_text' => '"The sun was a toddler, crawling across the sky." This line is an example of:',
     'option_a' => 'Simile',
     'option_b' => 'Personification',
     'option_c' => 'Metaphor',
     'option_d' => 'Oxymoron',
     'correct_option' => 'C',
     'explanation' => 'While it attributes human action (crawling), it is a direct comparison without using "like" or "as." It equates the sun to a toddler, making it a metaphor.'],
    ['question_text' => 'In a dramatic performance, a "Soliloquy" is primarily used by the playwright to:',
     'option_a' => 'Provide comic relief between scenes.',
     'option_b' => 'Reveal a character\'s innermost thoughts to the audience.',
     'option_c' => 'Allow characters to plot against the protagonist.',
     'option_d' => 'Summarize the plot at the end of the act.',
     'correct_option' => 'B',
     'explanation' => 'A soliloquy occurs when a character speaks to themselves while alone, letting the audience in on their private motivations.'],
    ['question_text' => '"He was a brave coward during the battle." This sentence contains a/an:',
     'option_a' => 'Paradox',
     'option_b' => 'Onomatopoeia',
     'option_c' => 'Pun',
     'option_d' => 'Hyperbole',
     'correct_option' => 'A',
     'explanation' => 'A paradox is a statement that appears self-contradictory but reveals a deeper truth (e.g., a man acting brave while feeling immense fear).'],
    ['question_text' => 'In poetry, the repetition of consonant sounds at the beginning of words in close proximity is called:',
     'option_a' => 'Assonance',
     'option_b' => 'Consonance',
     'option_c' => 'Alliteration',
     'option_d' => 'Enjambment',
     'correct_option' => 'C',
     'explanation' => 'Alliteration specifically refers to the initial sounds (e.g., "The serpent slithered silently").'],
    ['question_text' => 'When a story begins "In Media Res," it means the narrative starts:',
     'option_a' => 'At the very end of the events.',
     'option_b' => 'In the middle of the action.',
     'option_c' => 'With a detailed description of the setting.',
     'option_d' => 'With the birth of the protagonist.',
     'correct_option' => 'B',
     'explanation' => 'In media res is a Latin term used in epics and novels to hook the reader immediately with conflict.'],
    ['question_text' => 'The use of "Dramatic Irony" occurs when:',
     'option_a' => 'The characters know more than the audience.',
     'option_b' => 'The audience knows something that the characters do not.',
     'option_c' => 'The play ends in a tragic death.',
     'option_d' => 'Every character tells the truth.',
     'correct_option' => 'B',
     'explanation' => 'Dramatic irony builds tension (e.g., the audience knows a killer is in the room, but the character is calmly drinking tea).'],
    ['question_text' => 'A poem consisting of fourteen lines with a specific rhyme scheme is a/an:',
     'option_a' => 'Ode',
     'option_b' => 'Elegy',
     'option_c' => 'Sonnet',
     'option_d' => 'Ballad',
     'correct_option' => 'C',
     'explanation' => 'Whether Shakespearean or Petrarchan, a sonnet must have exactly 14 lines.'],
    ['question_text' => '"O Death, where is thy sting?" is an example of:',
     'option_a' => 'Apostrophe',
     'option_b' => 'Euphemism',
     'option_c' => 'Metonymy',
     'option_d' => 'Synecdoche',
     'correct_option' => 'A',
     'explanation' => 'Apostrophe is a figure of speech where the speaker addresses an absent person, an abstract idea, or a non-human object.'],
    ['question_text' => 'The term "Hubris" in Greek tragedy refers to:',
     'option_a' => 'The protagonist\'s ultimate victory.',
     'option_b' => 'The use of masks by the chorus.',
     'option_c' => 'Excessive pride that leads to a hero\'s downfall.',
     'option_d' => 'The resolution of the conflict.',
     'correct_option' => 'C',
     'explanation' => 'Hubris is a common "tragic flaw" (hamartia) found in characters like Oedipus or Okonkwo.'],
    ['question_text' => 'A "Farce" is a type of comedy characterized by:',
     'option_a' => 'Sad endings and moral lessons.',
     'option_b' => 'Exaggerated physical humor and improbable situations.',
     'option_c' => 'Intellectual wit and social satire.',
     'option_d' => 'The use of supernatural elements.',
     'correct_option' => 'B',
     'explanation' => 'Farce aims solely for laughter through absurdity and "slapstick" comedy.'],
];

foreach ($rows as $row) {
    // skip duplicates if already exist
    $exists = Question::where('subject_id', $subject->id)
        ->where('question_text', $row['question_text'])
        ->exists();
    if ($exists) {
        echo "skipped duplicate: " . substr($row['question_text'], 0, 60) . "...\n";
        continue;
    }

    Question::create(array_merge($row, [
        'subject_id' => $subject->id,
        'difficulty_level' => 'hard',
    ]));
    echo "inserted: " . substr($row['question_text'], 0, 60) . "...\n";
}

$count = Question::where('subject_id', $subject->id)->count();
echo "Done loading literature questions. total now: {$count}\n";
