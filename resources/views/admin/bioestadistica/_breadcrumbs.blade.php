@php
    $items = $items ?? [];
    $showConfigTabs = $showConfigTabs ?? \App\Application\Bioestadistica\BioestadisticaConfigNavigation::isConfigRoute();
@endphp
<nav aria-label="breadcrumb" class="bio-breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('bioestadistica.dashboard') }}">Bioestadística</a>
        </li>
        @foreach($items as $item)
            @if(!empty($item['url']))
                <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
            @else
                <li class="breadcrumb-item active" aria-current="page">{{ $item['label'] }}</li>
            @endif
        @endforeach
    </ol>
</nav>
@if($showConfigTabs)
    @include('admin.bioestadistica._configuraciones_tabs')
@endif
