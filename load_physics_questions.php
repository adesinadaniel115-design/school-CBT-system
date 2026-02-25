<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Get Physics subject ID
$physics = DB::table('subjects')->where('name', 'Physics')->first();
if (!$physics) {
    echo "Physics subject not found. Creating it...\n";
    $physicsId = DB::table('subjects')->insertGetId([
        'name' => 'Physics',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
} else {
    $physicsId = $physics->id;
    echo "Physics subject found with ID: $physicsId\n";
}

$questions = [
    [
        'question' => 'A body of mass 2 kg moves with a velocity of 10 m/s. It is brought to rest by a constant force over a distance of 5 m. The magnitude of the force is:',
        'a' => '10 N',
        'b' => '15 N',
        'c' => '20 N',
        'd' => '25 N',
        'answer' => 'C',
        'explanation' => 'Initial KE = ½mv² = ½(2)(100)=100 J. Work done = Fs = F×5 =100 → F=20 N.',
    ],
    [
        'question' => 'A projectile is fired at 30° with speed 40 m/s. Its time of flight is approximately (g=10 m/s²):',
        'a' => '2 s',
        'b' => '3 s',
        'c' => '4 s',
        'd' => '6 s',
        'answer' => 'C',
        'explanation' => 'T = 2u sinθ / g = 2(40×0.5)/10 = 4 s.',
    ],
    [
        'question' => 'Two resistors 6Ω and 3Ω are connected in parallel across 9V. Total current drawn is:',
        'a' => '2 A',
        'b' => '3 A',
        'c' => '4.5 A',
        'd' => '6 A',
        'answer' => 'C',
        'explanation' => '1/R = 1/6 + 1/3 = 1/6 + 2/6 = 3/6 → R=2Ω. I = V/R = 9/2 = 4.5 A.',
    ],
    [
        'question' => 'If the frequency of a wave doubles while wave speed remains constant, the wavelength:',
        'a' => 'Doubles',
        'b' => 'Halves',
        'c' => 'Remains constant',
        'd' => 'Becomes zero',
        'answer' => 'B',
        'explanation' => 'v = fλ. If f doubles, λ halves.',
    ],
    [
        'question' => 'A car of mass 1000 kg moves in a circle of radius 20 m at 10 m/s. Centripetal force is:',
        'a' => '500 N',
        'b' => '1000 N',
        'c' => '5000 N',
        'd' => '10000 N',
        'answer' => 'C',
        'explanation' => 'F = mv²/r = 1000×100/20 = 5000 N.',
    ],
    [
        'question' => 'An object weighs 100 N in air and 80 N in water. Upthrust is:',
        'a' => '10 N',
        'b' => '15 N',
        'c' => '20 N',
        'd' => '25 N',
        'answer' => 'C',
        'explanation' => 'Upthrust = loss in weight = 20 N.',
    ],
    [
        'question' => 'A 12V battery supplies 3A for 5 minutes. Energy delivered is:',
        'a' => '540 J',
        'b' => '1080 J',
        'c' => '10,800 J',
        'd' => '32,400 J',
        'answer' => 'C',
        'explanation' => 'E = VIt = 12×3×300 = 10,800 J.',
    ],
    [
        'question' => 'A body moves with uniform acceleration. If velocity changes from 5 m/s to 25 m/s in 4 s, acceleration is:',
        'a' => '4 m/s²',
        'b' => '5 m/s²',
        'c' => '6 m/s²',
        'd' => '7 m/s²',
        'answer' => 'B',
        'explanation' => 'a = (25−5)/4 = 5 m/s².',
    ],
    [
        'question' => 'A 0.2 kg ball moving at 15 m/s strikes a wall and rebounds at 10 m/s. Change in momentum is:',
        'a' => '1 kgm/s',
        'b' => '2 kgm/s',
        'c' => '5 kgm/s',
        'd' => '10 kgm/s',
        'answer' => 'C',
        'explanation' => 'Initial p = 3, final p = −2 → change = 5 kgm/s.',
    ],
    [
        'question' => 'A transformer has 500 primary turns and 100 secondary turns. If primary voltage is 240V, secondary voltage is:',
        'a' => '24V',
        'b' => '48V',
        'c' => '60V',
        'd' => '120V',
        'answer' => 'B',
        'explanation' => 'Vs/Vp = Ns/Np = 100/500 = 1/5 → Vs = 48V.',
    ],
    [
        'question' => 'A gas expands isothermally. Its internal energy:',
        'a' => 'Increases',
        'b' => 'Decreases',
        'c' => 'Remains constant',
        'd' => 'Becomes zero',
        'answer' => 'C',
        'explanation' => 'Internal energy depends only on temperature.',
    ],
    [
        'question' => 'A 2 kg mass is attached to a spring (k = 200 N/m). Period of oscillation is approximately:',
        'a' => '0.2 s',
        'b' => '0.6 s',
        'c' => '1.0 s',
        'd' => '2.0 s',
        'answer' => 'B',
        'explanation' => 'T = 2π√(m/k) ≈ 2π√(2/200) ≈ 0.6 s.',
    ],
    [
        'question' => 'Two charges 2C and 3C are 2m apart. Force between them (k=9×10⁹):',
        'a' => '1.35×10¹⁰ N',
        'b' => '1.35×10⁹ N',
        'c' => '6.75×10⁹ N',
        'd' => '2.7×10⁹ N',
        'answer' => 'A',
        'explanation' => 'F = kq1q2/r² = 9×10⁹×6/4 = 1.35×10¹⁰ N.',
    ],
    [
        'question' => 'If focal length of convex lens is 10 cm and object distance is 20 cm, image distance is:',
        'a' => '5 cm',
        'b' => '10 cm',
        'c' => '15 cm',
        'd' => '20 cm',
        'answer' => 'D',
        'explanation' => '1/f =1/v+1/u → 1/10=1/v+1/20 → v=20 cm.',
    ],
    [
        'question' => 'A wire of resistance 4Ω carries 2A. Power dissipated is:',
        'a' => '8W',
        'b' => '12W',
        'c' => '16W',
        'd' => '20W',
        'answer' => 'C',
        'explanation' => 'P = I²R = 4×4 =16W.',
    ],
    [
        'question' => 'A 5 kg object slides down a frictionless incline of height 8 m. Its speed at the bottom (g = 10 m/s²) is:',
        'a' => '8 m/s',
        'b' => '10 m/s',
        'c' => '12 m/s',
        'd' => '16 m/s',
        'answer' => 'C',
        'explanation' => 'mgh = ½mv² → v = √(2gh) = √(160) ≈ 12.6 ≈ 12 m/s.',
    ],
    [
        'question' => 'A current of 4 A flows through a conductor for 2 minutes. The quantity of charge transferred is:',
        'a' => '120 C',
        'b' => '240 C',
        'c' => '360 C',
        'd' => '480 C',
        'answer' => 'D',
        'explanation' => 'Q = It = 4 × 120 = 480 C.',
    ],
    [
        'question' => 'A stone dropped from a height reaches the ground in 4 s. The height is approximately:',
        'a' => '40 m',
        'b' => '60 m',
        'c' => '80 m',
        'd' => '100 m',
        'answer' => 'C',
        'explanation' => 's = ½gt² = 5 × 16 = 80 m.',
    ],
    [
        'question' => 'Two forces 6 N and 8 N act at right angles. The resultant is:',
        'a' => '10 N',
        'b' => '12 N',
        'c' => '14 N',
        'd' => '48 N',
        'answer' => 'A',
        'explanation' => '√(6² + 8²) = √100 = 10 N.',
    ],
    [
        'question' => 'A wave has frequency 500 Hz and wavelength 0.8 m. Its speed is:',
        'a' => '200 m/s',
        'b' => '300 m/s',
        'c' => '400 m/s',
        'd' => '500 m/s',
        'answer' => 'C',
        'explanation' => 'v = fλ = 500 × 0.8 = 400 m/s.',
    ],
    [
        'question' => 'A body of mass 1 kg moving at 20 m/s collides inelastically with a stationary 3 kg body. Their common velocity after collision is:',
        'a' => '5 m/s',
        'b' => '10 m/s',
        'c' => '15 m/s',
        'd' => '20 m/s',
        'answer' => 'A',
        'explanation' => 'Momentum conserved → (1×20)/(4) = 5 m/s.',
    ],
    [
        'question' => 'If the length of a simple pendulum is increased fourfold, its period becomes:',
        'a' => 'Twice',
        'b' => 'Four times',
        'c' => 'Half',
        'd' => 'Unchanged',
        'answer' => 'A',
        'explanation' => 'T ∝ √L → √4 = 2.',
    ],
    [
        'question' => 'A heater rated 2 kW operates for 30 minutes. Energy consumed in kWh is:',
        'a' => '0.5',
        'b' => '1',
        'c' => '1.5',
        'd' => '2',
        'answer' => 'B',
        'explanation' => '2 × 0.5 hr = 1 kWh.',
    ],
    [
        'question' => 'A ray of light passes from air into glass (n = 1.5). If angle of incidence is 30°, angle of refraction is approximately:',
        'a' => '15°',
        'b' => '19°',
        'c' => '30°',
        'd' => '45°',
        'answer' => 'B',
        'explanation' => 'n = sin i / sin r → sin r = 0.5 /1.5 = 0.333 → r ≈ 19°.',
    ],
    [
        'question' => 'The pressure at a depth of 5 m in water (ρ = 1000 kg/m³, g=10) is:',
        'a' => '25,000 Pa',
        'b' => '50,000 Pa',
        'c' => '75,000 Pa',
        'd' => '100,000 Pa',
        'answer' => 'B',
        'explanation' => 'P = ρgh = 1000×10×5 = 50,000 Pa.',
    ],
    [
        'question' => 'If the efficiency of a machine is 80% and input power is 500 W, useful output power is:',
        'a' => '300 W',
        'b' => '350 W',
        'c' => '400 W',
        'd' => '450 W',
        'answer' => 'C',
        'explanation' => '0.8 × 500 = 400 W.',
    ],
    [
        'question' => 'An electron accelerated through a potential difference of 200 V gains kinetic energy of:',
        'a' => '200 J',
        'b' => '200 eV',
        'c' => '400 J',
        'd' => '400 eV',
        'answer' => 'B',
        'explanation' => '1 volt gives 1 eV → 200 V = 200 eV.',
    ],
    [
        'question' => 'A 10 Ω resistor is connected to a 20 V supply. Heat produced in 5 s is:',
        'a' => '100 J',
        'b' => '150 J',
        'c' => '200 J',
        'd' => '250 J',
        'answer' => 'C',
        'explanation' => 'P = V²/R = 400/10 = 40 W → E = 40×5 = 200 J.',
    ],
    [
        'question' => 'If the frequency of a sound wave increases while speed remains constant, the pitch:',
        'a' => 'Decreases',
        'b' => 'Remains same',
        'c' => 'Increases',
        'd' => 'Becomes zero',
        'answer' => 'C',
        'explanation' => 'Higher frequency → higher pitch.',
    ],
    [
        'question' => 'A radioactive substance has half-life of 5 years. Fraction remaining after 15 years is:',
        'a' => '1/2',
        'b' => '1/4',
        'c' => '1/6',
        'd' => '1/8',
        'answer' => 'D',
        'explanation' => '15 years = 3 half-lives → (1/2)³ = 1/8.',
    ],
];

$inserted = 0;
$failed = 0;

foreach ($questions as $index => $q) {
    try {
        DB::table('questions')->insert([
            'subject_id' => $physicsId,
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

// Show total questions in Physics
$total = DB::table('questions')->where('subject_id', $physicsId)->count();
echo "\nTotal Physics questions in database: $total\n";
