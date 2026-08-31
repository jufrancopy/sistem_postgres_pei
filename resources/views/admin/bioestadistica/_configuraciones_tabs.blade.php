@php
    $bioConfigTabs = \App\Application\Bioestadistica\BioestadisticaConfigNavigation::visibleTabs();
@endphp
@if($bioConfigTabs !== [])
    <div class="bio-config-tabs mb-3 mt-2">
        <ul class="bio-config-nav nav flex-wrap" role="tablist">
            @foreach($bioConfigTabs as $tab)
                <li class="nav-item" role="presentation">
                    <a
                        class="bio-config-nav-link nav-link {{ $tab['is_active'] ? 'active font-weight-bold' : '' }}"
                        href="{{ route($tab['route']) }}"
                        @if($tab['is_active']) aria-current="page" @endif
                    >{{ $tab['label'] }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
