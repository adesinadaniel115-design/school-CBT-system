<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Get Literature in English subject ID
$literature = DB::table('subjects')
    ->where('name', 'LIKE', '%Literature%')
    ->orWhere('name', 'LIKE', '%LITERATURE%')
    ->first();

if (!$literature) {
    echo "Creating LITERATURE IN ENGLISH subject...\n";
    $literatureId = DB::table('subjects')->insertGetId([
        'name' => 'LITERATURE IN ENGLISH',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
} else {
    $literatureId = $literature->id;
    echo "Literature in English subject found with ID: {$literatureId}\n";
}

echo "\n=== LOADING JAMB LITERATURE IN ENGLISH (40 QUESTIONS) ===\n\n";

$questions = [];

// ======== SECTION A: LITERARY APPRECIATION & TERMS (10 QUESTIONS) ========
$section_a_context = "SECTION A: LITERARY APPRECIATION & TERMS\n\nThis section tests your understanding of literary devices, techniques, and terminology used in analyzing poetry, drama, and prose.";

$questions[] = [
    'passage' => $section_a_context,
    'group' => 'literary_terms',
    'question' => 'A story in which animals are used as characters to teach a moral lesson is:',
    'a' => 'An allegory',
    'b' => 'A fable',
    'c' => 'A parable',
    'd' => 'An epic',
    'answer' => 'B',
    'explanation' => 'Fables (like Aesop\'s) specifically use animals to deliver morals.',
];

$questions[] = [
    'passage' => $section_a_context,
    'group' => 'literary_terms',
    'question' => '"The waves danced to the tune of the wind" is an example of:',
    'a' => 'Hyperbole',
    'b' => 'Personification',
    'c' => 'Metonymy',
    'd' => 'Synecdoche',
    'answer' => 'B',
    'explanation' => 'Giving the human action of "dancing" to waves is personification.',
];

$questions[] = [
    'passage' => $section_a_context,
    'group' => 'literary_terms',
    'question' => 'A poem of fourteen lines with a specific rhyme scheme is a/an:',
    'a' => 'Ode',
    'b' => 'Lyric',
    'c' => 'Sonnet',
    'd' => 'Elegy',
    'answer' => 'C',
    'explanation' => 'Sonnets (Shakespearean or Petrarchan) always have 14 lines.',
];

$questions[] = [
    'passage' => $section_a_context,
    'group' => 'literary_terms',
    'question' => 'The use of a word to suggest its sound is called:',
    'a' => 'Onomatopoeia',
    'b' => 'Paradox',
    'c' => 'Irony',
    'd' => 'Pun',
    'answer' => 'A',
    'explanation' => 'Words like "bang," "hiss," or "splash" mimic real sounds.',
];

$questions[] = [
    'passage' => $section_a_context,
    'group' => 'literary_terms',
    'question' => '"Parting is such sweet sorrow" is an example of:',
    'a' => 'Metaphor',
    'b' => 'Oxymoron',
    'c' => 'Simile',
    'd' => 'Litotes',
    'answer' => 'B',
    'explanation' => '"Sweet" and "Sorrow" are contradictory terms placed together.',
];

$questions[] = [
    'passage' => $section_a_context,
    'group' => 'literary_terms',
    'question' => 'The hero\'s "tragic flaw" that leads to his downfall is known as:',
    'a' => 'Hubris',
    'b' => 'Hamartia',
    'c' => 'Catharsis',
    'd' => 'Nemesis',
    'answer' => 'B',
    'explanation' => 'Hamartia is the technical term for a fatal character flaw.',
];

$questions[] = [
    'passage' => $section_a_context,
    'group' => 'literary_terms',
    'question' => 'A speech made by a character alone on stage to reveal their inner thoughts is:',
    'a' => 'A monologue',
    'b' => 'A dialogue',
    'c' => 'A soliloquy',
    'd' => 'An aside',
    'answer' => 'C',
    'explanation' => 'Unlike a monologue, a soliloquy is strictly for the character\'s private thoughts.',
];

$questions[] = [
    'passage' => $section_a_context,
    'group' => 'literary_terms',
    'question' => '"He is a pillar of the community" is a:',
    'a' => 'Metaphor',
    'b' => 'Simile',
    'c' => 'Euphemism',
    'd' => 'Irony',
    'answer' => 'A',
    'explanation' => 'Direct comparison without using "like" or "as."',
];

$questions[] = [
    'passage' => $section_a_context,
    'group' => 'literary_terms',
    'question' => 'The turning point or highest point of interest in a plot is the:',
    'a' => 'Denouement',
    'b' => 'Exposition',
    'c' => 'Climax',
    'd' => 'Conflict',
    'answer' => 'C',
    'explanation' => 'The climax is the peak of the story\'s tension.',
];

$questions[] = [
    'passage' => $section_a_context,
    'group' => 'literary_terms',
    'question' => 'A play intended to make the audience laugh through exaggerated situations is:',
    'a' => 'Tragedy',
    'b' => 'Farce',
    'c' => 'Opera',
    'd' => 'Melodrama',
    'answer' => 'B',
    'explanation' => 'Farce uses slapstick and ridiculous situations for humor.',
];

// ======== SECTION B: AFRICAN PROSE - SECOND CLASS CITIZEN (5 QUESTIONS) ========
$section_b_context = "SECTION B: AFRICAN PROSE - SECOND CLASS CITIZEN\n\nBayo Adeyemi's novel follows Adah, a determined Nigerian woman who dreams of education and success despite facing obstacles in both Nigeria and the United Kingdom, where she becomes a \"second class citizen.\"";

$questions[] = [
    'passage' => $section_b_context,
    'group' => 'second_class_citizen',
    'question' => 'In "Second Class Citizen", Adah\'s dream of going to the UK is called:',
    'a' => 'The Promised Land',
    'b' => 'Presence from the North',
    'c' => 'The Golden Fleece',
    'd' => 'The Big Move',
    'answer' => 'B',
    'explanation' => 'She refers to her ambition as "the presence" following her.',
];

$questions[] = [
    'passage' => $section_b_context,
    'group' => 'second_class_citizen',
    'question' => 'Why does Francis burn Adah\'s first manuscript, "The Bride Price"?',
    'a' => 'It was poorly written',
    'b' => 'He was jealous and wanted to destroy her joy',
    'c' => 'He needed paper for fire',
    'd' => 'He wanted her to focus on kids',
    'answer' => 'B',
    'explanation' => 'Francis represents the oppressive husband who fears Adah\'s success.',
];

$questions[] = [
    'passage' => $section_b_context,
    'group' => 'second_class_citizen',
    'question' => 'How does Adah gain admission to her first school in Nigeria?',
    'a' => 'She paid a bribe',
    'b' => 'She sneaked into the class while the teacher was busy',
    'c' => 'Her father took her',
    'd' => 'She won a scholarship',
    'answer' => 'B',
    'explanation' => 'She ran away from home to Mr. Cole\'s class to force her way into education.',
];

$questions[] = [
    'passage' => $section_b_context,
    'group' => 'second_class_citizen',
    'question' => 'The character "Pa Noble" represents:',
    'a' => 'Wealthy Nigerians',
    'b' => 'Africans who have lost their dignity in the UK',
    'c' => 'Adah\'s secret lover',
    'd' => 'A successful doctor',
    'answer' => 'B',
    'explanation' => 'He is a clownish figure used to show the low status of some immigrants.',
];

$questions[] = [
    'passage' => $section_b_context,
    'group' => 'second_class_citizen',
    'question' => 'Adah\'s children are often a source of ____ for her in London:',
    'a' => 'Shame',
    'b' => 'Strength and motivation',
    'c' => 'Poverty',
    'd' => 'Anger',
    'answer' => 'B',
    'explanation' => 'Her love for her children keeps her working hard despite the "Second Class" status.',
];

// ======== SECTION C: NON-AFRICAN PROSE - UNEXPECTED JOY AT DAWN (5 QUESTIONS) ========
$section_c_context = "SECTION C: NON-AFRICAN PROSE - UNEXPECTED JOY AT DAWN\n\nBy Kobina Eyi Acquah, this novel chronicles the experiences of Nigerians in Ghana during the 1969 Aliens Compliance Order, exploring themes of displacement, family, and hope.";

$questions[] = [
    'passage' => $section_c_context,
    'group' => 'unexpected_joy_dawn',
    'question' => 'The "Aliens Compliance Order" was issued by the government of:',
    'a' => 'Nigeria',
    'b' => 'Ghana',
    'c' => 'Liberia',
    'd' => 'Togo',
    'answer' => 'B',
    'explanation' => 'The 1969 order expelled Nigerians from Ghana.',
];

$questions[] = [
    'passage' => $section_c_context,
    'group' => 'unexpected_joy_dawn',
    'question' => 'Nii Tackie is an alien in Ghana because:',
    'a' => 'He is from outer space',
    'b' => 'He has Nigerian tribal marks',
    'c' => 'He cannot speak Twi',
    'd' => 'He has no passport',
    'answer' => 'B',
    'explanation' => 'His Yoruba tribal marks betray his Nigerian heritage despite living in Ghana.',
];

$questions[] = [
    'passage' => $section_c_context,
    'group' => 'unexpected_joy_dawn',
    'question' => 'What is the name of Nii\'s sickly wife?',
    'a' => 'Massa',
    'b' => 'Marshak',
    'c' => 'Linda',
    'd' => 'Mama Orojo',
    'answer' => 'A',
    'explanation' => 'Her illness and eventual death are central to Nii\'s journey.',
];

$questions[] = [
    'passage' => $section_c_context,
    'group' => 'unexpected_joy_dawn',
    'question' => 'Mama Orojo travels to Ghana primarily to:',
    'a' => 'Buy gold',
    'b' => 'Find her brother, Nii',
    'c' => 'Sell clothes',
    'd' => 'Hide from the law',
    'answer' => 'B',
    'explanation' => 'The siblings\' search for each other is the heart of the novel.',
];

$questions[] = [
    'passage' => $section_c_context,
    'group' => 'unexpected_joy_dawn',
    'question' => 'The title "Unexpected Joy at Dawn" refers to:',
    'a' => 'Finding a bag of money',
    'b' => 'The reunion of Nii and Mama Orojo',
    'c' => 'The end of the war',
    'd' => 'Winning a visa lottery',
    'answer' => 'B',
    'explanation' => 'It symbolizes the hope found after their long, painful separation.',
];

// ======== SECTION D: DRAMA - THE LION AND THE JEWEL (5 QUESTIONS) ========
$section_d_context = "SECTION D: DRAMA - THE LION AND THE JEWEL\n\nBy Wole Soyinka, this play is a comedic satire of the clash between traditional African customs and modern Western values, centered on the village of Ilujunle.";

$questions[] = [
    'passage' => $section_d_context,
    'group' => 'lion_jewel',
    'question' => 'Lakunle refuses to pay the "Bride Price" because he thinks it is:',
    'a' => 'Too expensive',
    'b' => 'A barbaric/savage custom',
    'c' => 'Not necessary for a teacher',
    'd' => 'Against his religion',
    'answer' => 'B',
    'explanation' => 'He views the tradition as an insult to his modern "civilized" views.',
];

$questions[] = [
    'passage' => $section_d_context,
    'group' => 'lion_jewel',
    'question' => 'Who is the "Lion" in the play?',
    'a' => 'Lakunle',
    'b' => 'Baroka',
    'c' => 'The Photographer',
    'd' => 'The Bale of Ilujunle',
    'answer' => 'B',
    'explanation' => 'Baroka is the crafty, strong leader of the village.',
];

$questions[] = [
    'passage' => $section_d_context,
    'group' => 'lion_jewel',
    'question' => 'Sadiku is the Bale\'s ____?',
    'a' => 'Daughter',
    'b' => 'Head Wife',
    'c' => 'Secret Agent',
    'd' => 'Student',
    'answer' => 'B',
    'explanation' => 'She is the senior wife who helps Baroka trick Sidi.',
];

$questions[] = [
    'passage' => $section_d_context,
    'group' => 'lion_jewel',
    'question' => 'What news does Sadiku bring that makes Sidi happy?',
    'a' => 'Lakunle has more money',
    'b' => 'Baroka is dead',
    'c' => 'Baroka is impotent',
    'd' => 'A plane crashed',
    'answer' => 'C',
    'explanation' => 'It was a trick to make Sidi drop her guard and visit the Bale.',
];

$questions[] = [
    'passage' => $section_d_context,
    'group' => 'lion_jewel',
    'question' => 'At the end of the play, Sidi chooses to marry:',
    'a' => 'Lakunle',
    'b' => 'The Photographer',
    'c' => 'Baroka',
    'd' => 'No one',
    'answer' => 'C',
    'explanation' => 'She chooses the "Lion" because he proved his strength over Lakunle\'s empty words.',
];

// ======== SECTION E: SHAKESPEARE - A MIDSUMMER NIGHT'S DREAM (5 QUESTIONS) ========
$section_e_context = "SECTION E: SHAKESPEARE - A MIDSUMMER NIGHT'S DREAM\n\nShakespeare's comedy features two pairs of young lovers who become entangled in fairy magic while escaping into an enchanted forest near Athens.";

$questions[] = [
    'passage' => $section_e_context,
    'group' => 'midsummer_dream',
    'question' => 'Theseus is the Duke of:',
    'a' => 'Rome',
    'b' => 'Athens',
    'c' => 'Verona',
    'd' => 'Venice',
    'answer' => 'B',
    'explanation' => 'The play starts and ends in the court of Athens.',
];

$questions[] = [
    'passage' => $section_e_context,
    'group' => 'midsummer_dream',
    'question' => 'The "love-in-idleness" flower was struck by:',
    'a' => 'A thunderbolt',
    'b' => 'Cupid\'s arrow',
    'c' => 'A fairy\'s wand',
    'd' => 'Sunlight',
    'answer' => 'B',
    'explanation' => 'This made the juice of the flower a powerful love potion.',
];

$questions[] = [
    'passage' => $section_e_context,
    'group' => 'midsummer_dream',
    'question' => 'Hermia\'s father, Egeus, wants her to marry:',
    'a' => 'Lysander',
    'b' => 'Demetrius',
    'c' => 'Bottom',
    'd' => 'Puck',
    'answer' => 'B',
    'explanation' => 'This conflict drives the lovers into the woods.',
];

$questions[] = [
    'passage' => $section_e_context,
    'group' => 'midsummer_dream',
    'question' => 'Who is the mischievous spirit who serves Oberon?',
    'a' => 'Peaseblossom',
    'b' => 'Cobweb',
    'c' => 'Puck (Robin Goodfellow)',
    'd' => 'Moth',
    'answer' => 'C',
    'explanation' => 'Puck is the prankster responsible for the mixed-up love spells.',
];

$questions[] = [
    'passage' => $section_e_context,
    'group' => 'midsummer_dream',
    'question' => 'The "play-within-a-play" performed by the craftsmen is:',
    'a' => 'Romeo and Juliet',
    'b' => 'Pyramus and Thisbe',
    'c' => 'Hamlet',
    'd' => 'Macbeth',
    'answer' => 'B',
    'explanation' => 'It is a comical tragedy performed for Theseus\' wedding.',
];

// ======== SECTION F: POETRY - AFRICAN & NON-AFRICAN (10 QUESTIONS) ========
$section_f_context = "SECTION F: POETRY - AFRICAN & NON-AFRICAN\n\nThis section covers diverse poetic traditions from African and European poets, examining different themes, styles, and literary techniques.";

$questions[] = [
    'passage' => $section_f_context,
    'group' => 'poetry_mixed',
    'question' => 'In "The Leader and the Led", the Hyena is rejected because:',
    'a' => 'He is too small',
    'b' => 'He smells of lethal appetite',
    'c' => 'He is a coward',
    'd' => 'He has no teeth',
    'answer' => 'B',
    'explanation' => 'The animals reject the Hyena because of his predatory nature.',
];

$questions[] = [
    'passage' => $section_f_context,
    'group' => 'poetry_mixed',
    'question' => 'Maya Angelou\'s "Caged Bird" represents:',
    'a' => 'A pet in a house',
    'b' => 'Oppressed black people',
    'c' => 'A beautiful singer',
    'd' => 'A dying animal',
    'answer' => 'B',
    'explanation' => 'It is an allegory for the lack of freedom under racism and segregation.',
];

$questions[] = [
    'passage' => $section_f_context,
    'group' => 'poetry_mixed',
    'question' => 'In "The Grieved Lands", the poet emphasizes the ____ of the African people:',
    'a' => 'Wealth',
    'b' => 'Despair',
    'c' => 'Resilience and Imperishability',
    'd' => 'Fear',
    'answer' => 'C',
    'explanation' => 'Neto celebrates the survival of the African spirit despite colonization.',
];

$questions[] = [
    'passage' => $section_f_context,
    'group' => 'poetry_mixed',
    'question' => 'The poem "Binsey Poplars" is a lament for:',
    'a' => 'Fallen soldiers',
    'b' => 'Felled trees',
    'c' => 'Lost love',
    'd' => 'A dead king',
    'answer' => 'B',
    'explanation' => 'Gerard Manley Hopkins mourns the destruction of nature.',
];

$questions[] = [
    'passage' => $section_f_context,
    'group' => 'poetry_mixed',
    'question' => '"The Panic of Growing Older" suggests that at 20, one is:',
    'a' => 'Wise',
    'b' => 'Hopeful and copy-copy',
    'c' => 'Old',
    'd' => 'Finished',
    'answer' => 'B',
    'explanation' => 'The poet describes youth as a time of imitation and high expectations.',
];

$questions[] = [
    'passage' => $section_f_context,
    'group' => 'poetry_mixed',
    'question' => 'In "The Good-Morrow", John Donne compares the lovers\' room to:',
    'a' => 'A prison',
    'b' => 'The whole world',
    'c' => 'A church',
    'd' => 'A ship',
    'answer' => 'B',
    'explanation' => 'He argues that their love makes their small room an everywhere.',
];

$questions[] = [
    'passage' => $section_f_context,
    'group' => 'poetry_mixed',
    'question' => '"Bat" by D.H. Lawrence portrays the creature as:',
    'a' => 'A hero',
    'b' => 'An "old shape of a woman"',
    'c' => 'A disgusting "disgraceful" thing',
    'd' => 'A bird of prey',
    'answer' => 'C',
    'explanation' => 'Lawrence expresses a strong dislike and horror for bats.',
];

$questions[] = [
    'passage' => $section_f_context,
    'group' => 'poetry_mixed',
    'question' => 'The poem "Vanity" (Birago Diop) asks what will happen if we:',
    'a' => 'Don\'t work',
    'b' => 'Don\'t listen to the voices of the ancestors',
    'c' => 'Become too rich',
    'd' => 'Travel away',
    'answer' => 'B',
    'explanation' => 'It warns against the abandonment of traditional wisdom and roots.',
];

$questions[] = [
    'passage' => $section_f_context,
    'group' => 'poetry_mixed',
    'question' => '"A Government Driver on his Retirement" ends in:',
    'a' => 'A big party',
    'b' => 'A promotion',
    'c' => 'A fatal accident',
    'd' => 'A new house',
    'answer' => 'C',
    'explanation' => 'The driver celebrates his freedom with alcohol and dies in a crash.',
];

$questions[] = [
    'passage' => $section_f_context,
    'group' => 'poetry_mixed',
    'question' => 'In "Journey of the Magi", the journey is described as:',
    'a' => 'Easy and fun',
    'b' => 'A "cold coming" and a hard time',
    'c' => 'A short trip',
    'd' => 'A political mission',
    'answer' => 'B',
    'explanation' => 'T.S. Eliot portrays the spiritual journey as difficult and painful.',
];

// ======== LOAD ALL QUESTIONS ========

echo "Loading " . count($questions) . " questions...\n";

$inserted = 0;
$errors = [];

foreach ($questions as $index => $q) {
    try {
        $passageId = $q['passage'] ? DB::table('questions')
            ->where('passage_group', $q['group'])
            ->pluck('id')
            ->first() : null;

        $result = DB::table('questions')->insert([
            'subject_id' => $literatureId,
            'question_text' => $q['question'],
            'option_a' => $q['a'],
            'option_b' => $q['b'],
            'option_c' => $q['c'],
            'option_d' => $q['d'],
            'correct_option' => $q['answer'],
            'explanation' => $q['explanation'],
            'passage_text' => $q['passage'],
            'passage_group' => $q['group'],
            'difficulty_level' => 'medium',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($result) {
            $inserted++;
            echo "✓ Q" . ($index + 1) . " loaded\n";
        }
    } catch (\Exception $e) {
        $errors[] = "Q" . ($index + 1) . ": " . $e->getMessage();
        echo "✗ Q" . ($index + 1) . " failed: " . $e->getMessage() . "\n";
    }
}

echo "\n=== LOADING COMPLETE ===\n";
echo "✅ Successfully inserted: $inserted questions\n";

if (!empty($errors)) {
    echo "❌ Errors encountered: " . count($errors) . "\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
} else {
    echo "✅ No errors - all questions loaded successfully!\n";
}

$totalCount = DB::table('questions')->where('subject_id', $literatureId)->count();
echo "\n📊 Total Literature questions in database: $totalCount\n";
echo "\nSections breakdown:\n";
echo "  • Section A (Literary Terms): 10 questions\n";
echo "  • Section B (Second Class Citizen): 5 questions\n";
echo "  • Section C (Unexpected Joy at Dawn): 5 questions\n";
echo "  • Section D (The Lion and the Jewel): 5 questions\n";
echo "  • Section E (A Midsummer Night's Dream): 5 questions\n";
echo "  • Section F (Poetry Mixed): 10 questions\n";
echo "  ────────────────────────────────────\n";
echo "  Total: 40 questions\n";
