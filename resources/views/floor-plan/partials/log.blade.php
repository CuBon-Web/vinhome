<div class="fp-log">
    @foreach ($journalDays as $day)
    <article class="fp-log-day">
        <div class="fp-log-node"></div>
        <div class="fp-log-date">
            @if (!empty($day['dow']))<b>{{ $day['dow'] }}</b>@endif
            @if (!empty($day['date']))<span>{{ $day['date'] }}</span>@endif
        </div>
        <div class="fp-log-card">
            <div class="fp-log-card-top">
                @if (!empty($day['title']))<h3>{{ $day['title'] }}</h3>@endif
                @if (!empty($day['time']))<time>{{ $day['time'] }}</time>@endif
            </div>
            @foreach ($day['groups'] as $group)
            <div class="fp-log-group">
                @if (!empty($group['name']))
                <h4>{{ $group['name'] }}</h4>
                @endif
                <ul>
                    @foreach ($group['items'] as $item)
                    <li>
                        @if (!empty($item['label']))<b>{{ $item['label'] }}</b> – @endif
                        {{ $item['text'] ?? '' }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </article>
    @endforeach
</div>
