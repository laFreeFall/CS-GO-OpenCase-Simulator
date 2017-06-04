<div class="case col-lg-3 col-md-4 col-sm-6">
    <div class="row">
        <h4 class="case-title text-center"><a href="{{ action('CasesController@show', $case->id) }}">{{ $case->title }}</a></h4>
    </div>
    <p class="case-description text-center">{{ $case->description }}</p>
    <a href="{{ action('CasesController@show', $case->id) }}"><img src="{{ asset('storage/images/cases/' . $case->image) }}" alt="{{ $case->title }}" class="case-image {{ $case->collection ? 'collection-image' : '' }} center-block tooltipq" data-tooltip-content="#tooltip-case-{{ $case->id }}"></a>
    @unless($case->collection)
        <p class="case-price text-center">${{ number_format($case->price, 2) }}</p>
    @endunless
    <a href="{{ action('CasesController@shop', $case->id) }}" class="btn btn-info center-block shop-button">Shop</a>
    <br>
    <div class="tooltip_templates">
        <div id="tooltip-case-{{ $case->id }}">
            <h4 class="text-center">{{ $case->title }}</h4>
            <p class="case-tooltip-souvenir text-center">{{ $case->souvenir ? 'Souvenirs available' : '' }}</p>
            @if(count($case->weapons))
                @foreach($case->weapons as $weapon)
                    <p class="tooltip-case-weapon item-color-{{ strtolower($weapon->rarity->title) }}">
                        {{ $weapon->weaponName->title }} | {{ $weapon->weaponPattern->title }}
                    </p>
                @endforeach
                @unless($case->collection)
                    <p class="tooltip-case-weapon item-color-rare">Exceedingly Rare Item</p>
                @endunless
            @else
                <p class="tooltip-case-weapon"> There are no skins atm.</p>
            @endif
        </div>
    </div>
</div>