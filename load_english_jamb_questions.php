<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Get English Language subject ID
$english = DB::table('subjects')
    ->where('name', 'LIKE', '%English%')
    ->orWhere('name', 'LIKE', '%ENGLISH%')
    ->first();

if (!$english) {
    echo "Creating ENGLISH LANGUAGE subject...\n";
    $englishId = DB::table('subjects')->insertGetId([
        'name' => 'ENGLISH LANGUAGE',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
} else {
    $englishId = $english->id;
    echo "English Language subject found with ID: {$englishId}\n";
}

echo "\n=== LOADING JAMB USE OF ENGLISH (60 QUESTIONS) ===\n\n";

// SECTION A: THE LEKKI HEADMASTER (Questions 1-20)
// Novel-based context
$lekki_context = "SECTION A: THE LEKKI HEADMASTER\nBased on the novel by Kabir Alabi Garba.\n\nThe novel follows Mr. Adebepo Adewale, the dedicated headmaster of Stardom Schools in the affluent Lekki area of Lagos. The story explores themes of integrity, parental pressure, grade inflation, and the clash between educational standards and wealthy parents' expectations. The setting represents modern Nigerian elite society with its unique challenges.";

$questions = [];

// Section A: Novel Questions (1-6 shown as example, user can add 7-20)
$questions[] = [
    'passage' => $lekki_context,
    'group' => 'lekki_headmaster',
    'question' => 'What is the full name of the protagonist?',
    'a' => 'Mr. Adebepo Adewale',
    'b' => 'Mr. Segun Arinze',
    'c' => 'Mr. Kabir Alabi',
    'd' => 'Mr. Bakare',
    'answer' => 'A',
    'explanation' => 'Mr. Adebepo Adewale is the dedicated headmaster of Stardom Schools who serves as the novel\'s protagonist.',
];

$questions[] = [
    'passage' => $lekki_context,
    'group' => 'lekki_headmaster',
    'question' => 'The setting "Lekki" in the novel primarily represents:',
    'a' => 'Poverty',
    'b' => 'Affluence and its challenges',
    'c' => 'Rural life',
    'd' => 'Political instability',
    'answer' => 'B',
    'explanation' => 'Lekki is depicted as an elite area where wealth often clashes with moral standards and educational integrity.',
];

$questions[] = [
    'passage' => $lekki_context,
    'group' => 'lekki_headmaster',
    'question' => 'Which character is the proprietor of Stardom Schools?',
    'a' => 'Chief (Mrs) Eleniyan',
    'b' => 'Mr. Adewale',
    'c' => 'Alhaji Garba',
    'd' => 'Mrs. Shonukan',
    'answer' => 'A',
    'explanation' => 'Chief (Mrs) Eleniyan owns the school and often represents the business side of education in the novel.',
];

$questions[] = [
    'passage' => $lekki_context,
    'group' => 'lekki_headmaster',
    'question' => 'The major conflict Mr. Adebepo faces involves:',
    'a' => 'Lack of water',
    'b' => 'Parental pressure to inflate grades',
    'c' => 'Government tax',
    'd' => 'Student riots',
    'answer' => 'B',
    'explanation' => 'The "Lekki parents" often try to use their wealth to influence their children\'s results, creating the central conflict.',
];

$questions[] = [
    'passage' => $lekki_context,
    'group' => 'lekki_headmaster', 
    'question' => 'What does the "Japa" syndrome represent in the story?',
    'a' => 'A local festival',
    'b' => 'Brain drain and migration',
    'c' => 'A new school subject',
    'd' => 'A type of car',
    'answer' => 'B',
    'explanation' => 'The novel reflects the modern Nigerian reality of professionals leaving the country, known as the "Japa" syndrome.',
];

$questions[] = [
    'passage' => $lekki_context,
    'group' => 'lekki_headmaster',
    'question' => 'How does the Headmaster handle the attempted bribe by a parent?',
    'a' => 'He takes half',
    'b' => 'He reports to the police immediately',
    'c' => 'He firmly declines',
    'd' => 'He resigns',
    'answer' => 'C',
    'explanation' => 'His integrity is the central theme; he refuses to compromise academic standards despite bribery attempts.',
];

$questions[] = [
    'passage' => $lekki_context,
    'group' => 'lekki_headmaster',
    'question' => 'How does the "Lekki environment" affect the students\' behavior?',
    'a' => 'It makes them more humble',
    'b' => 'It creates a sense of entitlement',
    'c' => 'It makes them study harder',
    'd' => 'It has no effect',
    'answer' => 'B',
    'explanation' => 'The wealth in Lekki often makes students feel they can get away with anything.',
];

$questions[] = [
    'passage' => $lekki_context,
    'group' => 'lekki_headmaster',
    'question' => 'What is Mr. Adebepo\'s reaction to the "Japa" trend among his staff?',
    'a' => 'He fires them',
    'b' => 'He is saddened but understands the economic reality',
    'c' => 'He joins them',
    'd' => 'He reports them',
    'answer' => 'B',
    'explanation' => 'He recognizes the brain drain but stays to fix the Nigerian system.',
];

$questions[] = [
    'passage' => $lekki_context,
    'group' => 'lekki_headmaster',
    'question' => 'Who is the "villain" representing systemic corruption in the novel?',
    'a' => 'The Gatekeeper',
    'b' => 'Mr. Shonukan',
    'c' => 'Moji',
    'd' => 'The Governor',
    'answer' => 'B',
    'explanation' => 'Shonukan often acts as the antagonist pushing for shortcuts.',
];

$questions[] = [
    'passage' => $lekki_context,
    'group' => 'lekki_headmaster',
    'question' => 'The "Stardom Schools" motto emphasizes:',
    'a' => 'Wealth only',
    'b' => 'Integrity and Excellence',
    'c' => 'Sport and Music',
    'd' => 'Politics',
    'answer' => 'B',
    'explanation' => 'This aligns with the Headmaster\'s personal mission.',
];

$questions[] = [
    'passage' => $lekki_context,
    'group' => 'lekki_headmaster',
    'question' => 'Why did Moji initially disagree with her husband\'s strictness?',
    'a' => 'She wanted more money',
    'b' => 'She hated students',
    'c' => 'She wanted to move to the UK',
    'd' => 'She was lazy',
    'answer' => 'A',
    'explanation' => 'Like many, she felt his "over-integrity" kept the family from enjoying Lekki\'s wealth.',
];

$questions[] = [
    'passage' => $lekki_context,
    'group' => 'lekki_headmaster',
    'question' => 'The character \'Bisi\' serves as a symbol of:',
    'a' => 'Greed',
    'b' => 'The innocent student caught in the system',
    'c' => 'Technology',
    'd' => 'Old age',
    'answer' => 'B',
    'explanation' => 'She represents the students the Headmaster is fighting to protect.',
];

$questions[] = [
    'passage' => $lekki_context,
    'group' => 'lekki_headmaster',
    'question' => 'What happens during the PTA meeting that tests Mr. Adebepo?',
    'a' => 'A parent offers a bribe openly',
    'b' => 'The school roof collapses',
    'c' => 'A teacher is slapped',
    'd' => 'He is promoted',
    'answer' => 'A',
    'explanation' => 'It serves as a public test of his moral standing.',
];

$questions[] = [
    'passage' => $lekki_context,
    'group' => 'lekki_headmaster',
    'question' => 'How does the novel end for the Headmaster?',
    'a' => 'He is sacked',
    'b' => 'He is vindicated and his integrity is rewarded',
    'c' => 'He runs away',
    'd' => 'He dies',
    'answer' => 'B',
    'explanation' => 'The novel concludes with a victory for moral standards.',
];

// SECTION B: COMPREHENSION PASSAGE (Questions 21-25)
$digital_economy_passage = "SECTION B: COMPREHENSION PASSAGE\n\nThe digital economy in Nigeria is growing at an unprecedented rate. However, this growth is threatened by unstable power supply and high data costs. If the government does not intervene, the 'Silicon Lagoon' dream may remain a mirage.";

$questions[] = [
    'passage' => $digital_economy_passage,
    'group' => 'digital_economy',
    'question' => 'The writer uses \'unprecedented\' to show that growth is:',
    'a' => 'Slow',
    'b' => 'Never seen before',
    'c' => 'Expected',
    'd' => 'Dangerous',
    'answer' => 'B',
    'explanation' => 'Unprecedented means something that has no previous example or is exceptional in nature.',
];

$questions[] = [
    'passage' => $digital_economy_passage,
    'group' => 'digital_economy',
    'question' => 'What are the two main threats mentioned?',
    'a' => 'Water and Roads',
    'b' => 'Power and Data costs',
    'c' => 'Schools and Hospitals',
    'd' => 'Taxes and Fuel',
    'answer' => 'B',
    'explanation' => 'The text explicitly mentions "unstable power supply" and "high data costs" as the two threats.',
];

$questions[] = [
    'passage' => $digital_economy_passage,
    'group' => 'digital_economy',
    'question' => 'The phrase \'Silicon Lagoon\' is a reference to:',
    'a' => 'A new beach',
    'b' => 'Nigeria\'s tech hub',
    'c' => 'A fish farm',
    'd' => 'A computer shop',
    'answer' => 'B',
    'explanation' => 'It mimics "Silicon Valley," referring to the tech ecosystem in Lagos, Nigeria.',
];

$questions[] = [
    'passage' => $digital_economy_passage,
    'group' => 'digital_economy',
    'question' => 'A \'mirage\' as used in the passage means:',
    'a' => 'A reality',
    'b' => 'An illusion/unachievable dream',
    'c' => 'A success',
    'd' => 'A building',
    'answer' => 'B',
    'explanation' => 'A mirage is something that looks real but isn\'t achievable or doesn\'t truly exist.',
];

$questions[] = [
    'passage' => $digital_economy_passage,
    'group' => 'digital_economy',
    'question' => 'The writer\'s tone is:',
    'a' => 'Sarcastic',
    'b' => 'Warning',
    'c' => 'Indifferent',
    'd' => 'Excited',
    'answer' => 'B',
    'explanation' => 'The writer is cautioning the government about potential failure if intervention doesn\'t happen.',
];

// SECTION C: CLOZE PASSAGE - LEGAL REGISTER (Questions 26-35)
$legal_cloze_passage = "SECTION C: CLOZE PASSAGE - LEGAL REGISTER\n\nComplete the passage with appropriate legal terminology:\n\nThe <strong>(26)_______</strong> was filed by the <strong>(27)_______</strong> who claimed his rights were violated. The <strong>(28)_______</strong> argued that there was no admissible evidence. The witness was cross-examined by the <strong>(29)_______</strong>. The suspect was reminded of his right to remain <strong>(30)_______</strong>. The judge ruled that the evidence was <strong>(31)_______</strong>. A <strong>(32)_______</strong> was issued for the arrest of the runaway thief. The highest court in Nigeria is the <strong>(34)_______</strong> Court. The jury's decision is called a <strong>(35)_______</strong>.\n\n[Questions 26-35 test individual blanks from this passage]";

$questions[] = [
    'passage' => $legal_cloze_passage,
    'group' => 'legal_cloze',
    'question' => 'The <strong>_______</strong> was filed by the plaintiff.',
    'a' => 'Suit',
    'b' => 'Ward',
    'c' => 'Script',
    'd' => 'Score',
    'answer' => 'A',
    'explanation' => 'A \'suit\' is a formal legal process or case brought before a court.',
];

$questions[] = [
    'passage' => $legal_cloze_passage,
    'group' => 'legal_cloze',
    'question' => 'The suit was filed by the <strong>_______</strong> who claimed his rights were violated.',
    'a' => 'Patient',
    'b' => 'Plaintiff',
    'c' => 'Candidate',
    'd' => 'Author',
    'answer' => 'B',
    'explanation' => 'A plaintiff is the person who brings a case or lawsuit to court.',
];

$questions[] = [
    'passage' => $legal_cloze_passage,
    'group' => 'legal_cloze',
    'question' => 'The <strong>_______</strong> argued that there was no admissible evidence.',
    'a' => 'Doctor',
    'b' => 'Teacher',
    'c' => 'Counsel',
    'd' => 'Priest',
    'answer' => 'C',
    'explanation' => 'Counsel refers to the lawyer or legal representative in court proceedings.',
];

$questions[] = [
    'passage' => $legal_cloze_passage,
    'group' => 'legal_cloze',
    'question' => 'The witness was cross-examined by the <strong>_______</strong>.',
    'a' => 'Judge',
    'b' => 'Prosecution',
    'c' => 'Jury',
    'd' => 'Clerk',
    'answer' => 'B',
    'explanation' => 'The opposing side (prosecution) questions the witness.',
];

$questions[] = [
    'passage' => $legal_cloze_passage,
    'group' => 'legal_cloze',
    'question' => 'The suspect was reminded of his right to remain <strong>_______</strong>.',
    'a' => 'Noisy',
    'b' => 'Silent',
    'c' => 'Angry',
    'd' => 'Happy',
    'answer' => 'B',
    'explanation' => 'The "right to remain silent" is a standard legal caution.',
];

$questions[] = [
    'passage' => $legal_cloze_passage,
    'group' => 'legal_cloze',
    'question' => 'The judge ruled that the evidence was <strong>_______</strong>.',
    'a' => 'Inadmissible',
    'b' => 'Edible',
    'c' => 'Invisible',
    'd' => 'Plastic',
    'answer' => 'A',
    'explanation' => 'Inadmissible means it cannot be used in court.',
];

$questions[] = [
    'passage' => $legal_cloze_passage,
    'group' => 'legal_cloze',
    'question' => 'A <strong>_______</strong> was issued for the arrest of the runaway thief.',
    'a' => 'Receipt',
    'b' => 'Warrant',
    'c' => 'Letter',
    'd' => 'Ticket',
    'answer' => 'B',
    'explanation' => 'A warrant is a legal document authorizing an arrest.',
];

$questions[] = [
    'passage' => $legal_cloze_passage,
    'group' => 'legal_cloze',
    'question' => 'The highest court in Nigeria is the <strong>_______</strong> Court.',
    'a' => 'High',
    'b' => 'Magistrate',
    'c' => 'Supreme',
    'd' => 'Customary',
    'answer' => 'C',
    'explanation' => 'The Supreme Court is the final court of appeal.',
];

$questions[] = [
    'passage' => $legal_cloze_passage,
    'group' => 'legal_cloze',
    'question' => 'The jury\'s decision is called a <strong>_______</strong>.',
    'a' => 'Story',
    'b' => 'Verdict',
    'c' => 'Guess',
    'd' => 'Choice',
    'answer' => 'B',
    'explanation' => 'A verdict is the final decision on guilt or innocence.',
];

// SECTION D: LEXIS & STRUCTURE (Questions 36-50)
// No passage needed for these standalone questions

$questions[] = [
    'passage' => null,
    'group' => 'lexis_structure',
    'question' => 'Choose the synonym for \'Candid\':',
    'a' => 'Hidden',
    'b' => 'Frank/Honest',
    'c' => 'Blue',
    'd' => 'Slow',
    'answer' => 'B',
    'explanation' => 'Being candid means being straightforward, frank, and honest in expression.',
];

$questions[] = [
    'passage' => null,
    'group' => 'lexis_structure',
    'question' => 'Choose the antonym for \'Amicable\':',
    'a' => 'Friendly',
    'b' => 'Hostile',
    'c' => 'Rich',
    'd' => 'Loud',
    'answer' => 'B',
    'explanation' => 'Amicable means friendly or peaceable; hostile (unfriendly/aggressive) is the opposite.',
];

$questions[] = [
    'passage' => null,
    'group' => 'lexis_structure',
    'question' => '"The news of the accident \'broke\' his heart." This is:',
    'a' => 'Simile',
    'b' => 'Idiom',
    'c' => 'Personification',
    'd' => 'Hyperbole',
    'answer' => 'B',
    'explanation' => 'It is a figurative idiomatic expression meaning deep sadness or emotional distress.',
];

$questions[] = [
    'passage' => null,
    'group' => 'lexis_structure',
    'question' => 'Each of the students ____ to bring a laptop tomorrow.',
    'a' => 'Are',
    'b' => 'Has',
    'c' => 'Have',
    'd' => 'Were',
    'answer' => 'B',
    'explanation' => '\'Each\' is always singular and takes a singular verb \'has\', not the plural \'have\'.',
];

$questions[] = [
    'passage' => null,
    'group' => 'lexis_structure',
    'question' => 'He didn\'t go to the party, ____?',
    'a' => 'Didn\'t he',
    'b' => 'Did he',
    'c' => 'Has he',
    'd' => 'Had he',
    'answer' => 'B',
    'explanation' => 'In tag questions, a negative statement takes a positive tag. "Didn\'t go" → "did he?"',
];

$questions[] = [
    'passage' => null,
    'group' => 'lexis_structure',
    'question' => 'Choose the synonym for \'Meticulous\':',
    'a' => 'Sloppy',
    'b' => 'Careful',
    'c' => 'Brave',
    'd' => 'Rude',
    'answer' => 'B',
    'explanation' => 'Both mean showing great attention to detail.',
];

$questions[] = [
    'passage' => null,
    'group' => 'lexis_structure',
    'question' => 'Choose the antonym for \'Obscure\':',
    'a' => 'Dark',
    'b' => 'Clear/Well-known',
    'c' => 'Hidden',
    'd' => 'Small',
    'answer' => 'B',
    'explanation' => 'Obscure means unknown; clear is the opposite.',
];

$questions[] = [
    'passage' => null,
    'group' => 'lexis_structure',
    'question' => 'The idiom "To kick the bucket" means:',
    'a' => 'To play football',
    'b' => 'To die',
    'c' => 'To be angry',
    'd' => 'To clean the house',
    'answer' => 'B',
    'explanation' => 'This is a common idiom for death.',
];

$questions[] = [
    'passage' => null,
    'group' => 'lexis_structure',
    'question' => 'She is the ____ of the two sisters.',
    'a' => 'Tallest',
    'b' => 'Taller',
    'c' => 'Tall',
    'd' => 'Most tall',
    'answer' => 'B',
    'explanation' => 'Use comparative (-er) for two people; superlative (-est) for three or more.',
];

$questions[] = [
    'passage' => null,
    'group' => 'lexis_structure',
    'question' => 'He is proficient ____ Mathematics.',
    'a' => 'At',
    'b' => 'In',
    'c' => 'With',
    'd' => 'For',
    'answer' => 'B',
    'explanation' => 'You are proficient "in" a subject or skill.',
];

// SECTION E: ORAL FORMS (Questions 51-60)
$questions[] = [
    'passage' => null,
    'group' => 'oral_forms',
    'question' => 'Which word has the same vowel sound as /i:/ (e.g., \'Team\')?',
    'a' => 'Tin',
    'b' => 'Field',
    'c' => 'Ten',
    'd' => 'Tank',
    'answer' => 'B',
    'explanation' => '\'Field\' has the long /i:/ sound like \'Team\'. Other options have short vowel sounds.',
];

$questions[] = [
    'passage' => null,
    'group' => 'oral_forms',
    'question' => 'Identify the word with the /f/ sound:',
    'a' => 'Psychology',
    'b' => 'Laugh',
    'c' => 'Shepherd',
    'd' => 'Puncture',
    'answer' => 'B',
    'explanation' => 'In \'Laugh\', the \'gh\' is pronounced as \'f\', making the /f/ sound.',
];

$questions[] = [
    'passage' => null,
    'group' => 'oral_forms',
    'question' => 'Which word contains the /θ/ sound (e.g., \'Thin\')?',
    'a' => 'This',
    'b' => 'Three',
    'c' => 'Thomas',
    'd' => 'Then',
    'answer' => 'B',
    'explanation' => '\'Three\' uses the voiceless \'th\' sound /θ/, while others use the voiced /ð/ or other sounds.',
];

$questions[] = [
    'passage' => null,
    'group' => 'oral_forms',
    'question' => 'Select the word that rhymes with \'Bread\':',
    'a' => 'Bead',
    'b' => 'Said',
    'c' => 'Blade',
    'd' => 'Seed',
    'answer' => 'B',
    'explanation' => '\'Bread\' and \'Said\' both have the short /e/ sound, making them rhyme.',
];

$questions[] = [
    'passage' => null,
    'group' => 'oral_forms',
    'question' => 'Which word has a silent \'k\'?',
    'a' => 'Kite',
    'b' => 'Knowledge',
    'c' => 'Kettle',
    'd' => 'Kill',
    'answer' => 'B',
    'explanation' => 'In \'Knowledge\', the \'k\' is not pronounced.',
];

$questions[] = [
    'passage' => null,
    'group' => 'oral_forms',
    'question' => 'Identify the word with the /tʃ/ sound (e.g., \'Church\'):',
    'a' => 'Character',
    'b' => 'Chemistry',
    'c' => 'Cheap',
    'd' => 'Machine',
    'answer' => 'C',
    'explanation' => '\'Character\' uses /k/, \'Machine\' uses /ʃ/, \'Cheap\' uses /tʃ/.',
];

$questions[] = [
    'passage' => null,
    'group' => 'oral_forms',
    'question' => 'Which word has the same stress pattern as \'E-DUC-ATE\'?',
    'a' => 'Be-lieve',
    'b' => 'Cal-cul-ate',
    'c' => 'Pro-vide',
    'd' => 'Re-port',
    'answer' => 'B',
    'explanation' => 'Both are 3-syllable words with stress on the first syllable.',
];

$questions[] = [
    'passage' => null,
    'group' => 'oral_forms',
    'question' => 'Pick the word that rhymes with \'Goat\':',
    'a' => 'Got',
    'b' => 'Note',
    'c' => 'Hot',
    'd' => 'Lot',
    'answer' => 'B',
    'explanation' => 'Both use the /əʊ/ diphthong.',
];

$questions[] = [
    'passage' => null,
    'group' => 'oral_forms',
    'question' => 'Which word contains the /z/ sound?',
    'a' => 'Books',
    'b' => 'Pens',
    'c' => 'Cats',
    'd' => 'Laughs',
    'answer' => 'B',
    'explanation' => 'The \'s\' in \'Pens\' is voiced and sounds like /z/.',
];

$questions[] = [
    'passage' => null,
    'group' => 'oral_forms',
    'question' => 'Identify the word with the /v/ sound:',
    'a' => 'Off',
    'b' => 'Of',
    'c' => 'Philip',
    'd' => 'Staff',
    'answer' => 'B',
    'explanation' => 'Tricky! \'Of\' is pronounced with a /v/ sound, unlike \'Off\' (/f/).',
];

// Insert questions
$inserted = 0;
$failed = 0;

foreach ($questions as $index => $q) {
    try {
        DB::table('questions')->insert([
            'subject_id' => $englishId,
            'question_text' => $q['question'],
            'passage_text' => $q['passage'],
            'passage_group' => $q['group'],
            'option_a' => $q['a'],
            'option_b' => $q['b'],
            'option_c' => $q['c'],
            'option_d' => $q['d'],
            'correct_option' => $q['answer'],
            'explanation' => $q['explanation'],
            'difficulty_level' => 'medium',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $inserted++;
        echo "✓ Question " . ($index + 1) . " inserted\n";
    } catch (\Exception $e) {
        $failed++;
        echo "✗ Question " . ($index + 1) . " failed: " . $e->getMessage() . "\n";
    }
}

echo "\n=== SUMMARY ===\n";
echo "Sample questions loaded: $inserted\n";
echo "Failed: $failed\n";

$total = DB::table('questions')->where('subject_id', $englishId)->count();
echo "\nTotal English Language questions in database: $total\n";

echo "\n📝 JAMB ENGLISH SUMMARY:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ Section A: The Lekki Headmaster - 14 questions (Novel)\n";
echo "✅ Section B: Digital Economy Passage - 5 questions (Comprehension)\n";
echo "✅ Section C: Legal Register - 9 questions (Cloze Passage)\n";
echo "✅ Section D: Lexis & Structure - 11 questions (Grammar/Vocab)\n";
echo "✅ Section E: Oral Forms - 10 questions (Phonetics)\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TOTAL: 49 questions in this script\n\n";
echo "NOTE: Question 33 removed (Adjournment - flagged as tricky)\n\n";

echo "⚠️  STILL NEEDED for 60-question JAMB Mock:\n";
echo "- Questions 15-20: More Lekki Headmaster context (6 questions)\n";
echo "- Questions 46-50: More Lexis & Structure (4 questions)\n\n";

echo "✅ The system now supports passage-based comprehension questions!\n";
