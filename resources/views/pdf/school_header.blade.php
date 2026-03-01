@once
<style>
    .school-header-fixed {
        position: fixed;
        top: 0;
        width: 100%;
        text-align: center;
        font-weight: 700;
        font-size: 16px;
        padding: 8px 0;
        background: transparent;
        z-index: 1000;
    }
    body { /* add top padding to avoid overlap with fixed header */
        padding-top: 32px;
    }
    .school-watermark {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-30deg);
        font-size: 60px;
        color: #000;
        opacity: 0.08;
        z-index: -1000;
        white-space: nowrap;
        pointer-events: none;
    }
</style>
@endonce

@if(!empty($schoolName))
    <div class="school-header-fixed">{{ $schoolName }}</div>
    <div class="school-watermark">{{ $schoolName }}</div>
@endif
