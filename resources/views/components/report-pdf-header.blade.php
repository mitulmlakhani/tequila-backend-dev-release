@props([
    'title' => '',
    'subTitle' => '',
    'startDate' => '',
    'endDate' => null,
    'restaurantName' => '',
    'restaurantAddress' => '',
    'restaurantPhone' => '',
    'showDateRange' => true,
])

<style>
    .report-heading-container { margin-bottom: 2pt; }
    .report-header-info, .report-header-meta { text-align: center; }

    .report-title { font-size: 10pt; font-weight: 700; margin: 0; }
    .report-contact-info, .report-description { font-size: 10pt; margin: 0; color: #333; line-height: 10pt !important; }
    .report-contact-info p, .report-description { padding: 0; }

    .report-subtitle { font-size: 10pt; font-weight: 700; margin: 3pt 0 1pt 0; line-height: 10pt !important; }
    .report-date-range { margin: 0; border-bottom: 1px solid #222; line-height: 10pt !important; }
    .date-text { font-size: 8pt; color: #333; }

    h2, p {
        margin: 0;
        padding: 0;
    }
</style>

<div class="report-heading-container">

    {{-- Restaurant Info --}}
    <div class="report-header-info">
        <p class="report-title">{{ $restaurantName }}</p>

        @if($restaurantAddress || $restaurantPhone)
            <div class="report-contact-info">
                @foreach([$restaurantAddress, $restaurantPhone] as $info)
                    @if($info)
                        <p>{{ $info }}</p>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    {{-- Report Meta --}}
    <div class="report-header-meta">
        @if($title)
            <h2 class="report-subtitle">{{ $title }}</h2>
        @endif

        @if($showDateRange && $startDate)
            <div class="report-date-range">
                <span class="date-text">
                    {{ $startDate }}{{ $endDate ? " - $endDate" : '' }}
                </span>
            </div>
        @endif

        @if($subTitle)
            <p class="report-description">{{ $subTitle }}</p>
        @endif
    </div>

</div>