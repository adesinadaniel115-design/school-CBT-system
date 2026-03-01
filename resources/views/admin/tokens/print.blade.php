<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Print Exam Tokens</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4;
            margin: 5mm;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            
            .header {
                display: none !important;
            }
            
            .instructions {
                display: none !important;
            }
            
            body {
                background: white !important;
                padding: 3mm !important;
                margin: 0 !important;
                color: #000 !important;
            }
            
            .container {
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
                background: white !important;
                margin: 0 !important;
            }
            
            .scratch-card {
                page-break-inside: avoid;
                box-shadow: none !important;
                visibility: visible !important;
                opacity: 1 !important;
                display: block !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            
            .tokens-grid {
                gap: 3mm !important;
                display: grid !important;
                grid-template-columns: repeat(3, 1fr) !important;
                justify-content: center !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
            
            .card-header-section,
            .card-body-section,
            .card-footer-section,
            .token-code-section,
            .status-badge,
            .card-notes,
            .card-copyright {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            padding: 5px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            color-adjust: exact;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            color-adjust: exact;
        }

        .header h1 {
            margin: 0 0 5px 0;
            font-size: 20px;
            font-weight: 700;
        }

        .header p {
            margin: 0;
            opacity: 0.95;
            font-size: 12px;
        }

        .print-button {
            margin: 20px 0;
            text-align: center;
        }

        .print-button button {
            padding: 14px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            transition: transform 0.2s ease;
        }

        .print-button button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .instructions {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 2px solid #fbbf24;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            font-size: 14px;
            color: #92400e;
            box-shadow: 0 2px 10px rgba(251, 191, 36, 0.2);
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            color-adjust: exact;
        }

        .instructions h3 {
            margin: 0 0 12px 0;
            font-size: 17px;
            font-weight: 700;
        }

        .instructions ol {
            margin: 10px 0 0 0;
            padding-left: 22px;
        }

        .instructions li {
            margin: 8px 0;
            line-height: 1.5;
        }

        /* Scratch Card Grid */
        .tokens-grid {
            display: grid;
            /* 3-column layout for tighter A4 packing (8-10+ cards per page) */
            grid-template-columns: repeat(3, 1fr);
            gap: 3mm;
            margin: 0 auto;
            justify-content: center;
            width: 100%;
            max-width: 210mm;
        }

        /* Scratch Card Design */
        .scratch-card {
            width: 100%;
            aspect-ratio: 85 / 57;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            padding: 2px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            position: relative;
            overflow: hidden;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            color-adjust: exact;
        }

        .scratch-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 10px,
                rgba(255, 255, 255, 0.03) 10px,
                rgba(255, 255, 255, 0.03) 20px
            );
            pointer-events: none;
        }

        .card-inner {
            background: white;
            height: 100%;
            border-radius: 10px;
            padding: 0;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            visibility: visible !important;
            opacity: 1 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            color-adjust: exact;
        }

        .card-header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 4px 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            font-size: 0.8rem;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            color-adjust: exact;
        }

        .card-logo {
            display: flex;
            align-items: center;
            gap: 3px;
            font-weight: 700;
            font-size: 9px;
        }

        .card-logo-icon {
            width: 16px;
            height: 16px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            color-adjust: exact;
        }

        .card-type {
            font-size: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
            font-weight: 600;
        }

        /* Card Body */
        .card-body-section {
            flex: 1;
            padding: 5px 8px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: linear-gradient(to bottom, #ffffff 0%, #f9fafb 100%);
            font-size: 0.75rem;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            color-adjust: exact;
        }

        .token-label {
            font-size: 5px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .token-code-section {
            background: #f3f4f6;
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            padding: 5px 8px;
            text-align: center;
            margin: 3px 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            color-adjust: exact;
        }

        .token-code-value {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            font-weight: 900;
            color: #1f2937;
            letter-spacing: 1.5px;
            user-select: all;
        }

        .scratch-area-label {
            font-size: 4px;
            color: #9ca3af;
            margin-top: 2px;
            font-style: italic;
        }

        /* Card Details */
        .card-details {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 3px;
            margin-top: 3px;
        }

        .detail-item {
            text-align: center;
        }

        .detail-label {
            font-size: 5px;
            color: #9ca3af;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .detail-value {
            font-size: 7px;
            color: #1f2937;
            font-weight: 700;
            margin-top: 1px;
        }

        /* Card Footer */
        .card-footer-section {
            background: linear-gradient(to bottom, #f3f4f6, #e5e7eb);
            padding: 0;
            border-top: 1px solid #e5e7eb;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            color-adjust: exact;
        }

        .footer-top {
            padding: 3px 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.7rem;
        }

        .serial-number {
            font-size: 5px;
            color: #6b7280;
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }

        .card-status {
            display: flex;
            align-items: center;
            gap: 2px;
        }

        .status-badge {
            font-size: 5px;
            padding: 1px 4px;
            border-radius: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            color-adjust: exact;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            color-adjust: exact;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            color-adjust: exact;
        }

        .status-expired {
            background: #fed7aa;
            color: #92400e;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            color-adjust: exact;
        }

        /* Notes Section */
        .card-notes {
            font-size: 5px;
            color: #6b7280;
            margin-top: 3px;
            padding: 3px 6px;
            background: #fef3c7;
            border-radius: 4px;
            border-left: 2px solid #fbbf24;
            font-style: italic;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            color-adjust: exact;
        }

        /* Copyright Notice on Card */
        .card-copyright {
            background: #e5e7eb;
            padding: 2px 6px;
            text-align: center;
            font-size: 5px;
            color: #1f2937;
            border-top: 1px solid #d1d5db;
            font-weight: 700;
            letter-spacing: 0.3px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            color-adjust: exact;
        }

        /* Back Link */
        .back-link {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .back-link p {
            color: #6b7280;
            font-size: 14px;
            margin: 5px 0;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .back-link a:hover {
            color: #764ba2;
        }
    </style>
</head>
<body>
    <div class="container">
        @php
            $commonCenter = null;
            if ($tokens->count() > 0) {
                $firstCenter = $tokens->first()->center;
                if ($firstCenter && $tokens->every(fn($t) => $t->center_id === $firstCenter->id)) {
                    $commonCenter = $firstCenter->name;
                }
            }
        @endphp
        <div class="header no-print">
            <h1>🎓 School CBT Exam Tokens</h1>
            <p>Professional Exam Access Cards - Generated on {{ now()->format('F d, Y h:i A') }}</p>
            @if($commonCenter)
                <p style="font-size:12px; margin-top:4px;">Center: {{ $commonCenter }}</p>
            @endif
        </div>

        <div class="no-print instructions">
            <h3>📋 Instructions for Distribution:</h3>
            <ol>
                <li>Cut along the card borders before distributing to students</li>
                <li>Each card contains a unique token code for exam access</li>
                <li>Students should keep their token cards secure and confidential</li>
                <li>Token codes are case-insensitive (ABC-DEF-GHI = abc-def-ghi)</li>
                <li>Instruct students to enter the code when starting their JAMB exam</li>
                <li>Monitor usage to ensure tokens are not shared beyond their limits</li>
            </ol>
        </div>

        <div class="no-print print-button">
            <button onclick="window.print()">🖨️ Print Token Cards</button>
        </div>

        <div class="tokens-grid">
            @foreach($tokens as $token)
                <div class="scratch-card">
                    <div class="card-inner">
                        <!-- Card Header -->
                        <div class="card-header-section">
                            <div class="card-logo">
                                <div class="card-logo-icon">🎓</div>
                                <span>SchoolCBT</span>
                            </div>
                            <div class="card-type">Exam Token</div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body-section">
                            <div>
                                <div class="token-label">⚡ Access Code</div>
                                <div class="token-code-section">
                                    <div class="token-code-value">{{ $token->code }}</div>
                                    <div class="scratch-area-label">Scratch to reveal • Keep confidential</div>
                                </div>
                            </div>

                            <div class="card-details">
                                <div class="detail-item">
                                    <div class="detail-label">Max Uses</div>
                                    <div class="detail-value">{{ $token->max_uses }}</div>
                                </div>
                                @if($token->center)
                                <div class="detail-item">
                                    <div class="detail-label">Center</div>
                                    <div class="detail-value" style="font-size:9px;">{{ $token->center->name }}</div>
                                </div>
                                @endif
                                <div class="detail-item">
                                    <div class="detail-label">Remaining</div>
                                    <div class="detail-value" style="color: {{ $token->remainingUses() > 0 ? '#10b981' : '#ef4444' }};">
                                        {{ $token->remainingUses() }}
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Expires</div>
                                    <div class="detail-value" style="font-size: 8px;">
                                        @if($token->expires_at)
                                            {{ $token->expires_at->format('d/m/Y') }}
                                        @else
                                            Never
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($token->notes)
                                <div class="card-notes">
                                    📝 {{ $token->notes }}
                                </div>
                            @endif
                        </div>

                        <!-- Card Footer -->
                        <div class="card-footer-section">
                            <div class="footer-top">
                                <div class="serial-number">
                                    SN: {{ str_pad($token->id, 8, '0', STR_PAD_LEFT) }}
                                </div>
                                <div class="card-status">
                                    @if(!$token->is_active)
                                        <span class="status-badge status-inactive">Inactive</span>
                                    @elseif($token->expires_at && $token->expires_at->isPast())
                                        <span class="status-badge status-expired">Expired</span>
                                    @else
                                        <span class="status-badge status-active">Active</span>
                                    @endif
                                </div>
                            </div>
                            <!-- Copyright Notice -->
                            <div class="card-copyright">
                                © 2026 El-Bethel Digital Learning Systems
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="no-print back-link">
            <p><strong>Generated {{ $tokens->count() }} token card(s)</strong></p>
            <p><a href="{{ route('admin.tokens.index') }}">← Back to Token Management</a></p>
            <p style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 13px;">
                <strong style="color: #1f2937;">CBT Platform</strong> © 2026 El-Bethel Digital Learning Systems.
            </p>
        </div>
    </div>

    <script>
        // Auto-print on page load - delay longer to ensure DOM is fully rendered
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('autoprint') === '1' || urlParams.get('download') === '1') {
                // Give browser more time to render all content
                setTimeout(() => {
                    window.print();
                }, 1000);
            }
        });
    </script>
</body>
</html>
