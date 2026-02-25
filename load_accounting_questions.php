<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Get Accounting subject ID
$accounting = DB::table('subjects')->where('name', 'Accounting')->first();
if ($accounting) {
    echo "Accounting subject found with ID: {$accounting->id}\n";
    $accountingId = $accounting->id;
} else {
    echo "Accounting subject not found. Creating it...\n";
    $accountingId = DB::table('subjects')->insertGetId([
        'name' => 'Accounting',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

$questions = [
    [
        'question' => 'The primary objective of preparing financial statements is to:',
        'a' => 'Detect fraud in the business',
        'b' => 'Ascertain the financial position and performance of the business',
        'c' => 'Determine tax payable',
        'd' => 'Satisfy government regulations',
        'answer' => 'B',
        'explanation' => 'Financial statements show profitability and financial position.',
    ],
    [
        'question' => 'A transaction that increases assets and increases liabilities is:',
        'a' => 'Purchase of goods for cash',
        'b' => 'Receipt of cash from debtor',
        'c' => 'Purchase of goods on credit',
        'd' => 'Payment to creditor',
        'answer' => 'C',
        'explanation' => 'Inventory increases (asset) and creditor increases (liability).',
    ],
    [
        'question' => 'If capital at the beginning was ₦50,000 and capital at the end is ₦80,000 with no additional investment, profit is:',
        'a' => '₦20,000',
        'b' => '₦25,000',
        'c' => '₦30,000',
        'd' => '₦35,000',
        'answer' => 'C',
        'explanation' => 'Increase in capital = 80,000 − 50,000 = ₦30,000 profit.',
    ],
    [
        'question' => 'Which error will not affect the agreement of trial balance?',
        'a' => 'Posting wrong amount on one side',
        'b' => 'Omission of transaction',
        'c' => 'Posting to wrong side',
        'd' => 'Error in addition',
        'answer' => 'B',
        'explanation' => 'Complete omission affects neither debit nor credit totals.',
    ],
    [
        'question' => 'A bank reconciliation statement is prepared to:',
        'a' => 'Detect theft',
        'b' => 'Reconcile differences between cash book and bank statement',
        'c' => 'Calculate bank charges',
        'd' => 'Determine profit',
        'answer' => 'B',
        'explanation' => 'It matches cash book balance with bank statement balance.',
    ],
    [
        'question' => 'A trader bought goods worth ₦120,000 and was allowed 10% trade discount. Amount recorded in purchases account is:',
        'a' => '₦108,000',
        'b' => '₦110,000',
        'c' => '₦120,000',
        'd' => '₦132,000',
        'answer' => 'A',
        'explanation' => '10% of 120,000 = 12,000 → 120,000 − 12,000 = ₦108,000.',
    ],
    [
        'question' => 'The imprest system of petty cash ensures that:',
        'a' => 'Petty cash is unlimited',
        'b' => 'Petty cashier replaces funds when exhausted',
        'c' => 'Petty cash float remains constant',
        'd' => 'Expenses are minimized',
        'answer' => 'C',
        'explanation' => 'Fixed float is maintained under imprest system.',
    ],
    [
        'question' => 'Which account is debited when goods are returned to supplier?',
        'a' => 'Sales returns',
        'b' => 'Purchases',
        'c' => 'Purchases returns',
        'd' => 'Debtors',
        'answer' => 'C',
        'explanation' => 'Purchases returns (returns outward) is debited.',
    ],
    [
        'question' => 'Opening stock ₦20,000; Purchases ₦80,000; Closing stock ₦30,000. Cost of goods sold is:',
        'a' => '₦70,000',
        'b' => '₦80,000',
        'c' => '₦90,000',
        'd' => '₦100,000',
        'answer' => 'A',
        'explanation' => '20,000 + 80,000 − 30,000 = ₦70,000.',
    ],
    [
        'question' => 'Provision for doubtful debts is created to:',
        'a' => 'Increase expenses',
        'b' => 'Reduce profit artificially',
        'c' => 'Provide for possible bad debts',
        'd' => 'Increase assets',
        'answer' => 'C',
        'explanation' => 'It anticipates potential bad debts.',
    ],
    [
        'question' => 'Which ratio measures liquidity?',
        'a' => 'Gross profit ratio',
        'b' => 'Current ratio',
        'c' => 'Return on capital employed',
        'd' => 'Net profit ratio',
        'answer' => 'B',
        'explanation' => 'Current ratio assesses short-term solvency.',
    ],
    [
        'question' => 'Capital expenditure differs from revenue expenditure because it:',
        'a' => 'Is recurring',
        'b' => 'Maintains earning capacity',
        'c' => 'Increases earning capacity',
        'd' => 'Is always paid in cash',
        'answer' => 'C',
        'explanation' => 'Capital expenditure improves earning potential.',
    ],
    [
        'question' => 'If sales are ₦500,000 and gross profit is ₦125,000, gross profit ratio is:',
        'a' => '20%',
        'b' => '25%',
        'c' => '30%',
        'd' => '40%',
        'answer' => 'B',
        'explanation' => '125,000 / 500,000 ×100 = 25%.',
    ],
    [
        'question' => 'Which document serves as evidence of credit purchase?',
        'a' => 'Invoice',
        'b' => 'Receipt',
        'c' => 'Debit note',
        'd' => 'Credit note',
        'answer' => 'A',
        'explanation' => 'Invoice is issued by seller for credit transaction.',
    ],
    [
        'question' => 'Depreciation is charged to:',
        'a' => 'Increase assets',
        'b' => 'Reduce liabilities',
        'c' => 'Spread cost of asset over useful life',
        'd' => 'Show market value',
        'answer' => 'C',
        'explanation' => 'Depreciation allocates cost over asset\'s life.',
    ],
    [
        'question' => 'A machine costing ₦100,000 is depreciated at 10% per annum on straight-line basis. Annual depreciation is:',
        'a' => '₦9,000',
        'b' => '₦10,000',
        'c' => '₦11,000',
        'd' => '₦12,000',
        'answer' => 'B',
        'explanation' => '10% of 100,000 = ₦10,000.',
    ],
    [
        'question' => 'Which account is credited when capital is introduced in cash?',
        'a' => 'Capital',
        'b' => 'Cash',
        'c' => 'Drawings',
        'd' => 'Revenue',
        'answer' => 'A',
        'explanation' => 'Capital increases → credited.',
    ],
    [
        'question' => 'A suspense account is opened when:',
        'a' => 'Trial balance agrees',
        'b' => 'Errors are detected',
        'c' => 'Trial balance disagrees',
        'd' => 'Profit is calculated',
        'answer' => 'C',
        'explanation' => 'It temporarily balances the trial balance.',
    ],
    [
        'question' => 'Goodwill is classified as:',
        'a' => 'Current asset',
        'b' => 'Fixed asset',
        'c' => 'Intangible asset',
        'd' => 'Fictitious asset',
        'answer' => 'C',
        'explanation' => 'Goodwill has no physical form.',
    ],
    [
        'question' => 'If net profit is ₦60,000 and capital employed is ₦300,000, return on capital employed is:',
        'a' => '10%',
        'b' => '15%',
        'c' => '20%',
        'd' => '25%',
        'answer' => 'C',
        'explanation' => '60,000 / 300,000 ×100 = 20%.',
    ],
    [
        'question' => 'Which account records goods taken by owner for personal use?',
        'a' => 'Purchases',
        'b' => 'Drawings',
        'c' => 'Sales',
        'd' => 'Capital',
        'answer' => 'B',
        'explanation' => 'Personal withdrawal reduces capital.',
    ],
    [
        'question' => 'Control accounts are prepared mainly to:',
        'a' => 'Detect fraud',
        'b' => 'Check arithmetic accuracy of personal accounts',
        'c' => 'Increase profit',
        'd' => 'Record cash',
        'answer' => 'B',
        'explanation' => 'Control accounts verify ledger accuracy.',
    ],
    [
        'question' => 'In partnership, profit-sharing ratio applies when:',
        'a' => 'No agreement exists',
        'b' => 'Agreed ratio exists',
        'c' => 'Partners disagree',
        'd' => 'Capital is equal',
        'answer' => 'B',
        'explanation' => 'Profit is shared according to agreement.',
    ],
    [
        'question' => 'Which is NOT a feature of public sector accounting?',
        'a' => 'Budgetary control',
        'b' => 'Profit maximization',
        'c' => 'Fund accounting',
        'd' => 'Legislative control',
        'answer' => 'B',
        'explanation' => 'Public sector is not profit-oriented.',
    ],
    [
        'question' => 'A debtor owing ₦50,000 is allowed 5% cash discount. Amount received is:',
        'a' => '₦45,000',
        'b' => '₦47,500',
        'c' => '₦48,000',
        'd' => '₦49,500',
        'answer' => 'B',
        'explanation' => '5% of 50,000 = 2,500 → 50,000 − 2,500 = 47,500.',
    ],
    [
        'question' => 'Which stock valuation method gives higher profit during rising prices?',
        'a' => 'FIFO',
        'b' => 'LIFO',
        'c' => 'Average cost',
        'd' => 'Weighted cost',
        'answer' => 'A',
        'explanation' => 'FIFO issues cheaper stock first, leaving higher closing stock.',
    ],
    [
        'question' => 'Revenue reserve is created from:',
        'a' => 'Capital',
        'b' => 'Profit',
        'c' => 'Loan',
        'd' => 'Share premium',
        'answer' => 'B',
        'explanation' => 'Revenue reserve comes from retained earnings.',
    ],
    [
        'question' => 'Which of the following reduces net profit?',
        'a' => 'Prepaid expense',
        'b' => 'Accrued income',
        'c' => 'Outstanding expense',
        'd' => 'Capital introduced',
        'answer' => 'C',
        'explanation' => 'Outstanding expense increases expenses.',
    ],
    [
        'question' => 'A manufacturing account is prepared to determine:',
        'a' => 'Net profit',
        'b' => 'Gross profit',
        'c' => 'Cost of production',
        'd' => 'Cost of sales',
        'answer' => 'C',
        'explanation' => 'It calculates production cost.',
    ],
    [
        'question' => 'Which account appears in balance sheet only?',
        'a' => 'Purchases',
        'b' => 'Sales',
        'c' => 'Cash',
        'd' => 'Wages',
        'answer' => 'C',
        'explanation' => 'Cash is asset shown in balance sheet.',
    ],
    [
        'question' => 'If liabilities exceed assets, the business is:',
        'a' => 'Solvent',
        'b' => 'Insolvent',
        'c' => 'Profitable',
        'd' => 'Liquid',
        'answer' => 'B',
        'explanation' => 'Insolvency occurs when liabilities > assets.',
    ],
    [
        'question' => 'Which error is corrected by journal entry?',
        'a' => 'Casting error',
        'b' => 'Error of principle',
        'c' => 'Omission',
        'd' => 'All of the above',
        'answer' => 'D',
        'explanation' => 'Journal entries correct ledger errors.',
    ],
    [
        'question' => 'Which is a current liability?',
        'a' => 'Building',
        'b' => 'Loan repayable in 10 years',
        'c' => 'Trade creditor',
        'd' => 'Goodwill',
        'answer' => 'C',
        'explanation' => 'Creditors are short-term obligations.',
    ],
    [
        'question' => 'The accounting concept requiring business and owner to be treated separately is:',
        'a' => 'Accrual',
        'b' => 'Consistency',
        'c' => 'Business entity',
        'd' => 'Prudence',
        'answer' => 'C',
        'explanation' => 'Owner and business are distinct entities.',
    ],
    [
        'question' => 'Which document is issued when goods are returned by customer?',
        'a' => 'Debit note',
        'b' => 'Credit note',
        'c' => 'Invoice',
        'd' => 'Receipt',
        'answer' => 'B',
        'explanation' => 'Seller issues credit note.',
    ],
    [
        'question' => 'Net realizable value of stock equals:',
        'a' => 'Cost − expenses to complete',
        'b' => 'Selling price − selling expenses',
        'c' => 'Cost + profit',
        'd' => 'Cost − discount',
        'answer' => 'B',
        'explanation' => 'NRV = estimated selling price minus selling expenses.',
    ],
    [
        'question' => 'Share capital of a company is shown under:',
        'a' => 'Current liabilities',
        'b' => 'Fixed assets',
        'c' => 'Equity',
        'd' => 'Revenue',
        'answer' => 'C',
        'explanation' => 'It represents owners\' equity.',
    ],
    [
        'question' => 'Which ratio measures profitability?',
        'a' => 'Acid test ratio',
        'b' => 'Debt ratio',
        'c' => 'Net profit ratio',
        'd' => 'Current ratio',
        'answer' => 'C',
        'explanation' => 'It measures profit relative to sales.',
    ],
    [
        'question' => 'The book used to record daily cash transactions is:',
        'a' => 'Journal',
        'b' => 'Cash book',
        'c' => 'Ledger',
        'd' => 'Trial balance',
        'answer' => 'B',
        'explanation' => 'Cash book records receipts and payments.',
    ],
    [
        'question' => 'The principle that requires anticipated losses to be recorded but not anticipated gains is:',
        'a' => 'Matching',
        'b' => 'Prudence',
        'c' => 'Consistency',
        'd' => 'Materiality',
        'answer' => 'B',
        'explanation' => 'Prudence avoids overstatement of profit.',
    ],
];

$inserted = 0;
$failed = 0;

foreach ($questions as $index => $q) {
    try {
        DB::table('questions')->insert([
            'subject_id' => $accountingId,
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

// Show total questions in Accounting
$total = DB::table('questions')->where('subject_id', $accountingId)->count();
echo "\nTotal Accounting questions in database: $total\n";
