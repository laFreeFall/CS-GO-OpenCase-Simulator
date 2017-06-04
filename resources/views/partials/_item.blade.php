@unless($roulette)
<div class="{{ $col == 2 ? 'col-md-2 col-sm-3 col-xs-4' : 'col-md-5ths' }}">
@endunless
    <div
        class="item {{ isset($itemClass) ? $itemClass : '' }} {{ $rare ? '' : 'item-' . $item->id }} {{ $roulette ? 'items-roulette-item' : '' }} {{ !$disabled ? '' : 'item-dizabled' }} cursor-pointer"
        @unless($rare)
            id="item-{{ $item->id }}"
            data-itemid="{{ $item->id }}"
            data-blockid="{{ $loop->iteration }}"
            data-image="{{ $item->image }}"
        @endunless
        @unless($abstract)
             data-rarity="{{ $item->rarity_class }}"
             data-condition="{{ $item->condition->title}}"
             data-conditionabbr="{{ $item->condition_abbr }}"
             data-collection="{{ $item->itemCase()->first()->id }}"
             data-tooltip-content="#tooltip-item"
        @endunless
    >
        @unless($roulette)
            @if(! $abstract)
                <div class="item-border{{ $item->stattrak ? '-st' : '' }}{{ $item->souvenir ? '-sv' : '' }} item-border-{{ $item->rarity_class }}{{ $item->stattrak ? '-st' : '' }}{{ $item->souvenir ? '-sv' : '' }}">
            @else
                <div class="item-border {{ $rare ? 'item-border-rare' : 'item-border-' . strtolower($item->rarity->title) }}">
            @endif
        @endunless
            <div class="item-wrap">
                <div class="item-image-block">
                    @unless($abstract)
                        <button class="btn btn-xs btn-default item-btn item-condition-btn {{ $item->condition_abbr == 'FN' ? 'condition-fn' : '' }} pull-left">{{ $item->condition_abbr }}</button>
                        @if($item->stattrak)
                            <button class="btn btn-xs pull-right item-btn item-stattrak-btn">
                                <span class="item-stattrak">ST</span>
                            </button>
                        @endif
                        @if($item->souvenir)
                            <button class="btn btn-xs pull-right item-btn item-stattrak-btn item-souvenir-btn">
                                <span class="item-stattrak item-souvenir">SV</span>
                            </button>
                        @endif
                    @endunless
{{--                    <img src="{{ asset('storage/images/itemz/' . $rare ? $rare_image : $item->image) }}"--}}
                    <img src="{{ asset('storage/images/itemz/' . ($rare ? $rare_image : $item->image)) }}"
                         alt="{{ $abstract ? $rare ? $rare_title_weapon : $item->weaponName->title : $item->baseItem->weaponName->title }}"
                         class="item-image {{ $abstract ? 'item-abstract-image' : '' }} center-block"
                    >
                    @unless($abstract)
                        <button class="btn btn-xs btn-default item-btn item-price-btn">$<span class="item-price">{{ number_format($item->price, 2) }}</span> </button>
                        @unless($item->items_count <= 1)
                            <button class="btn btn-xs btn-default item-btn pull-right item-amount-btn">x<span class="item-amount">{{ $item->items_count }}</span> </button>
                        @endunless
                    @endunless
                    <div class="item-image-shadow"></div>
                </div>
                @if(isset($collection))
                    <div class="item-title item-background-{{strtolower($rarity)}}">
                @else
                    <div class="item-title item-background-{{ $abstract ? $rare ? 'rare' : strtolower($item->rarity->title) : strtolower($item->baseItem->rarity->title) }}">
                @endif
                    <p class="item-title-weapon text-center">
                        @unless($abstract)
                            {{ $item->stattrak ? 'Strattrak &#8482; ' : '' }}
                            {{ $item->souvenir ? 'Souvenir ' : '' }}
                        @endunless
                        {{  $abstract ? $rare ? $rare_title_weapon : $item->weaponName->title : $item->baseItem->weaponName->title }}
                    </p>
                    <p class="item-title-pattern text-center">
                        {{ $abstract ? $rare ? $rare_title_pattern : $item->weaponPattern->title : $item->baseItem->weaponPattern->title }}
                    </p>
                </div>
            </div>
        </div>
        @unless($roulette)
            </div>
            </div>
        @endunless
