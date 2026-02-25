@extends('layouts.admin')

@section('title', 'Exam Tokens')
@section('page-title', 'Exam Tokens Management')

@section('content')
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <div>
            <h3 class="card-title">
                <i class="bi bi-ticket-perforated-fill"></i> Exam Tokens
            </h3>
            <p style="color: #6b7280; margin: 0.25rem 0 0; font-size: 0.875rem;">Generate and manage exam access tokens</p>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            @if($tokens->total() > 0)
                <a href="{{ route('admin.tokens.print', ['all' => 1] + request()->only(['search', 'status'])) }}" 
                   class="btn btn-secondary" target="_blank" title="Print all filtered tokens">
                    <i class="bi bi-printer-fill"></i> Print All
                </a>
            @endif
            <a href="{{ route('admin.tokens.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Generate Tokens
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="{{ route('admin.tokens.index') }}" style="display: flex; gap: 1rem; align-items: end;">
        <div style="flex: 1;">
            <label class="form-label">Search Token Code</label>
            <input type="text" name="search" class="form-control" placeholder="Enter token code..." value="{{ request('search') }}">
        </div>
        <div style="width: 200px;">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="">All Tokens</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="used_up" {{ request('status') === 'used_up' ? 'selected' : '' }}>Fully Used</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary">
            <i class="bi bi-search"></i> Filter
        </button>
        @if(request()->anyFilled(['search', 'status']))
            <a href="{{ route('admin.tokens.index') }}" class="btn btn-light">
                <i class="bi bi-x-circle"></i> Clear
            </a>
        @endif
    </form>
</div>

<!-- Bulk Actions -->
@if($tokens->total() > 0)
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-body" style="padding: 1rem 1.5rem;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div>
                <strong style="color: #1f2937;"><i class="bi bi-trash-fill"></i> Bulk Delete Actions</strong>
                <p style="margin: 0.25rem 0 0; font-size: 0.875rem; color: #6b7280;">Delete multiple tokens at once by status</p>
            </div>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <form method="POST" action="{{ route('admin.tokens.bulk-delete') }}" 
                      onsubmit="return confirm('Delete all UNUSED tokens? This will permanently remove tokens that have never been used.');" 
                      style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="type" value="unused">
                    <button type="submit" class="btn btn-sm btn-warning">
                        <i class="bi bi-trash"></i> Delete Unused
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.tokens.bulk-delete') }}" 
                      onsubmit="return confirm('Delete all USED tokens? This will permanently remove tokens that have been used at least once.');" 
                      style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="type" value="used">
                    <button type="submit" class="btn btn-sm btn-warning">
                        <i class="bi bi-trash"></i> Delete Used
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.tokens.bulk-delete') }}" 
                      onsubmit="return confirm('Delete all EXPIRED tokens? This will permanently remove tokens past their expiration date.');" 
                      style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="type" value="expired">
                    <button type="submit" class="btn btn-sm btn-warning">
                        <i class="bi bi-trash"></i> Delete Expired
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.tokens.bulk-delete') }}" 
                      onsubmit="return confirm('Delete all FULLY USED tokens? This will permanently remove tokens that have reached their maximum usage limit.');" 
                      style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="type" value="fully_used">
                    <button type="submit" class="btn btn-sm btn-warning">
                        <i class="bi bi-trash"></i> Delete Fully Used
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Tokens List -->
<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Token Code</th>
                    <th>Status</th>
                    <th>Usage</th>
                    <th>Created By</th>
                    <th>Expires</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tokens as $token)
                    <tr>
                        <td>
                            <div style="font-family: 'Courier New', monospace; font-weight: 700; font-size: 1.1rem; letter-spacing: 1px; color: #1f2937;">
                                {{ $token->code }}
                            </div>
                            @if($token->notes)
                                <small style="color: #6b7280;">{{ $token->notes }}</small>
                            @endif
                        </td>
                        <td>
                            @if(!$token->is_active)
                                <span class="badge badge-danger">Inactive</span>
                            @elseif($token->expires_at && $token->expires_at->isPast())
                                <span class="badge badge-warning">Expired</span>
                            @elseif($token->used_count >= $token->max_uses)
                                <span class="badge badge-secondary">Fully Used</span>
                            @else
                                <span class="badge badge-success">Active</span>
                            @endif
                        </td>
                        <td>
                            <strong style="color: {{ $token->remainingUses() > 0 ? '#10b981' : '#ef4444' }};">
                                {{ $token->used_count }} / {{ $token->max_uses }}
                            </strong>
                            <br><small style="color: #6b7280;">{{ $token->remainingUses() }} remaining</small>
                        </td>
                        <td>
                            {{ $token->creator->name }}
                            <br><small style="color: #6b7280;">{{ $token->created_at->format('M d, Y H:i') }}</small>
                        </td>
                        <td>
                            @if($token->expires_at)
                                {{ $token->expires_at->format('M d, Y') }}
                                <br><small style="color: #6b7280;">{{ $token->expires_at->diffForHumans() }}</small>
                            @else
                                <span style="color: #6b7280;">No expiry</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <button type="button" class="btn btn-sm btn-info copy-token-btn" 
                                        data-token="{{ $token->code }}" title="Copy Token Code">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-success share-token-btn" 
                                        data-token="{{ $token->code }}"
                                        data-expires="{{ $token->expires_at ? $token->expires_at->format('M d, Y') : 'Never' }}"
                                        data-uses="{{ $token->remainingUses() }}" title="Share Token">
                                    <i class="bi bi-share"></i>
                                </button>
                                <a href="{{ route('admin.tokens.print', ['ids' => $token->id, 'download' => 1]) }}" 
                                   class="btn btn-sm btn-primary" target="_blank" title="Download">
                                    <i class="bi bi-download"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.tokens.toggle', $token) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $token->is_active ? 'btn-warning' : 'btn-success' }}" 
                                            title="{{ $token->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="bi bi-{{ $token->is_active ? 'pause-circle' : 'play-circle' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.tokens.destroy', $token) }}" 
                                      onsubmit="return confirm('Delete this token? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @if($token->usages->isNotEmpty())
                        <tr style="background: #f9fafb;">
                            <td colspan="6" style="padding: 1rem 2rem;">
                                <details>
                                    <summary style="cursor: pointer; font-weight: 600; color: #6b7280;">
                                        <i class="bi bi-people"></i> Usage History ({{ $token->usages->count() }})
                                    </summary>
                                    <div style="margin-top: 0.75rem; padding-left: 1.5rem;">
                                        @foreach($token->usages as $usage)
                                            <div style="padding: 0.5rem 0; border-bottom: 1px solid #e5e7eb;">
                                                <strong>{{ $usage->user->name }}</strong> ({{ $usage->user->student_id ?? 'No ID' }})
                                                - Used on {{ $usage->used_at->format('M d, Y H:i') }}
                                            </div>
                                        @endforeach
                                    </div>
                                </details>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 3rem; color: #6b7280;">
                            <i class="bi bi-ticket" style="font-size: 3rem; display: block; margin-bottom: 1rem;"></i>
                            <strong style="display: block; margin-bottom: 0.5rem;">No Tokens Found</strong>
                            <p style="margin: 0;">Generate exam tokens to control exam access</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($tokens->hasPages())
        <div style="padding: 1.5rem;">
            {{ $tokens->links() }}
        </div>
    @endif
</div>

<style>
.copy-token-btn.copied,
.share-token-btn.shared {
    background-color: #10b981 !important;
    border-color: #10b981 !important;
    color: white !important;
}

/* Share Modal */
.share-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.share-modal.active { display: flex; }

.share-modal-content {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
}

.share-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.share-modal-header h3 {
    margin: 0;
    color: #1f2937;
}

.share-close-btn {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #6b7280;
    padding: 0;
    width: 32px;
    height: 32px;
}

.share-options {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.share-text-box {
    padding: 1rem;
    background: #f9fafb;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-family: monospace;
    font-size: 0.875rem;
    color: #1f2937;
    white-space: pre-wrap;
    word-break: break-word;
}

.share-btn-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    margin-top: 1rem;
}

.share-btn {
    padding: 0.75rem 1rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
}

.share-btn-primary {
    background: #4f46e5;
    color: white;
}

.share-btn-primary:hover {
    background: #4338ca;
}

.share-btn-secondary {
    background: #e5e7eb;
    color: #374151;
}

.share-btn-secondary:hover {
    background: #d1d5db;
}

/* Mobile Responsive (max-width: 768px) */
@media (max-width: 768px) {
    .card-header > div:last-child {
        width: 100%;
        flex-wrap: wrap;
    }

    .card-header .btn {
        width: 100%;
    }

    form[method="GET"] {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 0.75rem !important;
    }

    form[method="GET"] > div {
        width: 100% !important;
    }

    .table-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table-container table {
        min-width: 720px;
    }

    .share-modal-content {
        padding: 1.25rem;
    }

    .share-btn-group {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- Share Modal -->
<div id="shareModal" class="share-modal">
    <div class="share-modal-content">
        <div class="share-modal-header">
            <h3><i class="bi bi-share-fill"></i> Share Token</h3>
            <button class="share-close-btn" onclick="closeShareModal()">
                <i class="bi bi-x"></i>
            </button>
        </div>
        <div class="share-options">
            <div class="share-text-box" id="shareText"></div>
            <div class="share-btn-group">
                <button class="share-btn share-btn-primary" onclick="copyShareText()">
                    <i class="bi bi-clipboard"></i> Copy Text
                </button>
                <button class="share-btn share-btn-secondary" onclick="closeShareModal()">
                    <i class="bi bi-x-circle"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentShareText = '';

document.addEventListener('DOMContentLoaded', function() {
    // Copy Token Buttons
    const copyButtons = document.querySelectorAll('.copy-token-btn');
    
    copyButtons.forEach(button => {
        button.addEventListener('click', async function() {
            const tokenCode = this.getAttribute('data-token');
            const icon = this.querySelector('i');
            const originalClass = icon.className;
            
            try {
                await navigator.clipboard.writeText(tokenCode);
                
                // Visual feedback
                this.classList.add('copied');
                icon.className = 'bi bi-check-circle-fill';
                
                // Reset after 2 seconds
                setTimeout(() => {
                    this.classList.remove('copied');
                    icon.className = originalClass;
                }, 2000);
            } catch (err) {
                // Fallback
                const textarea = document.createElement('textarea');
                textarea.value = tokenCode;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                
                this.classList.add('copied');
                icon.className = 'bi bi-check-circle-fill';
                setTimeout(() => {
                    this.classList.remove('copied');
                    icon.className = originalClass;
                }, 2000);
            }
        });
    });

    // Share Token Buttons
    const shareButtons = document.querySelectorAll('.share-token-btn');
    
    shareButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tokenCode = this.getAttribute('data-token');
            const expires = this.getAttribute('data-expires');
            const uses = this.getAttribute('data-uses');
            
            const shareText = `🎓 EXAM ACCESS TOKEN\n\nToken Code: ${tokenCode}\n\nExpires: ${expires}\nRemaining Uses: ${uses}\n\nInstructions:\n1. Log in to your student account\n2. Select JAMB exam mode\n3. Choose your 3 subjects\n4. Enter this token code when prompted\n5. Start your exam\n\n⚠️ Keep this token secure and do not share unnecessarily.`;
            
            currentShareText = shareText;
            document.getElementById('shareText').textContent = shareText;
            document.getElementById('shareModal').classList.add('active');
        });
    });

    // Close modal when clicking outside
    document.getElementById('shareModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeShareModal();
        }
    });
});

function closeShareModal() {
    document.getElementById('shareModal').classList.remove('active');
}

function copyShareText() {
    navigator.clipboard.writeText(currentShareText).then(() => {
        const btn = event.target.closest('button');
        const icon = btn.querySelector('i');
        const originalClass = icon.className;
        
        icon.className = 'bi bi-check-circle-fill';
        btn.textContent = '';
        btn.appendChild(icon);
        btn.appendChild(document.createTextNode(' Copied!'));
        
        setTimeout(() => {
            icon.className = originalClass;
            btn.textContent = '';
            btn.appendChild(icon);
            btn.appendChild(document.createTextNode(' Copy Text'));
        }, 2000);
    });
}
</script>
@endsection
