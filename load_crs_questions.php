<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Get Christian Religious Studies subject ID
$crs = DB::table('subjects')->where('name', 'Christian Religious Studies')->first();
if ($crs) {
    echo "Christian Religious Studies subject found with ID: {$crs->id}\n";
    $crsId = $crs->id;
} else {
    echo "Christian Religious Studies subject not found. Creating it...\n";
    $crsId = DB::table('subjects')->insertGetId([
        'name' => 'Christian Religious Studies',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

$questions = [
    [
        'question' => 'According to the creation story in Genesis 1, man was created on the:',
        'a' => 'Fourth day',
        'b' => 'Fifth day',
        'c' => 'Sixth day',
        'd' => 'Seventh day',
        'answer' => 'C',
        'explanation' => 'God created land animals and humans on the sixth day before resting on the seventh.',
    ],
    [
        'question' => 'The major reason why Joseph\'s brothers hated him was because:',
        'a' => 'He was the firstborn',
        'b' => 'He was a dreamer',
        'c' => 'He was a shepherd',
        'd' => 'He refused to work',
        'answer' => 'B',
        'explanation' => 'His dreams of superiority and his father\'s favoritism (the coat of many colors) fueled their jealousy.',
    ],
    [
        'question' => 'God called Moses to deliver Israel from Egypt at:',
        'a' => 'Mount Sinai',
        'b' => 'Mount Horeb',
        'c' => 'Mount Nebo',
        'd' => 'Mount Carmel',
        'answer' => 'B',
        'explanation' => 'Moses encountered the burning bush at Horeb, the mountain of God.',
    ],
    [
        'question' => 'The sign of the covenant between God and Abraham was:',
        'a' => 'The Rainbow',
        'b' => 'Circumcision',
        'c' => 'The Sabbath',
        'd' => 'The Ten Commandments',
        'answer' => 'B',
        'explanation' => 'In Genesis 17, God commanded circumcision as a physical sign of the covenant.',
    ],
    [
        'question' => 'Who was the first King of Israel?',
        'a' => 'David',
        'b' => 'Solomon',
        'c' => 'Saul',
        'd' => 'Samuel',
        'answer' => 'C',
        'explanation' => 'Though Samuel was the prophet/judge, Saul was anointed as the first king.',
    ],
    [
        'question' => 'King David committed adultery with Bathsheba, the wife of:',
        'a' => 'Joab',
        'b' => 'Uriah',
        'c' => 'Nathan',
        'd' => 'Abner',
        'answer' => 'B',
        'explanation' => 'David sent Uriah the Hittite to the front lines of battle to be killed after the affair.',
    ],
    [
        'question' => 'Solomon\'s greatest achievement as king was the:',
        'a' => 'Expansion of the army',
        'b' => 'Building of the Temple',
        'c' => 'Marriage to Pharaoh\'s daughter',
        'd' => 'Judgment of the two harlots',
        'answer' => 'B',
        'explanation' => 'Solomon fulfilled David\'s dream by building the first permanent Temple for God in Jerusalem.',
    ],
    [
        'question' => 'The prophet who challenged the prophets of Baal on Mount Carmel was:',
        'a' => 'Elisha',
        'b' => 'Elijah',
        'c' => 'Amos',
        'd' => 'Hosea',
        'answer' => 'B',
        'explanation' => 'Elijah demonstrated that Yahweh is the true God by calling down fire from heaven.',
    ],
    [
        'question' => 'Hosea\'s marriage to Gomer was a symbol of:',
        'a' => 'God\'s love for Israel\'s faithfulness',
        'b' => 'Israel\'s unfaithfulness to God',
        'c' => 'The purity of the priesthood',
        'd' => 'The joy of family life',
        'answer' => 'B',
        'explanation' => 'Gomer\'s harlotry represented Israel\'s spiritual adultery (idolatry) against God.',
    ],
    [
        'question' => '"Let justice roll down like waters, and righteousness like an ever-flowing stream" was said by:',
        'a' => 'Isaiah',
        'b' => 'Jeremiah',
        'c' => 'Amos',
        'd' => 'Micah',
        'answer' => 'C',
        'explanation' => 'Amos was the prophet of social justice, condemning the oppression of the poor.',
    ],
    [
        'question' => 'The person who led the rebuilding of the walls of Jerusalem after the exile was:',
        'a' => 'Ezra',
        'b' => 'Nehemiah',
        'c' => 'Zerubbabel',
        'd' => 'Joshua',
        'answer' => 'B',
        'explanation' => 'Nehemiah served as cupbearer to the king and returned to rebuild the walls.',
    ],
    [
        'question' => 'The prophet who spent three days in the belly of a fish was:',
        'a' => 'Daniel',
        'b' => 'Jonah',
        'c' => 'Obadiah',
        'd' => 'Joel',
        'answer' => 'B',
        'explanation' => 'Jonah was swallowed after trying to flee from God\'s command to go to Nineveh.',
    ],
    [
        'question' => 'In the New Testament, the forerunner of Jesus Christ was:',
        'a' => 'John the Baptist',
        'b' => 'Elijah',
        'c' => 'Peter',
        'd' => 'Nicodemus',
        'answer' => 'A',
        'explanation' => 'John the Baptist prepared the way for Jesus, preaching repentance.',
    ],
    [
        'question' => 'Jesus was baptized in the River Jordan to:',
        'a' => 'Wash away his sins',
        'b' => 'Please his parents',
        'c' => 'Fulfill all righteousness',
        'd' => 'Become a member of the church',
        'answer' => 'C',
        'explanation' => 'In Matthew 3:15, Jesus said it was necessary to "fulfill all righteousness."',
    ],
    [
        'question' => 'The first miracle of Jesus was performed at:',
        'a' => 'Capernaum',
        'b' => 'Nazareth',
        'c' => 'Cana',
        'd' => 'Jerusalem',
        'answer' => 'C',
        'explanation' => 'Jesus turned water into wine at a wedding in Cana of Galilee.',
    ],
    [
        'question' => 'In the Parable of the Sower, the seeds that fell on the thorns represent those who:',
        'a' => 'Do not understand the word',
        'b' => 'Are choked by the cares of this world',
        'c' => 'Have no roots',
        'd' => 'Are immediately converted',
        'answer' => 'B',
        'explanation' => 'The thorns represent worldly anxieties and the deceitfulness of riches.',
    ],
    [
        'question' => 'The disciple who betrayed Jesus was:',
        'a' => 'Peter',
        'b' => 'Judas Iscariot',
        'c' => 'Thomas',
        'd' => 'James',
        'answer' => 'B',
        'explanation' => 'Judas betrayed Jesus to the chief priests for thirty pieces of silver.',
    ],
    [
        'question' => 'On the night of his betrayal, Jesus prayed at:',
        'a' => 'Mount of Olives (Gethsemane)',
        'b' => 'Golgotha',
        'c' => 'The Temple',
        'd' => 'Bethany',
        'answer' => 'A',
        'explanation' => 'Jesus endured great agony in the Garden of Gethsemane while his disciples slept.',
    ],
    [
        'question' => 'Who was the Roman Governor that sentenced Jesus to death?',
        'a' => 'Herod',
        'b' => 'Pontius Pilate',
        'c' => 'Felix',
        'd' => 'Festus',
        'answer' => 'B',
        'explanation' => 'Despite finding no fault in Him, Pilate succumbed to the pressure of the crowd.',
    ],
    [
        'question' => 'The first person to see the risen Christ according to the Gospels was:',
        'a' => 'Peter',
        'b' => 'John',
        'c' => 'Mary Magdalene',
        'd' => 'The Virgin Mary',
        'answer' => 'C',
        'explanation' => 'Mary Magdalene went to the tomb early on the first day of the week and saw Jesus.',
    ],
    [
        'question' => 'On the day of Pentecost, the Holy Spirit descended on the Apostles in the form of:',
        'a' => 'A Dove',
        'b' => 'Tongues of fire',
        'c' => 'A Cloud',
        'd' => 'Still small voice',
        'answer' => 'B',
        'explanation' => 'Acts 2 records that divided tongues as of fire rested on each of them.',
    ],
    [
        'question' => 'The first Christian martyr was:',
        'a' => 'James',
        'b' => 'Peter',
        'c' => 'Stephen',
        'd' => 'Paul',
        'answer' => 'C',
        'explanation' => 'Stephen was stoned to death for his witness to Christ.',
    ],
    [
        'question' => 'Saul\'s conversion took place on the road to:',
        'a' => 'Tarsus',
        'b' => 'Jerusalem',
        'c' => 'Damascus',
        'd' => 'Antioch',
        'answer' => 'C',
        'explanation' => 'Saul was blinded by a light from heaven while traveling to persecute Christians in Damascus.',
    ],
    [
        'question' => 'The first place where the followers of Jesus were called "Christians" was:',
        'a' => 'Jerusalem',
        'b' => 'Antioch',
        'c' => 'Rome',
        'd' => 'Ephesus',
        'answer' => 'B',
        'explanation' => 'In Acts 11:26, the disciples were first called Christians in Antioch.',
    ],
    [
        'question' => 'According to Paul in 1 Corinthians 13, the greatest of the virtues is:',
        'a' => 'Faith',
        'b' => 'Hope',
        'c' => 'Love (Charity)',
        'd' => 'Wisdom',
        'answer' => 'C',
        'explanation' => 'Paul concludes that love is the greatest of all spiritual gifts and virtues.',
    ],
    [
        'question' => 'Which of the following kings of Israel did what was "right in the eyes of the Lord"?',
        'a' => 'Ahab',
        'b' => 'Jeroboam',
        'c' => 'Josiah',
        'd' => 'Manasseh',
        'answer' => 'C',
        'explanation' => 'Josiah was known for his religious reforms and returning the people to the Law.',
    ],
    [
        'question' => 'The high priest who fell and died after hearing that the Ark of God was captured was:',
        'a' => 'Eli',
        'b' => 'Samuel',
        'c' => 'Hophni',
        'd' => 'Phinehas',
        'answer' => 'A',
        'explanation' => 'Eli died of a broken neck after hearing his sons were dead and the Ark was taken.',
    ],
    [
        'question' => 'Shadrach, Meshach, and Abednego were thrown into the fiery furnace because:',
        'a' => 'They stole from the king',
        'b' => 'They refused to worship the golden image',
        'c' => 'They prayed to God',
        'd' => 'They were found to be spies',
        'answer' => 'B',
        'explanation' => 'They remained loyal to God despite King Nebuchadnezzar\'s decree.',
    ],
    [
        'question' => 'The "Sermon on the Mount" is recorded in the Gospel of:',
        'a' => 'Matthew',
        'b' => 'Mark',
        'c' => 'Luke',
        'd' => 'John',
        'answer' => 'A',
        'explanation' => 'Matthew 5–7 contains the Beatitudes and the core teachings of Jesus\' ministry.',
    ],
    [
        'question' => 'Who was the king of Israel when Elijah was a prophet?',
        'a' => 'Saul',
        'b' => 'Solomon',
        'c' => 'Ahab',
        'd' => 'Jehu',
        'answer' => 'C',
        'explanation' => 'Ahab and his wife Jezebel were Elijah\'s chief antagonists.',
    ],
    [
        'question' => 'Zacchaeus, the tax collector, climbed a sycamore tree because:',
        'a' => 'He wanted to hide',
        'b' => 'He was short and wanted to see Jesus',
        'c' => 'He was running away',
        'd' => 'He was a tree climber',
        'answer' => 'B',
        'explanation' => 'Being short of stature, he climbed the tree to see Jesus passing through Jericho.',
    ],
    [
        'question' => 'The person who helped Jesus carry the cross was:',
        'a' => 'Joseph of Arimathea',
        'b' => 'Simon of Cyrene',
        'c' => 'Nicodemus',
        'd' => 'Barnabas',
        'answer' => 'B',
        'explanation' => 'The Roman soldiers compelled Simon of Cyrene to carry the cross.',
    ],
    [
        'question' => 'The fruit of the Spirit, according to Galatians 5:22, includes:',
        'a' => 'Prophecy',
        'b' => 'Healing',
        'c' => 'Gentleness',
        'd' => 'Tongues',
        'answer' => 'C',
        'explanation' => 'Gentleness is one of the nine fruits; others like Prophecy are "Gifts" of the Spirit.',
    ],
    [
        'question' => 'In the book of James, faith without works is:',
        'a' => 'Small',
        'b' => 'Dead',
        'c' => 'Growing',
        'd' => 'Acceptable',
        'answer' => 'B',
        'explanation' => 'James teaches that true faith must be accompanied by actions.',
    ],
    [
        'question' => 'God gave the Ten Commandments to Moses on:',
        'a' => 'Mt. Ararat',
        'b' => 'Mt. Sinai',
        'c' => 'Mt. Zion',
        'd' => 'Mt. Hermon',
        'answer' => 'B',
        'explanation' => 'Mount Sinai (also called Horeb) is where the Law was given.',
    ],
    [
        'question' => 'The parable of the "Prodigal Son" teaches mainly about:',
        'a' => 'Financial management',
        'b' => 'Farming',
        'c' => 'God\'s forgiveness',
        'd' => 'Sibling rivalry',
        'answer' => 'C',
        'explanation' => 'It illustrates the Father\'s (God\'s) joy and readiness to forgive a repentant sinner.',
    ],
    [
        'question' => 'How many disciples did Jesus choose to be with Him?',
        'a' => '7',
        'b' => '10',
        'c' => '12',
        'd' => '70',
        'answer' => 'C',
        'explanation' => 'Jesus chose twelve apostles to represent the twelve tribes of Israel.',
    ],
    [
        'question' => 'The name "Immanuel" means:',
        'a' => 'God is great',
        'b' => 'God with us',
        'c' => 'God saves',
        'd' => 'God is one',
        'answer' => 'B',
        'explanation' => 'This name was prophesied in Isaiah and applied to Jesus in Matthew.',
    ],
    [
        'question' => 'Peter was a ______ by profession before he was called by Jesus.',
        'a' => 'Tax collector',
        'b' => 'Fisherman',
        'c' => 'Tentmaker',
        'd' => 'Carpenter',
        'answer' => 'B',
        'explanation' => 'Peter and his brother Andrew were fishermen on the Sea of Galilee.',
    ],
    [
        'question' => 'The wall of Jericho fell down after the Israelites marched around it for:',
        'a' => '1 day',
        'b' => '3 days',
        'c' => '7 days',
        'd' => '40 days',
        'answer' => 'C',
        'explanation' => 'On the seventh day, after seven laps and a shout, the walls collapsed.',
    ],
];

$inserted = 0;
$failed = 0;

foreach ($questions as $index => $q) {
    try {
        DB::table('questions')->insert([
            'subject_id' => $crsId,
            'question_text' => $q['question'],
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
echo "Total questions: " . count($questions) . "\n";
echo "Successfully inserted: $inserted\n";
echo "Failed: $failed\n";

// Show total questions in CRS
$total = DB::table('questions')->where('subject_id', $crsId)->count();
echo "\nTotal Christian Religious Studies questions in database: $total\n";
